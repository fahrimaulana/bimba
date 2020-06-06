<?php

use Carbon\Carbon;
use App\Models\Client;
use App\Models\Counter;
use App\Models\Preference;
use App\Models\Customer\Customer;
use Illuminate\Support\Facades\Config;
use App\Exceptions\ForbiddenPermissionAccessException;

function prefix($str, $length)
{
    return str_pad($str, $length, '0', STR_PAD_LEFT);
}

function excerpt($str, $maxLength = 155, $startPos = 0)
{
    $str = strip_tags($str);
    if (strlen($str) > $maxLength) {
        $excerpt   = substr($str, $startPos, $maxLength);
        $lastSpace = strrpos($excerpt, ' ');
        $excerpt   = substr($excerpt, 0, $lastSpace);
        $excerpt  .= '...';
    } else {
        $excerpt = $str;
    }

    return $excerpt;
}

function generateNewNim($isApi = false)
{
    $barcodeLength  = 8;
    $prefix         = client()->code;
    $lastCounter    = Counter::valueOf('murid');
    $nextCounter    = $lastCounter + 1;
    $codeLength     = $barcodeLength - strlen((string) $prefix);
    $newCode        = prefix($nextCounter, $codeLength);
    $newNim     = $prefix . $newCode;

    Counter::updateValueOf('murid', $nextCounter);

    return $newNim;
}


function isClosure($t)
{
    return is_object($t) && ($t instanceof Closure);
}

function isEmpty($string)
{
    return empty(trim($string));
}

function dmyToYmd($dmyDate)
{
    $date = explode('-', $dmyDate);

    return $date[2] . '-' . $date[1] . '-' . $date[0];
}

function ymdToDmy($dmyDate)
{
    $date = explode('-', $dmyDate);

    return $date[2] . '-' . $date[1] . '-' . $date[0];
}

function thousandSeparator($number, $commas = 0)
{
    return number_format($number, $commas, '.', '.');
}

function noDuplicatesInArr(array $array)
{
    return count($array) === count(array_flip($array));
}

function space_case($str)
{
    $str = snake_case($str);
    $str = str_replace('_', ' ', $str);
    $str = str_replace('-', ' ', $str);

    return title_case($str);
}

function validateDate($string, $dateFormat = 'Y-m-d')
{
    try {
        return Carbon\Carbon::createFromFormat($dateFormat, $string) !== false;
    } catch (\Exception $e) {
        return false;
    }
}

function validateMonth($string)
{
    $month = intval($string);

    return $month >= 1 && $month <= 12;
}

function checkPermissionTo($permissionCode, $requireAll = false)
{
    if (!userCan($permissionCode, $requireAll)) throw new ForbiddenPermissionAccessException($permissionCode);

    return true;
}

function userCan($permissionCode, $requireAll = false)
{
    return user()->can($permissionCode, $requireAll);
}

function unknownError(Exception $e, $message = 'Unknown Error! Please try again later.')
{
    DB::rollBack();
    report($e);

    return validationError($message);
}

function validationError($message = 'Validation Error!', $route = null)
{
    while (DB::transactionLevel() > 0) {
        DB::rollBack();
    }

    return $route
        ? redirect()->route($route)->withInput(request()->except('_token'))->withErrors($message)
        : redirect()->back()->withInput(request()->except('_token'))->withErrors($message);
}

function swalError($message = 'Validation Error!')
{
    while (DB::transactionLevel() > 0) {
        DB::rollBack();
    }

    return redirect()->back()->withInput(request()->except('_token'))->with('swal_error', $message);
}

function user()
{
    return Auth::user();
}

function existsOnCurrentClient($table)
{
    return 'exists:' . $table . ',id,client_id,' . clientId() . ',deleted_at,NULL';
}

function headerLogo()
{
    if (!Session::has('header_logo')) {
        // Session::put('header_logo', Preference::valueOf('logo') ?: asset('assets/images/logo.png'));
        Session::put('header_logo', asset('assets/images/logo.png'));
    }

    return Session::get('header_logo');
}

function uploadFile($file, $directory, $originalName = false, $resizeImage = false)
{
    $base64String = substr($file, strpos($file, ",") ? strpos($file, ",") + 1 : 0);
    if (isBase64($base64String)) {
        $imageString = base64_decode($base64String);
        $extension = optional(getimagesizefromstring($imageString))['mime'];
        $extension = array_values(array_slice(explode('/', $extension), -1))[0];
        if ($originalName === true || $originalName === false) {
            $filename = strtoupper(str_random(10)) . '-' . time() . '.' . $extension;
        } elseif ($originalName) {
            $filename = $originalName . '.' . $extension;
        }

        Storage::put($directory . '/' . $filename, $imageString);
    } else {
        if ($originalName === true) {
            $filename = preg_replace('@[^0-9a-z\.\s]+@i', '', $file->getClientOriginalName());
            $filename = str_replace(' ', '-', $filename);
        } elseif ($originalName) {
            $filename = $originalName . '.' . $file->getClientOriginalExtension();
        } else {
            $filename = strtoupper(str_random(10)) . '-' . time() . '.' . $file->getClientOriginalExtension();
        }

        Storage::putFileAs($directory, $file, $filename, 'public');
    }

    if (in_array($file->getClientOriginalExtension(), ['jpg', 'png', 'gif', 'webp'])) compressImage('uploads/' . $directory . '/' . $filename);
    if ($resizeImage) resizeImage('uploads/' . $directory . '/' . $filename);

    return Storage::url($directory . '/' . $filename);
}

function isBase64($base64String)
{
    return base64_encode(base64_decode($base64String, true)) === $base64String;
}

function deleteFile($fileUrl)
{
    $currentImagePath = explode(url('/') . '/', $fileUrl);
    $currentImagePath = isset($currentImagePath[1]) ? storage_path('app/public/' . $currentImagePath[1]) : null;
    if ($currentImagePath && File::isFile($currentImagePath)) {
        File::delete($currentImagePath);

        return true;
    } else {
        return false;
    }
}

function compressImage($pathToFile, $quality = 60, $savePath = null)
{
    Image::make($pathToFile)->save($savePath, $quality);

    return true;
}

function resizeImage($pathToFile, $width = 500, $height = null, $quality = 60, $savePath = null)
{
    Image::make($pathToFile)->resize($width, $height, function ($constraint) {
        $constraint->aspectRatio();
    })->save($savePath, $quality);

    return true;
}

function getUserIP()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if (filter_var($client, FILTER_VALIDATE_IP)) {
        $ip = $client;
    } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
        $ip = $forward;
    } else {
        $ip = $remote;
    }

    return $ip;
}

function generateUniqueSlug($model, $title, $excludeId = null)
{
    $slug = $originalSlug = str_slug($title, '-');
    $modelCount = $model::withoutGlobalScopes()->where('slug', $slug)->where('id', '!=', $excludeId)->count();

    $counter = 2;
    while ($modelCount > 0) {
        $slug = $originalSlug . '-' . $counter;
        $modelCount = $model::withoutGlobalScopes()->where('slug', $slug)->where('id', '!=', $excludeId)->count();
        $counter++;
    }

    return $slug;
}

function shortNumberFormat($amount, $precision = 0)
{
    if ($amount < 999) {
        $amountFormat = number_format($amount, $precision, ',', '.');
        $suffix = '';
    } else if ($amount < 999999) {
        $amountFormat = number_format($amount / 1000, $precision, ',', '.');
        $suffix = 'K';
    } else if ($amount < 999999999) {
        $amountFormat = number_format($amount / 1000000, $precision, ',', '.');
        $suffix = 'M';
    } else if ($amount < 999999999999) {
        $amountFormat = number_format($amount / 1000000000, $precision, ',', '.');
        $suffix = 'B';
    } else {
        $amountFormat = number_format($amount / 1000000000000, $precision, ',', '.');
        $suffix = 'T';
    }
    if ($precision > 0) {
        $dotzero = '.' . str_repeat('0', $precision);
        $amountFormat = str_replace($dotzero, '', $amountFormat);
    }

    return $amountFormat . $suffix;
}

function uniqueCode($model, $field, $length = null)
{
    $modelExists = true;
    while ($modelExists) {
        $code = str_random($length);
        $modelExists = $model::withoutGlobalScopes()->where($field, $code)->first();
    }

    return $code;
}

function setIfMatchUri($uri, $output = 'active')
{
    if (is_array($uri)) {
        foreach ($uri as $u) {
            if (Route::is($u)) {
                return $output;
            }
        }
    } else {
        if (Route::is($uri)) {
            return $output;
        }
    }
}

function uuid()
{
    return Uuid::generate()->string;
}

function getSql($model)
{
    $replace = function ($sql, $bindings) {
        $needle = '?';
        foreach ($bindings as $replace) {
            $pos = strpos($sql, $needle);
            if ($pos !== false) {
                if (gettype($replace) === "string") {
                    $replace = ' "' . addslashes($replace) . '" ';
                }
                $sql = substr_replace($sql, $replace, $pos, strlen($needle));
            }
        }
        return $sql;
    };
    $sql = $replace($model->toSql(), $model->getBindings());

    return $sql;
}

function platform()
{
    return Config::get('platform') ?: (user() ? user()->platform : null);
}

function client()
{
    if (Config::get('client.id')) {
        $client = Client::find(Config::get('client.id'));

        if ($client) return $client;
    }

    if ($client = optional(user())->client) {
        return $client;
    }

    Auth::logout();
    Session::flash('alert_error', 'Your account is suspended. Please contact us for more information!');

    return null;
}

function clientId()
{
    $client = client();

    return $client ? $client->id : null;
}

function guardType()
{
    $guardName = Auth::guard()->getName();

    return explode('_', $guardName)[1];
}

function chunkNums($total, Closure $action, $firstValue = 0)
{
    $iteration = floor($total / 10000);
    $rest = $total % 10000;

    if (!$total) return (object) compact('total', 'iteration', 'rest');

    foreach (range(0, $iteration) as $i) {
        if ($i == $iteration) {
            $action(range(($i * 10000) + $firstValue, ($i * 10000) + $rest + $firstValue - 1));
            break;
        }
        $action(range(($i * 10000) + $firstValue, (($i + 1) * 10000) + $firstValue - 1));
    }

    return (object) compact('total', 'iteration', 'rest');
}

function yearMonthFormat($date, $comparedDate = null)
{
    $diffMonth = $date->diffInMonths($comparedDate ?: today());
    $year = floor($diffMonth / 12);
    $month = $diffMonth % 12;

    return "{$year} Thn {$month} Bln";
}

function shortMonths()
{
    return [
        1 => 'Jan',
        2 => 'Feb',
        3 => 'Mar',
        4 => 'Apr',
        5 => 'Mei',
        6 => 'Jun',
        7 => 'Jul',
        8 => 'Ags',
        9 => 'Sep',
        10 => 'Okt',
        11 => 'Nov',
        12 => 'Des',
    ];
}

function lockedYear()
{
    return Session::get('locked_year');
}

function lockedMonth()
{
    return Session::get('locked_month');
}

function updateLockedPeriod($year, $month)
{
    Session::put('locked_year', $year);
    Session::put('locked_month', $month);

    return [lockedYear(), lockedMonth()];
}

function year()
{
    return lockedYear();
}

function month()
{
    return lockedMonth();
}

function lockedDate()
{
    return Carbon::parse(year() . '-' . month() . '-01');
}

function getTableColumns($table)
{
    return collect(\Schema::getColumnListing($table))->map(function ($col) use ($table) {
        return "{$table}.{$col}";
    })->toArray();
}
