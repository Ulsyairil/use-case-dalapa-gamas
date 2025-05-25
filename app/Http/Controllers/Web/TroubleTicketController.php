<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\TroubleTicket;
use App\Resources\CustomResponse;
use App\Resources\ResponseMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class TroubleTicketController extends Controller
{
    public function listTicketView() {
        $actorId   = Session::get('actor_id');
        $cacheData = Cache::get('admin_' . $actorId);

        return view('pages.incident-ticket.list-incident-ticket', [
            'username'    => $cacheData['username'],
            'access_name' => $cacheData['access_name'],
        ]);
    }

    public function detailTicketView() {
        $actorId   = Session::get('actor_id');
        $cacheData = Cache::get('admin_' . $actorId);

        return view('pages.incident-ticket.detail-incident-ticket', [
            'username'    => $cacheData['username'],
            'access_name' => $cacheData['access_name'],
        ]);
    }

    public function listTicket(Request $request) {
        $page   = $request->input('page', 1);
        $length = $request->input('length', 10);
        $sort   = $request->input('sort', 'created_at');
        $order  = $request->input('order', 'desc');

        try {
            $actorId   = Session::get('actor_id');
            $cacheData = Cache::get('admin_' . $actorId);
            $token     = $cacheData['token'];

            $parameter = [
                'actor_id' => $actorId,
                'token'    => $token,
                'page'     => $page,
                'length'   => $length,
                'sort'     => $sort,
                'order'    => $order,
            ];

            $response = Http::timeout(5)->get(url('/api/ticket'), $parameter);
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

            $tmp = [];
            foreach ($dataBody['data']['data'] as $key => $value) {
                $tmp[] = [
                    0 => $value['id'],
                    1 => $value['ticket_number'],
                    2 => $value['headline'],
                    3 => $value['description'],
                    4 => $value['status'],
                    5 => $value['created_at'],
                    6 => $value['updated_at'],
                    7 => $value['created_at'],
                ];
            }

            $dataBody['data']['data'] = $tmp;

            // Unset Unnecessary Data
            unset($dataBody['data']['first_page_url']);
            unset($dataBody['data']['last_page_url']);
            unset($dataBody['data']['next_page_url']);
            unset($dataBody['data']['prev_page_url']);
            unset($dataBody['data']['links']);
            unset($dataBody['data']['path']);
            
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

    public function detailTicket(Request $request) {
        $itemId = $request->input('item_id');

        $validator = Validator::make($request->all(), [
            'item_id' => 'required|string|min:36|max:36',
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
                'item_id'  => $itemId
            ];

            $response = Http::timeout(1)->get(url('/api/ticket/detail'), array_merge($parameter));
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

            $data = $dataBody['data'];

            $tmp  = [];
            $tmp[0] = $data['id'] ?? '-'; // WO ID
            $tmp[1] = strtoupper($data['status']) ?? '-'; // WO Status

            $ticketStatus = '';
            switch ($data['ticket']['status']) {
                case TroubleTicket::OPEN:
                    $ticketStatus = 'OPEN';
                    break;
                
                case TroubleTicket::IN_PROGRESS:
                    $ticketStatus = 'IN PROGRESS';
                    break;
                
                case TroubleTicket::CLOSED:
                    $ticketStatus = 'CLOSED';
                    break;
                
                default:
                    $ticketStatus = '-';
                    break;
            }
            $tmp[2] = [ // Ticket Detail
                0 => $data['ticket']['ticket_number'] ?? '-',
                1 => $data['ticket']['headline'] ?? '-',
                2 => $data['ticket']['description'] ?? '-',
                3 => $ticketStatus,
            ];
            
            $tmp[3] = [ // Technician
                0 => $data['technician']['fullname'] ?? '-',
                1 => $data['technician']['nik'] ?? '-',
                2 => $data['technician']['email'] ?? '-',
                3 => $data['technician']['phone'] ?? '-',
            ];
            
            $tmp[4] = [ // Segment
                0 => strtoupper($data['segment']['segment']) ?? '-',
                1 => $data['segment']['address'] ?? '-',
                2 => $data['segment']['coordinates'] ?? '-',
            ];
            
            $tmp[5] = [ // Technical Information
                0 => $data['technical_information']['description'] ?? '-',
            ];

            $tmp[6] = [ // WO Image
                0 => $data['wo_image'][0]['image']['type'] == 'before' ? $data['wo_image'][0]['image']['url'] : '-',
                1 => $data['wo_image'][1]['image']['type'] == 'after' ? $data['wo_image'][1]['image']['url'] : '-',
            ];

            $tmp[7] = []; // Material Usage
            foreach ($data['material_usage'] as $key => $value) {
                $tmp[7][$key] = [
                    0 => $value['material']['name'] ?? '-',
                    1 => $value['material']['code'] ?? '-',
                    3 => $value['note'] ?? '-',
                    4 => $value['quantity'] ?? '-',
                    6 => $value['total_price'] ?? '-',
                    7 => $value['material']['quantity'] ?? '-',
                    8 => $value['material']['price'] ?? '-',
                    9 => $value['material']['total_price'] ?? '-',
                ];
            }

            $tmp[8] = $data['created_at'] ?? '-';
            $tmp[9] = $data['updated_at'] ?? '-';

            $tmp[10] = [ // WO Verification
                0 => $data['wo_verification']['status'] ?? '',
                1 => $data['wo_verification']['note'] ?? '',
            ];

            $this->code    = $dataBody['code'];
            $this->message = $dataBody['message'];
            $this->data    = $tmp;
        } catch (\Exception $e) {
            $data = get_defined_vars();
            unset($data['e']);
            $this->writeException($e, $data);
        }

        return CustomResponse::createResponse($this->data, $this->code, $this->message);
    }
}
