<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Resources\CustomResponse;
use App\Resources\ResponseMessage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class AdminController extends Controller
{
    public function loginView() {
        return view('login');
    }

    public function login(Request $request) {
        $username = $request->input('username');
        $password = $request->input('password');

        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            if ($validator->fails()) {
                $this->code    = 1;
                $this->message = $validator->errors()->first();
                throw new \Exception($this->message);
            }

            $response = Http::timeout(5)->post(url("/api/login"), [
                'username' => $username,
                'password' => $password
            ]);

            $status   = $response->status();
            $dataBody = $response->json();

            if ($status != 200) {
                $this->code    = 900;
                $this->message = ResponseMessage::getMessage($this->code);
                throw new \Exception($this->message);
            }

            if ($dataBody['code'] != 0) {
                $this->code    = $dataBody['code'];
                $this->message = $dataBody['message'];
                throw new \Exception($this->message);
            }

            $data    = $dataBody['data'];
            $actorId = $data['actor_id'];
            $expired = Carbon::parse($data['expired'])->diffInSeconds();

            Session::put('actor_id', $actorId);
            Cache::put('admin_' . $actorId, $data, $expired);

            $this->code    = 0;
            $this->message = ResponseMessage::getMessage($this->code);
        } catch (\Exception $e) {
            $data = get_defined_vars();
            unset($data['e']);
            $this->writeException($e, $data);
        }

        return CustomResponse::createResponse($this->data, $this->code, $this->message);
    }

    public function changeLocale(Request $request) {
        $locale = $request->input('locale');

        $validator = Validator::make($request->all(), [
            'locale' => 'required|string|in:en,id',
        ]);
        
        try {
            if ($validator->fails()) {
                $this->code    = 1;
                $this->message = $validator->errors()->first();
                throw new \Exception($this->message);
            }

            App::setLocale($locale);
            LaravelLocalization::setLocale($locale);

            $this->code    = 0;
            $this->message = ResponseMessage::getMessage($this->code);
        } catch (\Exception $e) {
            $data = get_defined_vars();
            unset($data['e']);
            $this->writeException($e, $data);
        }

        return CustomResponse::createResponse($this->data, $this->code, $this->message);
    }

    public function logout(Request $request) {
        try {
            $actorId   = Session::get('actor_id');
            $cacheData = Cache::get('admin_' . $actorId);
            $token     = $cacheData['token'];

            $parameter = [
                'actor_id' => $actorId,
                'token'    => $token,
            ];

            $response = Http::timeout(5)->post(url('/api/logout'), $parameter);
            $status   = $response->status();
            $dataBody = $response->json();

            if ($status != 200) {
                $this->code    = 900;
                $this->message = ResponseMessage::getMessage($this->code);
                throw new \Exception($this->message);
            }

            if ($dataBody['code'] != 0) {
                $this->code    = $dataBody['code'];
                $this->message = ResponseMessage::getMessage($this->code);
                throw new \Exception($this->message);
            }

            Session::forget('actor_id');
            Cache::forget('admin_' . $actorId);

            $this->code    = 0;
            $this->message = ResponseMessage::getMessage($this->code);
        } catch (\Exception $e) {
            $data = get_defined_vars();
            unset($data['e']);
            $this->writeException($e, $data);
        }

        return CustomResponse::createResponse($this->data, $this->code, $this->message);
    }
}
