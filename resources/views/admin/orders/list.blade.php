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
                            <input type="submit" name="search" value="{{ __('messages.search') }}" class="btn btn-primary" />
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
        <div class="row">
        <div class="col-xl-12 col-lg-12 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">{{__('messages.todays_orders_text')}}</h5>
                    <table class="data-table data-table-standard responsive nowrap"
                        data-order="[[ 1, &quot;desc&quot; ]]">
                        <thead>
                            <tr>
                                <th>{{__('messages.orderreportClientOrderId')}}</th>
                                <th>{{__('messages.orderreportClientArabicName')}}</th>
                                <th>{{__('messages.orderreportDate')}}</th>
                                <th>{{__('messages.coupon')}}</th>
                                <th>{{__('messages.status')}}</th>
                                <th width="5%">{{__('messages.buttonStatusTitle')}}</th>
                                <th width="5%">{{__('messages.buttonStatusTitle')}}</th>
                                <th width="5%">{{__('messages.buttonStatusTitle')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orderreportdata as $key => $single)
                                <tr>
                                    <td class="@if($single->id % 2 == 0) even @endif">
                                            {{$single->id}}
                                        </td>
                                        <td>
                                            @if ($single->client_id >= '1')
                                                @if ($single->client->name == '' || $single->client->name == ' ')
                                                    {{$single->payer_name}}
                                                @else
                                                    {{$single->client->name}}
                                                @endif
                                            @else
                                                {{$single->payer_name}} - {{__('messages.GUEST_USER')}}
                                            @endif
                                        </td>
                                        <td>
                                            {{$single->createdate}}
                                        </td>
                                        <td>
                                            {{ $single->coupon ? $single->coupon->name : '' }}
                                        </td>
                                        <td>{{ $single->order_payment_status == 1 ? 'تم الدفع' : 'لم يتم الدفع' }}</td>
                                        <td>
                                            <a href="/orderreport/status/{{$single->id}}/2"
                                            class="btn green-haze btn-xs">
                                                @if($single->sumtype == 1)
                                                    <i class="fa fa-eye"></i>
                                                    {{__('messages.orderReportToShipping')}}
                                                @elseif ($single->sumtype == 3)
                                                    <i class="fa fa-eye"></i>
                                                    {{__('messages.orderReportToDelivered')}}
                                                @else
                                                    <i class="fa fa-eye-slash"></i>
                                                    {{__('messages.orderReportDelivered')}}
                                                @endif
                                            </a>
                                        </td>
            
                                        <td>
                                            <a href="/orderreport/edit/{{$single->id}}" class="btn blue-hoki btn-xs">
                                                <i class="fa fa-pencil"></i>
                                                {{__('messages.roleShowTitle')}}
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn grey-cascade btn-xs deletemsg" data-id="{{$single->id}}" data-link="orderreport">
                                                <i class="fa fa-times"></i>
                                                {{__('messages.deleteButtonTitle')}}
                                            </a>
                                        </td>
            
                                    </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>  
    </div>
@endsection
