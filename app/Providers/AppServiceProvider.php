<?php

namespace App\Providers;

use Exception, Validator;
use App\Jobs\SendSms;
use App\Models\Service\ServiceCredit;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\ServiceProvider;
use Intervention\Image\ImageManagerStatic;
use Illuminate\Database\Eloquent\Relations\Relation;
use Auth, Blade, Config, Log, Mail, Queue, URL, Schema, DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \DB::listen(function ($query) {
            \Log::info("[$query->time ms] " . $query->sql, $query->bindings);
        });
        Schema::defaultStringLength(191);

        if (app()->environment() === 'production') {
            URL::forceScheme('https');
            $this->app->alias('bugsnag.multi', \Psr\Log\LoggerInterface::class);
        }

        view()->composer('*', function ($view) {
            $view->with('authUser', Auth::user());
        });

        // Call to Laratrust::can
        Blade::if('can', function ($permission, $requireAll = false) {
            return app('laratrust')->can($permission, $requireAll);
        });

        Queue::failing(function (JobFailed $event) {
            Mail::send([], [], function ($mail) use ($event) {
                $subject = 'Queue failed for job ' . $event->job->resolveName();
                $content = 'Exception ' . $event->exception->getMessage() . '<br /><br />Error Trace:<br />' . $event->exception->getTraceAsString();

                $mail->to(env('MAIL_USERNAME', 'jimmy@loyalto.id'))
                    ->subject($subject)
                    ->setBody($content, 'text/html');
            });

            while (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
        });

        Queue::looping(function () {
            while (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
        });

        Validator::extend('imageable', function ($attribute, $value, $params, $validator) {
            try {
                ImageManagerStatic::make($value);
                return true;
            } catch (Exception $e) {
                return false;
            }
        }, 'The :attribute must be an image.');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
