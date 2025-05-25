@extends('layout.dashboard')

@section('title', __('custom.list_incident_ticket'))

@section('content')
<div class="nk-content-inner">
    <div class="nk-block">
        <div class="card card-bordered card-stretch">
            <div class="card-inner-group">
                <div class="card-inner">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <h5 class="card-title">{{ __('custom.list_incident_ticket') }}</h5>
                        </div>

                        <div>
                            <span id="total-records" class="me-2">{{ __('custom.total_records') }}: 0</span>
                            
                            <button class="btn btn-primary" onclick="createSimulationTicket()" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{ __('custom.simulate_incident_ticket') }}">
                                <i class="fas fa-ticket-alt"></i>
                            </button>

                            <button class="btn btn-secondary" onclick="fetchIncidentTicket()" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{ __('custom.refresh') }}">
                                <i class="fas fa-sync"></i>
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive" id="table_container">
                        <table class="table" id="data-table">
                            <thead>
                                <tr>
                                    <th data-sortable="false">No</th>
                                    <th class="text-nowrap" data-sortable="false">{{ __('custom.ticket_number') }}</th>
                                    <th class="text-nowrap" data-sortable="false">{{ __('custom.headline') }}</th>
                                    <th data-sortable="false">{{ __('custom.description') }}</th>
                                    <th class="text-center" data-sortable="false">{{ __('custom.status') }}</th>
                                    <th class="text-nowrap text-right" data-column="created_at" data-sortable="true">{{ __('custom.created_at') }}</th>
                                    <th class="text-nowrap text-right" data-column="updated_at" data-sortable="true">{{ __('custom.updated_at') }}</th>
                                    <th class="text-nowrap">{{ __('custom.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="8" style="text-align:center;">{{ __('custom.no_data_available') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 mt-3">
                        <div class="d-flex align-items-center">
                            <label class="mb-0 me-2">
                                {{ __('custom.show') }}
                                <select id="recordsPerPage" class="form-select form-select-sm d-inline-block" style="width: auto;">
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                {{ __('custom.entries') }}
                            </label>
                        </div>
                    
                        <div class="d-flex align-items-center">
                            <input type="number" id="jumpToPage" class="form-control form-control-sm me-2" style="width: 90px;" min="1" placeholder="{{ __('custom.page') }}">
                            <button id="jumpToPageBtn" class="btn btn-sm btn-primary">
                                <i class="fas fa-arrow-right fa-2x"></i>
                            </button>
                        </div>
                    
                        <nav aria-label="Page navigation">
                            <ul class="pagination mb-0"></ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script>
    fetchIncidentTicket();
</script>
@endsection