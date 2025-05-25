<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @OA\Info(
     *     title="My API Documentation",
     *     version="0.0.1",
     *     description="This is the API documentation for Test Dalapa",
     *     @OA\Contact(
     *         email="ulsyairil@outlook.co.id"
     *     )
     * )
     */

    public $code    = 9;
    public $message = '';
    public $data    = [];

    public function writeException($e, $data = ''){
        $fullPath       = $e->getFile();
        $relativePath   = str_replace(base_path(), '', $fullPath);
        $endTime        = microtime(true);
        $executionTime  = round(($endTime - LARAVEL_START) * 1000, 2);

        $data = [
            'Line'  => $e->getLine(),
            'File'  => $relativePath,
            'Error' => $e->getMessage(),
            'Data'  => $data
        ];

        $information = [
            'Log Type'         => 'Exception',
            'Application Name' => env('APP_NAME'),
            'Incoming Time'    => round(LARAVEL_START, 0),
            'Request URI'      => request()->getRequestUri(),
            'Request Data'     => '',
            'Response Data'    => json_encode($data),
            'End Time'         => round($endTime, 0),
            'Execution Time'   => $executionTime
        ];

        Log::error(json_encode($information));
    }
}
