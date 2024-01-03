@extends('admin.layout.app')
@section('page_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1>{{__('messages.sidebar_dashboard_text')}}</h1>
            <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                <ol class="breadcrumb pt-0">
                    <li class="breadcrumb-item active">
                        <a href="#">{{__('messages.home_text')}}</a>
                    </li>
                </ol>
            </nav>
            <div class="separator mb-5"></div>
        </div>
        <div class="col-lg-12 col-xl-12">
            <div class="icon-cards-row">
                <div class="glide dashboard-numbers">
                    <div class="glide__track" data-glide-el="track">
                        <ul class="glide__slides">
                            <li class="glide__slide">
                                <a href="{{route('admin.orders')}}/3" class="card">
                                    <div class="card-body text-center">
                                        <i class="iconsminds-clock"></i>
                                        <p class="card-text mb-0">{{__('messages.clientCount')}}</p>
                                        <p class="lead text-center">{{$clientCount}}</p>    
                                    </div>
                                </a>
                            </li>
                            <li class="glide__slide">
                                <a href="{{route('admin.orders')}}/7" class="card">
                                    <div class="card-body text-center">
                                        <i class="iconsminds-basket-coins"></i>
                                        <p class="card-text mb-0">{{__('messages.todayOrderCount')}}</p>
                                        <p class="lead text-center">{{$todayOrderCount}}</p>
                                    </div>
                                </a>
                            </li>
                            <li class="glide__slide">
                                <a href="{{route('admin.orders')}}" class="card">
                                    <div class="card-body text-center">
                                        <i class="glyph-icon iconsminds-dollar"></i>
                                        <p class="card-text mb-0">{{__('messages.todayRevenue')}}</p>
                                        <p class="lead text-center">{{number_format($todayRevenue,2)}}</p>
                                    </div>
                                </a>
                            </li>
                            <li class="glide__slide">
                                <a href="{{route('admin.orders')}}" class="card">
                                    <div class="card-body text-center">
                                        <i class="glyph-icon iconsminds-dollar"></i>
                                        <p class="card-text mb-0">{{__('messages.productCount')}}</p>
                                        <p class="lead text-center">{{$productCount}}</p>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
        </div>
        
        <?php /*
        <div class="col-lg-12 col-xl-6">
            <div class="icon-cards-row">
                <div class="glide dashboard-numbers">
                    <div class="glide__track" data-glide-el="track">
                        <ul class="glide__slides">
                            
                            <li class="glide__slide"> 
                                <a href="{{route('admin.orders')}}" class="card">
                                    <div class="card-body text-center">
                                        <i class="glyph-icon iconsminds-dollar"></i>
                                        <p class="card-text mb-0">Today's Released Amount</p>
                                        <p class="lead text-center">{{\App\Models\UserWalletHistory::where('order_id','=',0)->where('created_at','like',date('Y-m-d').'%')->sum('amount')}}</p>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
       
        */ ?>
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
                            @foreach($todayOrder as $key => $single)
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

