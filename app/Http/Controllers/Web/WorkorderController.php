<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\WorkorderVerification;
use App\Resources\CustomResponse;
use App\Resources\ResponseMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class WorkorderController extends Controller
{
    public function simulateTicket(Request $request) {
        try {
            $actorId   = Session::get('actor_id');
            $cacheData = Cache::get('admin_' . $actorId);
            $token     = $cacheData['token'];

            $parameter = [
                'actor_id' => $actorId,
                'token'    => $token,
            ];

            $response = Http::timeout(5)->get(url('/api/ticket/simulate'), $parameter);
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

            $this->code    = $dataBody['code'];
            $this->message = $dataBody['message'];
            $this->data    = $dataBody['data'];
        } catch (\Exception $e) {
            $data = get_defined_vars();
            unset($data['e']);
            $this->writeException($e, $data);
        }

        return CustomResponse::createResponse($this->data, $this->code, $this->message);
    }

    public function verification(Request $request) {
        $itemId = $request->input('item_id');
        $status = $request->input('status');
        $note   = $request->input('note');

        $validator = Validator::make($request->all(), [
            'item_id' => 'required|string|min:36|max:36',
            'status'  => 'required|string|in:' . implode(',', [WorkorderVerification::DISETUJUI, WorkorderVerification::DITOLAK]),
            'note'    => 'nullable|string',
        ]);

        try {
            if ($validator->fails()) {
                $this->code    = 1;
                $this->message = $validator->errors()->first();
                throw new \Exception($this->message);
            }

            $actorId   = Session::get('actor_id');
            $cacheData = Cache::get('admin_' . $actorId);
            $token     = $cacheData['token'];

            $parameter = [
                'actor_id' => $actorId,
                'token'    => $token,
                'item_id'  => $itemId,
                'status'   => $status,
                'note'     => $note,
            ];

            $response = Http::timeout(5)->asJson()->get(url('/api/ticket/verification'), $parameter);
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

            $this->code    = $dataBody['code'];
            $this->message = $dataBody['message'];
            $this->data    = $dataBody['data'];
        } catch (\Exception $e) {
            $data = get_defined_vars();
            unset($data['e']);
            $this->writeException($e, $data);
        }

        return CustomResponse::createResponse($this->data, $this->code, $this->message);
    }
}
