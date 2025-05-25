<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminToken;
use App\Resources\CustomResponse;
use App\Resources\ResponseMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use OpenApi\Annotations as OA;

class AdminController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Admin"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="username",
     *                     type="string",
     *                     example="admin"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     example="12345678"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="code",
     *                 type="integer",
     *                 example=0
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="token",
     *                     type="string",
     *                     example="token"
     *                 ),
     *                 @OA\Property(
     *                     property="actor_id",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="expired",
     *                     type="string",
     *                     example="2022-01-01 00:00:00"
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Success"
     *             )
     *         )
     *     )
     * )
     */
    public function login(Request $request) {
        $username = $request->input('username');
        $password = $request->input('password');

        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        try {
            if ($validator->fails()) {
                $this->code    = 1;
                $this->message = $validator->errors()->first();
                throw new \Exception($this->message);
            }

            $admin = Admin::query()->with(['access'])->where('username', $username)->first();
            if (!$admin) {
                $this->code    = 10;
                $this->message = ResponseMessage::getMessage($this->code);
                throw new \Exception($this->message);
            }

            if (Hash::check($password, $admin->password)) {
                $this->code    = 12;
                $this->message = ResponseMessage::getMessage($this->code);
                throw new \Exception($this->message);
            }

            if ($admin->is_active == 0) {
                $this->code    = 13;
                $this->message = ResponseMessage::getMessage($this->code);
                throw new \Exception($this->message);
            }

            $token = Str::random(60);

            AdminToken::query()->create([
                'admin_id'    => $admin->id,
                'token'       => $token,
                'expired_at'  => now()->addDays(1),
            ]);

            $data = [
                'actor_id'    => $admin->id,
                'username'    => $admin->username,
                'access_name' => $admin->access->access_name,
                'token'       => $token,
                'expired'     => now()->addDays(1),
            ];

            $this->code    = 0;
            $this->data    = $data;
            $this->message = ResponseMessage::getMessage($this->code);
        } catch (\Exception $e) {
            $data = get_defined_vars();
            unset($data['e']);
            $this->writeException($e, $data);
        }
        
        return CustomResponse::createResponse($this->data, $this->code, $this->message);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Admin"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="token",
     *                     type="string",
     *                     example="token"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="code",
     *                 type="integer",
     *                 example=0
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Success"
     *             )
     *         )
     *     )
     * )
     */
    public function logout(Request $request) {
        $token = $request->input('token');

        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
        ]);

        try {
            AdminToken::query()->where('token', $token)->delete();

            $this->code    = 0;
            $this->message = ResponseMessage::getMessage($this->code);
        } catch (\Exception $e) {
            $data = get_defined_vars();
            unset($data['e']);
            $this->writeException($e, $data);
        }
        
        return CustomResponse::createResponse([], $this->code, $this->message);
    }
}
