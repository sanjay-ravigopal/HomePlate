@extends('layouts.admin.app')

@section('title', translate('Evening_Delivery_Orders'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title mb-2 text-capitalize">
                <div class="card-header-icon d-inline-flex mr-2 img">
                    <img src="{{ dynamicAsset('/public/assets/admin/img/orders.png') }}" alt="public">
                </div>
                <span>
                    {{ translate('Evening Delivery') }} {{ translate('messages.orders') }}
                    @if($status != 'all')
                        - {{ translate(str_replace('_', ' ', $status)) }}
                    @endif
                </span>
                <span class="badge badge-soft-dark ml-2">{{ $total }}</span>
            </h1>
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header flex-wrap py-2">
                <div class="search--button-wrapper justify-content-end">
                    <form class="my-2 ml-auto mr-sm-2 mr-xl-4 ml-sm-auto flex-grow-1 flex-grow-sm-0">
                        <!-- Search -->
                        <div class="input--group input-group input-group-merge input-group-flush">
                            <input id="datatableSearch_" type="search" name="search" class="form-control"
                                value="{{ request()?->search ?? null }}"
                                placeholder="{{ translate('messages.Ex:_Search your order...') }}"
                                aria-label="{{ translate('messages.search') }}">
                            <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                        </div>
                        <!-- End Search -->
                    </form>
                    <!-- Datatable Info -->
                    <!-- End Datatable Info -->

                    <!-- Unfold -->
                    <!-- End Unfold -->
                    @if (Request::is('admin/refund/*'))
                        <div class="select-item">
                            <select name="slist" class="form-control js-select2-custom refund-filter">
                                <option {{ $status == 'requested' ? 'selected' : '' }}
                                    value="{{ route('admin.refund.refund_attr', ['requested']) }}">
                                    {{ translate('messages.Refund_Requests') }}</option>
                                <option {{ $status == 'refunded' ? 'selected' : '' }}
                                    value="{{ route('admin.refund.refund_attr', ['refunded']) }}">
                                    {{ translate('messages.Refund') }}</option>
                                <option {{ $status == 'rejected' ? 'selected' : '' }}
                                    value="{{ route('admin.refund.refund_attr', ['rejected']) }}">
                                    {{ translate('Rejected') }}</option>
                            </select>
                        </div>
                    @endif
                    <!-- Unfold -->
                    <div class="hs-unfold">
                        <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle btn export-btn export--btn btn-outline-primary btn--primary font--sm"
                            href="javascript:"
                            data-hs-unfold-options='{
                                "target": "#usersExportDropdown",
                                "type": "css-animation"
                            }'>
                            <i class="tio-download-to mr-1"></i> {{ translate('messages.export') }}
                        </a>

                        <div id="usersExportDropdown"
                            class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">

                            <span class="dropdown-header">{{ translate('messages.download_options') }}</span>
                            <a id="export-excel" class="dropdown-item" href="javascript:">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{ dynamicAsset('public/assets/admin') }}/svg/components/excel.svg"
                                    alt="Image Description">
                                {{ translate('messages.excel') }}
                            </a>
                            <a id="export-csv" class="dropdown-item" href="javascript:">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{ dynamicAsset('public/assets/admin') }}/svg/components/placeholder-csv-format.svg"
                                    alt="Image Description">
                                {{ translate('messages.csv') }}
                            </a>

                        </div>
                    </div>
                    <!-- End Unfold -->
                    <!-- Unfold -->

                    @if ( isset($status) && $status !=  'dine_in')
                        <div class="hs-unfold">
                            <a class="js-hs-unfold-invoker btn btn-sm btn-white filter-show" href="javascript:">
                                <i class="tio-filter-list mr-1"></i>{{ translate('filters') }} <span
                                    class="badge badge-success badge-pill ml-1" id="filter_count"></span>
                            </a>
                        </div>
                    @endif
                    <!-- End Unfold -->
                </div>
            </div>
            <!-- End Header -->

            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                        <tr>
                            <th class="border-0">
                                {{ translate('messages.sl') }}
                            </th>
                            <th class="border-0">{{ translate('messages.order_id') }}</th>
                            <th class="border-0">{{ translate('messages.order_date') }}</th>
                            <th class="border-0">{{ translate('messages.customer') }}</th>
                            <th class="border-0">{{ translate('messages.restaurant') }}</th>
                            <th class="border-0">{{ translate('messages.total_amount') }}</th>
                            <th class="border-0">{{ translate('messages.order_status') }}</th>
                            <th class="border-0">{{ translate('messages.order_type') }}</th>
                            <th class="border-0 text-center">{{ translate('messages.actions') }}</th>
                        </tr>
                    </thead>

                    <tbody id="set-rows">
                    @foreach($orders as $key=>$order)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>
                                <a href="{{route('admin.order.details',['id'=>$order['id']])}}" class="text-dark">{{$order['id']}}</a>
                            </td>
                            <td>{{date('d M Y',strtotime($order['created_at']))}}</td>
                            <td>
                                @if($order->customer)
                                    <a class="text-body text-capitalize" href="{{route('admin.customer.view',[$order->customer['id']])}}">
                                        {{$order->customer['f_name'].' '.$order->customer['l_name']}}
                                    </a>
                                @else
                                    <label class="badge badge-danger">{{translate('messages.invalid_customer_data')}}</label>
                                @endif
                            </td>
                            <td>
                                @if($order->restaurant)
                                    <a class="text-body text-capitalize" href="{{route('admin.restaurant.view', $order->restaurant_id)}}">
                                        {{Str::limit($order->restaurant->name,25,'...')}}
                                    </a>
                                @else
                                    <label class="badge badge-danger">{{translate('messages.restaurant_deleted')}}</label>
                                @endif
                            </td>
                            <td>
                                <div class="text-right">
                                    <div>
                                        {{ \App\CentralLogics\Helpers::format_currency($order['order_amount']) }}
                                    </div>
                                    @if($order->payment_status=='paid')
                                        <strong class="text-success">{{translate('messages.paid')}}</strong>
                                    @else
                                        <strong class="text-danger">{{translate('messages.unpaid')}}</strong>
                                    @endif
                                </div>
                            </td>
                            <td class="text-capitalize">
                                @if($order['order_status']=='pending')
                                    <span class="badge badge-soft-info ml-2 ml-sm-3">
                                        <span class="legend-indicator bg-info"></span>{{translate('messages.pending')}}
                                    </span>
                                @elseif($order['order_status']=='confirmed')
                                    <span class="badge badge-soft-info ml-2 ml-sm-3">
                                        <span class="legend-indicator bg-info"></span>{{translate('messages.confirmed')}}
                                    </span>
                                @elseif($order['order_status']=='processing')
                                    <span class="badge badge-soft-warning ml-2 ml-sm-3">
                                        <span class="legend-indicator bg-warning"></span>{{translate('messages.processing')}}
                                    </span>
                                @elseif($order['order_status']=='picked_up')
                                    <span class="badge badge-soft-warning ml-2 ml-sm-3">
                                        <span class="legend-indicator bg-warning"></span>{{translate('messages.out_for_delivery')}}
                                    </span>
                                @elseif($order['order_status']=='delivered')
                                    <span class="badge badge-soft-success ml-2 ml-sm-3">
                                        <span class="legend-indicator bg-success"></span>{{translate('messages.delivered')}}
                                    </span>
                                @elseif($order['order_status']=='failed')
                                    <span class="badge badge-soft-danger ml-2 ml-sm-3">
                                        <span class="legend-indicator bg-danger"></span>{{translate('messages.payment_failed')}}
                                    </span>
                                @elseif($order['order_status']=='canceled')
                                    <span class="badge badge-soft-danger ml-2 ml-sm-3">
                                        <span class="legend-indicator bg-danger"></span>{{translate('messages.canceled')}}
                                    </span>
                                @elseif($order['order_status']=='refund_requested')
                                    <span class="badge badge-soft-warning ml-2 ml-sm-3">
                                        <span class="legend-indicator bg-warning"></span>{{translate('messages.refund_requested')}}
                                    </span>
                                @elseif($order['order_status']=='refunded')
                                    <span class="badge badge-soft-success ml-2 ml-sm-3">
                                        <span class="legend-indicator bg-success"></span>{{translate('messages.refunded')}}
                                    </span>
                                @elseif($order['order_status']=='scheduled')
                                    <span class="badge badge-soft-info ml-2 ml-sm-3">
                                        <span class="legend-indicator bg-info"></span>{{translate('messages.scheduled')}}
                                    </span>
                                @elseif($order['order_status']=='accepted')
                                    <span class="badge badge-soft-info ml-2 ml-sm-3">
                                        <span class="legend-indicator bg-info"></span>{{translate('messages.accepted')}}
                                    </span>
                                @elseif($order['order_status']=='handover')
                                    <span class="badge badge-soft-warning ml-2 ml-sm-3">
                                        <span class="legend-indicator bg-warning"></span>{{translate('messages.handover')}}
                                    </span>
                                @else
                                    <span class="badge badge-soft-info ml-2 ml-sm-3">
                                        <span class="legend-indicator bg-info"></span>{{str_replace('_',' ',$order['order_status'])}}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-soft-info">
                                    {{ translate('Evening Delivery') }}
                                </span>
                            </td>
                            <td>
                                <div class="btn--container justify-content-center">
                                    <a class="btn btn-sm btn--primary btn-outline-primary action-btn"
                                        href="{{route('admin.order.details',['id'=>$order['id']])}}" title="{{translate('messages.view')}}">
                                        <i class="tio-visible"></i>
                                    </a>
                                    <a class="btn btn-sm btn--warning btn-outline-warning action-btn"
                                        href="{{route('admin.order.view',['id'=>$order['id']])}}" title="{{translate('messages.edit')}}">
                                        <i class="tio-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- End Table -->

            <!-- Footer -->
            <div class="card-footer">
                <!-- Pagination -->
                <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
                    <div class="col-sm mb-2 mb-sm-0">
                        <div class="d-flex justify-content-center justify-content-sm-start align-items-center">
                            <span class="mr-2">{{translate('messages.showing')}}</span>
                            <span class="d-flex align-items-center">
                                <select name="entries" id="entries" class="form-control form-control-sm">
                                    <option value="25" {{ request()->get('entries') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request()->get('entries') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request()->get('entries') == 100 ? 'selected' : '' }}>100</option>
                                </select>
                            </span>
                            <span class="ml-2">{{translate('messages.of')}} {{$orders->total()}} {{translate('messages.entries')}}</span>
                        </div>
                    </div>
                    <div class="col-sm-auto">
                        <div class="d-flex justify-content-center justify-content-sm-end">
                            {!! $orders->links() !!}
                        </div>
                    </div>
                </div>
                <!-- End Pagination -->
            </div>
            <!-- End Footer -->
        </div>
        <!-- End Card -->
    </div>

@endsection

@push('script_2')
    <script>
        $(document).on('ready', function () {
            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });

            $('#entries').on('change', function() {
                var url = new URL(window.location);
                url.searchParams.set('entries', $(this).val());
                window.location = url.href;
            });

            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#datatable'), {
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        className: 'd-none'
                    },
                    {
                        extend: 'excel',
                        className: 'd-none'
                    },
                    {
                        extend: 'csv',
                        className: 'd-none'
                    },
                    {
                        extend: 'pdf',
                        className: 'd-none'
                    },
                    {
                        extend: 'print',
                        className: 'd-none'
                    },
                ],
                select: {
                    style: 'multi',
                    selector: 'td:first-child input[type="checkbox"]',
                    classMap: {
                        checkAll: '#datatableCheckAll',
                        counter: '#datatableCounter',
                        counterInfo: '#datatableCounterInfo'
                    }
                },
                language: {
                    zeroRecords: '<div class="text-center p-4">' +
                        '<img class="mb-3" src="{{asset('public/assets/admin')}}/svg/illustrations/sorry.svg" alt="Image Description" style="width: 7rem;">' +
                        '<p class="mb-0">No data to show</p>' +
                        '</div>'
                }
            });

            // INITIALIZATION OF TAGIFY
            // =======================================================
            $('.js-tagify').each(function () {
                var tagify = $.HSCore.components.HSTagify.init($(this));
            });
        });

        function filter_branch_orders(id) {
            location.href = '{{url('/')}}/admin/orders/branch-filter/' + id;
        }
    </script>

    <script>
        $('#search-form').on('submit', function () {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.order.search')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#set-rows').html(data.view);
                    $('.page-area').hide();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush
