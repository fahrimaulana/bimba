<?php

namespace App\Exceptions;

use DB, Exception;
use Illuminate\Auth\AuthenticationException;
use App\Exceptions\GeneralValidationException;
use Illuminate\Session\TokenMismatchException;
use App\Exceptions\RestApiValidationErrorException;
use App\Exceptions\ForbiddenPermissionAccessException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
        \App\Exceptions\RestApiValidationErrorException::class,
        \App\Exceptions\RestApiValidationErrorException::class,
        \App\Exceptions\ForbiddenPermissionAccessException::class,
        \League\OAuth2\Server\Exception\OAuthServerException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    public function render($request, Exception $exception)
    {
        if ($this->isApi($request)) return $this->restRender($request, $exception);

        if ($exception instanceof GeneralValidationException) {
            while (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            return redirect()->back()->withInput($request->except('_token'))
                             ->with('swal_error', $exception->getMessage());
        } elseif ($exception instanceof TokenMismatchException) {
            return redirect()->back()->withInput($request->except('_token'))
                             ->with('swal_error', 'Sorry, Your session is ended Please try again!');
        } elseif ($exception instanceof ForbiddenPermissionAccessException) {
            return redirect()->route(guardType() . '.dashboard')
                             ->with('swal_error', 'You don\'t have permission to ' . $exception->getPermissionName());
        }

        return parent::render($request, $exception);
    }

    public function restRender($request, Exception $exception)
    {
        if ($exception instanceof RestApiValidationErrorException) {
            $errorData = $exception->getErrors();

            return response()->json($errorData, 422);
        } elseif ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {
            $message = $exception instanceof ModelNotFoundException ? 'Data not found!' : 'Endpoint not found!';

            return response()->json([
                'status' => 'error',
                'status_code' => 404,
                'message' => $message,
                'errors' => [$message]
            ], 404);
        } elseif ($exception instanceof HttpException) {
            return response()->json([
                'status' => 'error',
                'status_code' => $exception->getStatusCode(),
                'message' => $exception->getMessage(),
                'errors' => [$exception->getMessage()]
            ], $exception->getStatusCode());
        }

        if (!(bool) config('app.debug') && !$exception instanceof AuthenticationException) {
            $this->report($exception);

            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => 'Unknown error!',
                'errors' => ['Unknown error!'],
            ], 500);
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($this->isApi($request)) {
            return response()->json([
                'status' => 'error',
                'status_code' => 401,
                'message' => 'Unauthenticated',
                'errors' => ['Unauthenticated']
            ], 401);
        }
        $guard = array_get($exception->guards(), 0);

        return redirect()->guest(route($guard . '.login.show-form'));
    }

    private function isApi($request)
    {
        return $request->expectsJson() || $request->is('api*');
    }
}
