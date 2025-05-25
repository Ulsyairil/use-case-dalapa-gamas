@extends('layout.dashboard')

@section('title', __('custom.detail_title'))

@section('content')
<div class="nk-content-inner">
    <div class="nk-block">
        <div class="row g-1">
            {{-- Ticket Detail --}}
            <div class="col-md-6">
                <div class="card card-bordered card-stretch" id="ticket_content">
                    <div class="card-inner-group">
                        <div class="card-inner">
                            <h5 class="card-title">{{ __('custom.detail_title') }}</h5>

                            <div class="form-group">
                                <label for="ticket_number">{{ __('custom.ticket_number') }}</label>
                                <input type="text" class="form-control" id="ticket_number" placeholder="{{ __('custom.ticket_number') }}" disabled>
                            </div>

                            <div class="form-group">
                                <label for="headline">{{ __('custom.headline') }}</label>
                                <input type="text" class="form-control" id="headline" placeholder="{{ __('custom.headline') }}" disabled>
                            </div>

                            <div class="form-group">
                                <label for="ticket_description">{{ __('custom.description') }}</label>
                                <input type="text" class="form-control" id="ticket_description" placeholder="{{ __('custom.description') }}" disabled>
                            </div>

                            <div class="form-group">
                                <label for="ticket_status">{{ __('custom.status') }}</label>
                                <input type="text" class="form-control" id="ticket_status" placeholder="{{ __('custom.status') }}" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Technical Detail --}}
            <div class="col-md-6">
                <div class="card card-bordered card-stretch" id="technician_content">
                    <div class="card-inner-group">
                        <div class="card-inner">
                            <h5 class="card-title">{{ __('custom.technician_detail') }}</h5>

                            <div class="form-group">
                                <label for="technician_name">{{ __('custom.technician_name') }}</label>
                                <input type="text" class="form-control" id="technician_name" placeholder="{{ __('custom.technician_name') }}" disabled>
                            </div>

                            <div class="form-group">
                                <label for="nik">{{ __('custom.nik') }}</label>
                                <input type="text" class="form-control" id="nik" placeholder="{{ __('custom.nik') }}" disabled>
                            </div>

                            <div class="form-group">
                                <label for="email">{{ __('custom.email') }}</label>
                                <input type="text" class="form-control" id="email" placeholder="{{ __('custom.email') }}" disabled>
                            </div>

                            <div class="form-group">
                                <label for="phone_number">{{ __('custom.phone_number') }}</label>
                                <input type="text" class="form-control" id="phone_number" placeholder="{{ __('custom.phone_number') }}" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Segment Detail --}}
            <div class="col-md-6">
                <div class="card card-bordered card-stretch" id="segment_content">
                    <div class="card-inner-group">
                        <div class="card-inner">
                            <h5 class="card-title">{{ __('custom.segment_detail') }}</h5>

                            <div class="form-group">
                                <label for="segment">{{ __('custom.segment') }}</label>
                                <input type="text" class="form-control" id="segment" placeholder="{{ __('custom.segment') }}" disabled>
                            </div>

                            <div class="form-group">
                                <label for="address">{{ __('custom.address') }}</label>
                                <textarea class="form-control" id="address" disabled></textarea>
                            </div>

                            <a href="javascript:void(0)" class="btn btn-primary" target="_blank" id="map">
                                {{ __('custom.view_location') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Technical Information --}}
            <div class="col-md-6">
                <div class="card card-bordered card-stretch" id="technical_content">
                    <div class="card-inner-group">
                        <div class="card-inner">
                            <h5 class="card-title">{{ __('custom.technical_information') }}</h5>

                            <div class="form-group">
                                <label for="technical_description">{{ __('custom.description') }}</label>
                                <textarea class="form-control" id="technical_description" cols="30" rows="12" disabled></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Material Usage Detail --}}
            <div class="col-md-6">
                <div class="card card-bordered card-stretch" id="material_usage_content">
                    <div class="card-inner-group">
                        <div class="card-inner">
                            <h5 class="card-title">{{ __('custom.material_usage_detail') }}</h5>

                            <div class="table-responsive text-nowrap">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ __('custom.material_name') }}</th>
                                            <th>{{ __('custom.material_code') }}</th>
                                            <th>{{ __('custom.note') }}</th>
                                            <th>{{ __('custom.used_quantity') }}</th>
                                            <th>{{ __('custom.total_price') }}</th>
                                            <th>{{ __('custom.stock_quantity') }}</th>
                                            <th>{{ __('custom.unit_price') }}</th>
                                            <th>{{ __('custom.stock_total_price') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="material-usage-body">
                                        <tr>
                                            <td colspan="8" style="text-align:center;">{{ __('custom.no_data') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Image & Verification Status --}}
            <div class="col-md-6">
                <div class="card card-bordered card-stretch" id="image_content">
                    <div class="card-inner-group">
                        <div class="card-inner">
                            <h5 class="card-title">{{ __('custom.image_and_verification_status') }}</h5>

                            <div class="mb-2">
                                <button type="button" class="btn btn-primary" id="btnImageBefore" data-bs-toggle="modal" data-bs-target="#modalImageBefore" disabled>
                                    {{ __('custom.image_before') }}
                                </button>

                                <button type="button" class="btn btn-primary" id="btnImageAfter" data-bs-toggle="modal" data-bs-target="#modalImageAfter" disabled>
                                    {{ __('custom.image_after') }}
                                </button>
                            </div>

                            <div class="form-group">
                                <label for="created_at">{{ __('custom.created_at') }}</label>
                                <input type="text" class="form-control" id="created_at" placeholder="{{ __('custom.created_at') }}" disabled>
                            </div>

                            <div class="form-group">
                                <label for="updated_at">{{ __('custom.updated_at') }}</label>
                                <input type="text" class="form-control" id="updated_at" placeholder="{{ __('custom.updated_at') }}" disabled>
                            </div>

                            <div class="form-group">
                                <label for="status_wo">{{ __('custom.workorder_status') }}</label>
                                <input type="text" class="form-control" id="status_wo" placeholder="{{ __('custom.workorder_status') }}" disabled>
                            </div>

                            <form>
                                <input type="hidden" id="wo_id">

                                <div class="form-group">
                                    <label for="status">{{ __('custom.verification_status') }}</label>
                                    <select id="status" class="form-select" required disabled>
                                        <option value="" selected>{{ __('custom.select_status') }}</option>
                                        <option value="disetujui">{{ __('custom.approved') }}</option>
                                        <option value="ditolak">{{ __('custom.rejected') }}</option>
                                    </select>
                                </div>

                                <div class="form-group d-none" id="note_content">
                                    <label for="wo_note">{{ __('custom.note') }}</label>
                                    <textarea class="form-control" id="wo_note" disabled></textarea>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary mt-2" id="btn_save" disabled>{{ __('custom.save') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal --}}
<div class="modal fade" id="modalImageBefore" tabindex="-1" aria-labelledby="modalImageBeforeLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalImageBeforeLabel">{{ __('custom.image_before') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('custom.close') }}"></button>
            </div>
            <div class="modal-body">
                <img src="" alt="image-before" id="image_before">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('custom.close') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalImageAfter" tabindex="-1" aria-labelledby="modalImageAfterLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalImageAfterLabel">{{ __('custom.image_after') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('custom.close') }}"></button>
            </div>
            <div class="modal-body">
                <img src="" alt="image-after" id="image_after">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('custom.close') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection


@section('custom_js')
<script>
    const itemId = new URLSearchParams(window.location.search).get('id');
    fetchDetailIncidentTicket(itemId);

    $("#btn_save").click(function (e) { 
        e.preventDefault();
        verifyTicket(itemId);
    });

    $("#status").change(function (e) { 
        e.preventDefault();
        if ($(this).val() == "ditolak") {
            $("#note_content").removeClass("d-none");
            $("#wo_note").removeAttr("disabled");
        } else {
            $("#wo_note").val("");
            $("#wo_note").attr("disabled");
            $("#note_content").addClass("d-none");
        }
    });
</script>
@endsection