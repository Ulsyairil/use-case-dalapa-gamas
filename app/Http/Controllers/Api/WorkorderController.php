<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Material;
use App\Models\MaterialUsage;
use App\Models\Segment;
use App\Models\TechnicalInformation;
use App\Models\Technician;
use App\Models\TroubleTicket;
use App\Models\Workorder;
use App\Models\WorkorderImage;
use App\Models\WorkorderVerification;
use App\Resources\CustomResponse;
use App\Resources\ResponseMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

class WorkorderController extends Controller
{
    public $faker;

    public function __construct() {
        $this->faker = Faker::create('id_ID');
    }

    /**
     * @OA\Get(
     *     path="/api/ticket/simulate",
     *     summary="Simulate creation of a trouble ticket with related data",
     *     tags={"Tickets"},
     *     @OA\Response(
     *         response=200,
     *         description="Response indicating success or failure",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=0),
     *             @OA\Property(property="message", type="string", example="Success or error message"),
     *             @OA\Property(property="data", type="object", nullable=true, description="Usually null here")
     *         )
     *     )
     * )
     */
    public function simulateTicketOrder(Request $request) {
        try {
            DB::beginTransaction();

            TroubleTicket::setCurrentUser('system');
            $ticket = TroubleTicket::query()->create([
                'ticket_number' => 'TN-' . time(),
                'headline'      => $this->faker->sentence(),
                'description'   => $this->faker->paragraph(),
                'status'        => TroubleTicket::OPEN,
            ]);

            $technician = Technician::query()->inRandomOrder()->first();
            if (!$technician) {
                Technician::setCurrentUser('system');
                $technician = Technician::query()->create([
                    'fullname' => $this->faker->name(),
                    'nik'      => $this->faker->unique()->randomNumber(6, true),
                    'email'    => $this->faker->unique()->email(),
                    'phone'    => $this->faker->unique()->phoneNumber(),
                ]);
            }

            Workorder::setCurrentUser('system');
            $woStatuses     = [Workorder::DRAFT, Workorder::SELESAI, Workorder::DIVERIFIKASI, Workorder::REVISI];
            $randomWoStatus = $woStatuses[array_rand($woStatuses, 1)];
            $workorder      = Workorder::query()->create([
                'technician_id' => $technician->id,
                'ticket_id'     => $ticket->id,
                'status'        => $randomWoStatus,
                'is_submitted'  => $randomWoStatus == Workorder::SELESAI || $randomWoStatus == Workorder::DIVERIFIKASI ? 1 : 0,
            ]);

            Segment::setCurrentUser('system');
            $segments      = [Segment::FEEDER, Segment::DISTRIBUSI];
            $randomSegment = $segments[array_rand($segments, 1)];
            Segment::query()->create([
                'workorder_id' => $workorder->id,
                'segment'      => $randomSegment,
                'address'      => $this->faker->address(),
                'coordinates'  => $this->faker->latitude() . ',' . $this->faker->longitude(),
            ]);

            $material = Material::query()->inRandomOrder()->first();

            MaterialUsage::setCurrentUser('system');
            $quantity = $this->faker->randomNumber(2);
            MaterialUsage::query()->create([
                'workorder_id' => $workorder->id,
                'material_id'  => $material->id,
                'quantity'     => $quantity,
                'price'        => $material->price,
                'total_price'  => $material->price * $quantity,
                'note'         => $this->faker->sentence(),
            ]);

            TechnicalInformation::setCurrentUser('system');
            TechnicalInformation::query()->create([
                'workorder_id' => $workorder->id,
                'description'  => $this->faker->paragraph(),
            ]);

            Image::setCurrentUser('system');
            $woImageBefore = Image::query()->create([
                'name'  => $this->faker->sentence(),
                'type'  => Image::BEFORE,
                'ext'   => 'png',
                'path'  => '/',
                'url'   => $this->faker->imageUrl(),
            ]);

            $woImageAfter = Image::query()->create([
                'name'  => $this->faker->sentence(),
                'type'  => Image::AFTER,
                'ext'   => 'png',
                'path'  => '/',
                'url'   => $this->faker->imageUrl(),
            ]);

            WorkorderImage::query()->create([
                'workorder_id' => $workorder->id,
                'image_id'     => $woImageBefore->id,
            ]);

            WorkorderImage::query()->create([
                'workorder_id' => $workorder->id,
                'image_id'     => $woImageAfter->id,
            ]);

            if ($randomWoStatus != Workorder::DRAFT) {
                if ($randomWoStatus == Workorder::SELESAI) {
                    WorkorderVerification::setCurrentUser('system');
                    WorkorderVerification::query()->create([
                        'workorder_id'  => $workorder->id,
                        'status'        => WorkorderVerification::DISETUJUI,
                        'note'          => $this->faker->sentence(),
                    ]);

                    $ticket->status = TroubleTicket::CLOSED;
                    $ticket->save();
                }

                if ($randomWoStatus == Workorder::REVISI) {
                    WorkorderVerification::setCurrentUser('system');
                    WorkorderVerification::query()->create([
                        'workorder_id'  => $workorder->id,
                        'status'        => WorkorderVerification::DITOLAK,
                        'note'          => $this->faker->sentence(),
                    ]);

                    $ticket->status = TroubleTicket::IN_PROGRESS;
                    $ticket->save();
                }
            }

            if ($randomWoStatus == Workorder::DIVERIFIKASI) {
                $ticket->status = TroubleTicket::IN_PROGRESS;
                $ticket->save();
            }

            DB::commit();

            $this->code    = 0;
            $this->message = ResponseMessage::getMessage($this->code);
        } catch (\Exception $e) {
            DB::rollBack();
            $data = get_defined_vars();
            unset($data['e']);
            $this->writeException($e, $data);
        }

        return CustomResponse::createResponse($this->data, $this->code, $this->message);
    }

    /**
     * @OA\Post(
     *     path="/api/ticket/verification",
     *     summary="Verifikasi status work order (disetujui/ditolak)",
     *     tags={"Tickets"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"actor_id", "item_id", "status"},
     *             @OA\Property(property="actor_id", type="string", example="user-123"),
     *             @OA\Property(property="item_id", type="string", example="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx"),
     *             @OA\Property(property="status", type="string", enum={"DISETUJUI", "DITOLAK"}, example="DISETUJUI"),
     *             @OA\Property(property="note", type="string", example="Verifikasi berhasil", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Response sukses atau gagal",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=0),
     *             @OA\Property(property="message", type="string", example="Success or error message"),
     *             @OA\Property(property="data", type="object", nullable=true)
     *         )
     *     )
     * )
     */
    public function verification(Request $request) {
        $actorId = $request->input('actor_id');
        $itemId  = $request->input('item_id');
        $status  = $request->input('status');
        $note    = $request->input('note');

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

            DB::beginTransaction();

            WorkorderVerification::setCurrentUser($actorId);
            WorkorderVerification::query()->updateOrCreate([
                'workorder_id' => $itemId,
            ],[
                'status'       => $status,
                'note'         => $note,
            ]);

            if ($status == WorkorderVerification::DISETUJUI) {
                Workorder::setCurrentUser($actorId);
                $workorder = Workorder::query()->where('id', $itemId)->first();
                $workorder->status = Workorder::SELESAI;
                $workorder->save();

                TroubleTicket::setCurrentUser($actorId);
                $ticket = TroubleTicket::query()->where('id', $workorder->ticket_id)->first();
                $ticket->status = TroubleTicket::CLOSED;
                $ticket->save();

                $checkMaterialUsed = MaterialUsage::query()->where('workorder_id', $itemId)->get();
                if (!empty($checkMaterialUsed)) {
                    foreach ($checkMaterialUsed as $key => $value) {
                        Material::setCurrentUser($actorId);
                        $material = Material::query()->where('id', $value->material_id)->first();

                        if (($material->quantity - $value->quantity) < 0) {
                            $this->code    = 1;
                            $this->message = 'Material ' . $material->name . ' not enough';
                            throw new \Exception($this->message);
                        }

                        $material->quantity    = $material->quantity - $value->quantity;
                        $material->total_price = $material->quantity * $material->price;
                        $material->save();
                    }
                }
            }

            if ($status == WorkorderVerification::DITOLAK) {
                Workorder::setCurrentUser($actorId);
                $workorder = Workorder::query()->where('id', $itemId)->first();
                $workorder->status = Workorder::REVISI;
                $workorder->save();

                TroubleTicket::setCurrentUser($actorId);
                $ticket = TroubleTicket::query()->where('id', $workorder->ticket_id)->first();
                $ticket->status = TroubleTicket::IN_PROGRESS;
                $ticket->save();
            }

            DB::commit();

            $this->code    = 0;
            $this->message = ResponseMessage::getMessage($this->code);
        } catch (\Exception $e) {
            DB::rollBack();
            $data = get_defined_vars();
            unset($data['e']);
            $this->writeException($e, $data);
        }

        return CustomResponse::createResponse($this->data, $this->code, $this->message);
    }
}
