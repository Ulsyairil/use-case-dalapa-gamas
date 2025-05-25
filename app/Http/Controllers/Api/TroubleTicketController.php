<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TroubleTicket;
use App\Models\Workorder;
use App\Resources\CustomResponse;
use App\Resources\ResponseMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use OpenApi\Annotations as OA;

class TroubleTicketController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/ticket",
     *     summary="Get list of trouble tickets",
     *     tags={"Tickets"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="length",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Sort by field",
     *         required=false,
     *         @OA\Schema(type="string", default="created_at")
     *     ),
     *     @OA\Parameter(
     *         name="order",
     *         in="query",
     *         description="Sort order (asc or desc)",
     *         required=false,
     *         @OA\Schema(type="string", default="desc", enum={"asc","desc"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response with paginated tickets",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=0),
     *             @OA\Property(property="message", type="string", example="Success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(property="data", type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="headline", type="string"),
     *                         @OA\Property(property="status", type="string"),
     *                         @OA\Property(property="created_at", type="string", format="date-time"),
     *                     )
     *                 ),
     *                 @OA\Property(property="last_page", type="integer"),
     *                 @OA\Property(property="per_page", type="integer"),
     *                 @OA\Property(property="total", type="integer"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function listTicket(Request $request) {
        $page   = $request->input('page');
        $length = $request->input('length');
        $sort   = $request->input('sort', 'created_at');
        $order  = $request->input('order', 'desc');

        try {
            $data = TroubleTicket::query()
                ->orderBy(empty($sort) ? 'created_at' : $sort, empty($order) ? 'desc' : $order)
                ->paginate($length, ['*'], 'page', $page);

            $this->code    = 0;
            $this->message = ResponseMessage::getMessage($this->code);
            $this->data    = $data;
        } catch (\Exception $e) {
            $data = get_defined_vars();
            unset($data['e']);
            $this->writeException($e, $data);
        }

        return CustomResponse::createResponse($this->data, $this->code, $this->message);
    }

    /**
     * @OA\Get(
     *     path="/api/ticket/detail",
     *     summary="Get ticket detail by item ID",
     *     tags={"Tickets"},
     *     @OA\Parameter(
     *         name="item_id",
     *         in="query",
     *         description="Ticket ID (string, length 36)",
     *         required=true,
     *         @OA\Schema(type="string", minLength=36, maxLength=36)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Response with ticket detail or error message",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=0),
     *             @OA\Property(property="message", type="string", example="Success or error message"),
     *             @OA\Property(property="data", type="object", nullable=true, description="Ticket detail data or null if error")
     *         )
     *     )
     * )
     */
    public function ticketDetail(Request $request) {
        $itemId = $request->input('item_id');

        $validator = Validator::make($request->all(), [
            'item_id' => 'required|string|min:36|max:36'
        ]);

        try {
            if ($validator->fails()) {
                $this->code    = 1;
                $this->message = $validator->errors()->first();
                throw new \Exception($this->message);
            }

            $workorder = Workorder::query()
                ->with(['technician', 'ticket', 'segment', 'technical_information', 'wo_verification', 'material_usage.material', 'wo_image.image'])
                ->where('ticket_id', $itemId)
                ->first();
            if (!$workorder) {
                $this->code    = 1;
                $this->message = 'Ticket not found';
                throw new \Exception($this->message);
            }

            $this->code    = 0;
            $this->message = ResponseMessage::getMessage($this->code);
            $this->data    = $workorder;
        } catch (\Exception $e) {
            $data = get_defined_vars();
            unset($data['e']);
            $this->writeException($e, $data);
        }

        return CustomResponse::createResponse($this->data, $this->code, $this->message);
    }
}
