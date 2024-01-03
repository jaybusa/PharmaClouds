@extends('admin.layout.app')
@section('page_content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1>{{ __('messages.orders_text') }}</h1>
                <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                    <ol class="breadcrumb pt-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">{{ __('messages.home_text') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <a href="#">{{ __('messages.orders_text') }}</a>
                        </li>
                    </ol>
                </nav>
                <div class="separator mb-5"></div>
            </div>
        </div>
        <div class="row orderreport-search-orderid" style="display:flex;">
            <div>
                <form action="#" method="get">
                    <div class="x-container">
                        <div class="text-label">
                            {{ __('messages.search_by_order_id') }}
                        </div>
                        <div>
                            <input type="text" name="orderid" class="form-control" value="{{ $search_order_id }}" />
                        </div>
                        <div>
                            <input type="submit" name="search" value="{{ __('messages.search') }}"
                                class="btn btn-primary" />
                        </div>
                    </div>
                </form>
            </div>
            <div>
                <form action="#" method="post">
                    <div class="x-container">
                        <div class="text-label">
                            {{ __('messages.select_date') }}
                        </div>
                        <div>
                            <input type="hidden" name="startDate" id="startDate" value="{{ $startDate }}">
                            <input type="hidden" name="endDate" id="endDate" value="{{ $endDate }}">
                            <input type="text" name="daterange" id="daterange" class="form-control"
                                value="{{ $startDatestring }} - {{ $endDatestring }}" />
                        </div>
                        <div>
                            <input type="submit" name="search" value="{{ __('messages.search') }}"
                                class="btn btn-primary" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <form action="#" method="post" class="form-horizontal" id="role_form">
                <div class="form-body">
                    <!-- tabs -->
                    <div class="row">
                        <div class="tabbable tabbable-custom tabbable-noborder ">
                            @if ($search_order_id == '')
                                <ul class="nav nav-tabs">
                                    <li class=" @if ($type == 'demand') active @endif">
                                        <a href="/orderreport?type=demand">
                                            <i class="icon-notebook"></i>
                                            {{ __('messages.demand') }}
                                        </a>
                                    </li>
                                    <li class=" @if ($type == 'shipped') active @endif">
                                        <a href="/orderreport?type=shipped">
                                            <i class="icon-crop"></i>
                                            {{ __('messages.shipped') }}
                                        </a>
                                    </li>

                                    <li class=" @if ($type == 'delivered') active @endif">
                                        <a href="/orderreport?type=delivered">
                                            <i class="icon-crop"></i>
                                            {{ __('messages.delivered') }}
                                        </a>
                                    </li>

                                    <li class=" @if ($type == 'cancelled') active @endif">
                                        <a href="/orderreport?type=cancelled">
                                            <i class="icon-crop"></i>
                                            {{ __('messages.cancelled') }}
                                        </a>
                                    </li>

                                    <li class=" @if ($type == 'unpaid') active @endif">
                                        <a href="/orderreport?type=unpaid">
                                            <i class="icon-crop"></i>
                                            {{ __('messages.unpaid') }}
                                        </a>
                                    </li>
                                    <li class=" @if ($type == 'last') active @endif">
                                        <a href="/orderreport?type=last">
                                            <i class="icon-crop"></i>
                                            {{ __('messages.last') }}
                                        </a>
                                    </li>
                                    <li class=" @if ($type == 'archive') active @endif">
                                        <a href="/orderreport?type=archive">
                                            <i class="icon-crop"></i>
                                            {{ __('messages.archive') }}
                                        </a>
                                    </li>

                                    <li class=" @if ($type == 'returned') active @endif">
                                        <a href="/orderreport?type=returned">
                                            <i class="icon-crop"></i>
                                            {{ __('messages.returned') }}
                                        </a>
                                    </li>

                                    <li class=" @if ($type == 'cod') active @endif">
                                        <a href="/orderreport?type=cod">
                                            <i class="icon-crop"></i>
                                            {{ __('messages.cod') }}
                                        </a>
                                    </li>
                                    <li class=" @if ($type == 'vat') active @endif">
                                        <a href="/orderreport?type=vat">
                                            <i class="icon-crop"></i>
                                            {{ __('messages.vatOrders') }}
                                        </a>
                                    </li>
                                </ul>
                            @else
                                <a href="/orderreport" class="btn btn-default btn-sm"
                                    style="margin-bottom:15px;">{{ __('messages.clear_search') }} </a>
                            @endif

                            <div class="tab-content mysettingrole">


                                <div class="tab-pane @if ($type == 'demand') active @endif" id="tab_setting_1">


                                    <div class="portlet-body form">

                                        <table class="table table-hover" id="custom_tbl_dt-bkp">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>
                                                        {{ __('messages.orderreportClientOrderId') }}
                                                    </th>
                                                    <th>
                                                        {{ __('messages.orderreportClientArabicName') }}
                                                    </th>
                                                    <th>
                                                        {{ __('messages.clientMobileTitle') }}
                                                    </th>
                                                    <th>
                                                        {{ __('messages.orderreportDate') }}
                                                    </th>
                                                    <th>
                                                        {{ __('messages.coupon') }}
                                                    </th>
                                                    <th>
                                                        {{ __('messages.status') }}
                                                    </th>
                                                    <th width="5%">{{ __('messages.buttonStatusTitle') }} </th>
                                                    <th width="5%"></th>
                                                    <th width="5%"></th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($orderreportdata as $single)

                                                    <tr class="odd gradeX">
                                                        <td>
                                                            <input type="checkbox" name="delete_order[]"
                                                                value="{$single.id}" />
                                                        </td>
                                                        <td>
                                                            {{ $single->id }}
                                                        </td>
                                                        <td>
                                                            @if ($single->client_id >= '1')
                                                                @if ($single->name == '' || $single->name == ' ')
                                                                    {{ $single->payer_name }}
                                                                @else
                                                                    {{ $single->name }}
                                                                @endif
                                                            @else
                                                                {{ $single->payer_name }} -
                                                                {{ __('messages.GUEST_USER') }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($single->client_id >= '1')
                                                                @if ($single->phone == '' && $single->mobile == ' ')
                                                                    {{ $single->phone }} - {{ $single->mobile }}
                                                                @else
                                                                    {{ $single->payer_mobile }}
                                                                @endif
                                                            @else
                                                                {{ $single->payer_mobile }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{ $single->createdate }}
                                                        </td>
                                                        <td>
                                                            {{ $single->coupon_name }}
                                                        </td>
                                                        <td>
                                                            @if ($single->order_payment_status == 1)
                                                                {{ __('messages.status_paid') }}
                                                            @else
                                                                {{ __('messages.status_unpaid') }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($single->sumtype == 1)
                                                                <a href="/orders/status/{$single->id}/2"
                                                                    class="btn green-haze btn-xs">
                                                                    <i class="fa fa-eye"></i>
                                                                    {{ __('messages.shipping') }}
                                                                </a>
                                                            @elseif ($single->sumtype == 3)
                                                                <a href="/orders/status/{$single->id}/3"
                                                                    class="btn green-haze btn-xs">
                                                                    <i class="fa fa-eye"></i>
                                                                    {{ __('messages.delivering') }}
                                                                </a>
                                                            @else
                                                                <a href="/orders/status/{$single->id}/4"
                                                                    class="btn green-haze btn-xs">
                                                                    <i class="fa fa-eye-slash"></i>
                                                                    {{ __('messages.delivered') }}

                                                                </a>
                                                            @endif
                                                        </td>

                                                        <td>
                                                            <a href="/{$controllname}/edit/{$single->id}"
                                                                class="btn blue-hoki btn-xs"><i class="fa fa-pencil"></i>
                                                                {{ __('messages.editOrder') }}
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a class="btn grey-cascade btn-xs deletemsg"
                                                                data-id="{$single->id}" data-link="{$controllname}"><i
                                                                    class="fa fa-times"></i>
                                                                {{ __('messages.deleteOrder') }}
                                                            </a>
                                                        </td>

                                                    </tr>
                                                    {/foreach}
                                            </tbody>
                                        </table>


                                    </div>


                                </div>

                            </div>
                        </div>
                        <div class="pagination">
                            <ul class="pagination">
                                @if ($page_data['total_pages'] <= 6)
                                    @for ($i = 1; $i <= $page_data['total_pages']; $i++)
                                        <li class="@if ($page_data['current_page'] == $i) 'active' @endif">
                                            <a href="/orders?type={{ $page_data['type'] }}&page={{ $i }}">
                                                {{ $i }}
                                            </a>
                                        </li>
                                    @endfor
                                @else
                                    <li>
                                        <a href="/orders?type={{ $page_data['type'] }}&page=1">
                                            First
                                        </a>
                                    </li>
                                    @if ($page_data['current_page'] == 1)
                                        <li class="active">
                                            <a href="/orders?type={{ $page_data['type'] }}&page=1">
                                                1
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="/orders?type={{ $page_data['type'] }}&page=2">
                                                2
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="/orders?type={{ $page_data['type'] }}&page=3">
                                                3
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="/orders?type={{ $page_data['type'] }}&page=4">
                                                4
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="/orders?type={{ $page_data['type'] }}&page=5">
                                                5
                                            </a>
                                        </li>
                                        <li><a href="Javascript:void(0);">...</a></li>
                                    @elseif ($page_data['current_page'] == $page_data['total_pages'])
                                        <li><a href="Javascript:void(0);">...</a></li>
                                        <li>
                                            <a
                                                href="/orders?type={{ $page_data['type'] }}&page={{ $page_data['total_pages'] - 4 }}">
                                                {{ $page_data['total_pages'] - 4 }}
                                            </a>
                                        </li>
                                        <li>
                                            <a
                                                href="/orders?type={{ $page_data['type'] }}&page={{ $page_data['total_pages'] - 3 }}">
                                                {{ $page_data['total_pages'] - 3 }}
                                            </a>
                                        </li>
                                        <li>
                                            <a
                                                href="/orders?type={{ $page_data['type'] }}&page={{ $page_data['total_pages'] - 2 }}">
                                                {{ $page_data['total_pages'] - 2 }}
                                            </a>
                                        </li>
                                        <li class="">
                                            <a
                                                href="/orders?type={{ $page_data['type'] }}&page={{ $page_data['total_pages'] - 1 }}">
                                                {{ $page_data['total_pages'] - 1 }}
                                            </a>
                                        </li>
                                        <li class="active">
                                            <a
                                                href="/orders?type={{ $page_data['type'] }}&page={{ $page_data['total_pages'] }}">
                                                {{ $page_data['total_pages'] }}
                                            </a>
                                        </li>
                                    @else
                                        <li><a href="Javascript:void(0);">...</a></li>
                                        <li>
                                            <a
                                                href="/orders?type={{ $page_data['type'] }}&page={{ $page_data['current_page'] - 2 }}">
                                                {$page_data.current_page - 2}
                                            </a>
                                        </li>
                                        <li>
                                            <a
                                                href="/orders?type={{ $page_data['type'] }}&page={{ $page_data['current_page'] - 1 }}">
                                                {$page_data.current_page - 1}
                                            </a>
                                        </li>
                                        <li class="active">
                                            <a
                                                href="/orders?type={{ $page_data['type'] }}&page={{ $page_data['current_page'] }}">
                                                {$page_data.current_page}
                                            </a>
                                        </li>
                                        <li class="">
                                            <a
                                                href="/orders?type={{ $page_data['type'] }}&page={{ $page_data['current_page'] + 1 }}">
                                                {$page_data.current_page + 1}
                                            </a>
                                        </li>
                                        <li class="">
                                            <a
                                                href="/orders?type={{ $page_data['type'] }}&page={{ $page_data['current_page'] + 2 }}">
                                                {$page_data.current_page + 2}
                                            </a>
                                        </li>
                                        <li><a href="Javascript:void(0);">...</a></li>
                                    @endif
                                    <li>
                                        <a
                                            href="/orders?type={{ $page_data['type'] }}&page={{ $page_data['total_pages'] + 1 }}">
                                            Last
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>


                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="action" class="form-control">
                                    <option value="">-- Select --</option>
                                    <option value="shipping">{{ __('messages.shipping') }}</option>
                                    <option value="delivered">{{ __('messages.orderReportDelivered') }}</option>
                                    <option value="archive">{{ __('messages.Archive') }}</option>
                                    <option value="delete">{{ __('messages.deleteButtonTitle') }}</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn green"> {{ __('messages.editButtonTitle') }} </button>
                                <a href="#" class="btn default"> {{ __('messages.cancelButtonTitle') }} </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!-- END FORM-->


        </div>
    </div>
@endsection
