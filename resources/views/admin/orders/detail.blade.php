@extends('admin.layout.app')
@section('page_content')
<div class="container-fluid">
            <div class="row ">
                <div class="col-12 survey-app">
                    <div class="mb-2">
                        <h1>{{__('messages.order_detail_text')}}</h1>
                        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                        <ol class="breadcrumb pt-0">
                            <li class="breadcrumb-item">
                                <a href="{{route('admin.dashboard')}}">{{__('messages.home_text')}}</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{route('admin.orders')}}">{{__('messages.sidebar_orders_text')}}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="#">{{__('messages.order_detail_text')}}</a>
                            </li>
                        </ol>
                    </nav>
                    <div class="separator mb-5"></div>
                    </div>
                    
                    <div class="tab-content mb-4">
                            <div class="row">

                                <div class="col-lg-4 col-12 mb-4">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <p class="list-item-heading mb-4">{{__('messages.order_detail_text')}}</p>
                                            <p class="text-muted text-small mb-2">{{__('messages.order_number_text')}}</p>
                                            <p class="mb-3">
                                                {{$order->order_code}}
                                            </p>
                                            <p class="text-muted text-small mb-2">{{__('messages.customer_name_text')}}</p>
                                            <p class="mb-3">
                                                @if($order->customer)
                                                {{$order->customer->name}}
                                                @endif
                                            </p>

                                            <p class="text-muted text-small mb-2">{{__('messages.mechanic_name_text')}}</p>
                                            <p class="mb-3">
                                                @if($order->hairdresser)
                                                {{$order->hairdresser->name}}
                                                @endif
                                            </p>

                                            <p class="text-muted text-small mb-2">{{__('messages.address_text')}}</p>
                                            <p class="mb-3">
                                                {{$order->address}}
                                            </p>

                                            <p class="text-muted text-small mb-2">{{__('messages.total_text')}}</p>
                                            <p class="mb-3">
                                                {{$order->final_total}}
                                            </p>

                                            <p class="text-muted text-small mb-2">{{__('messages.promocode_text')}}</p>
                                            <p class="mb-3">
                                                @if($order->promocode_detail)
                                                {{$order->promocode_detail->code}}
                                                @else
                                                -
                                                @endif
                                            </p>

                                            <p class="text-muted text-small mb-2">{{__('messages.promocode_amount_text')}}</p>
                                            <p class="mb-3">
                                                @if($order->promocode_detail)
                                                {{$order->promo_code_amount}}
                                                @else
                                                -
                                                @endif
                                            </p>

                                            <p class="text-muted text-small mb-2">{{__('messages.hairdressor_amount')}}</p>
                                            <p class="mb-3">
                                                {{$order->hairdressor_amount}}
                                            </p>

                                            <p class="text-muted text-small mb-2">{{__('messages.admin_amount')}}</p>
                                            <p class="mb-3">
                                                {{$order->admin_amount}}
                                            </p>

                                            <p class="text-muted text-small mb-2">{{__('messages.status_text')}}</p>
                                            <p class="mb-3">
                                                @if($order->order_status==ORDER_REQUEST)
                                                Order Request
                                                @elseif($order->order_status==ORDER_TIMEOUT)
                                                Order Timeout
                                                @elseif($order->order_status==ORDER_CANCEL)
                                                Order Cancel
                                                @elseif($order->order_status==ORDER_ACCEPT)
                                                Order Accept
                                                @elseif($order->order_status==ORDER_ON_THE_WAY)
                                                Order On The Way
                                                @elseif($order->order_status==ORDER_PROCESSING)
                                                Order Processing
                                                @elseif($order->order_status==ORDER_COMPLETE)
                                                Order Complete
                                                @endif
                                            </p>


                                            <p class="text-muted text-small mb-2">{{__('messages.created_at_text')}}</p>
                                            <p class="mb-3">
                                                {{$order->created_at}}
                                            </p>

                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8 col-12 mb-8">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <p class="list-item-heading mb-4">{{__('messages.orders_service_text')}}</p>
                                            <table class="data-table" id="data-table">
                                                <thead>
                                                    <tr>
                                                        <th>{{__('messages.order_service_text')}}</th>
                                                        <th>{{__('messages.order_price_text')}}</th>
                                                        <th>{{__('messages.order_quntity_text')}}</th>
                                                        <th>{{__('messages.order_total_text')}}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $final_total = 0; @endphp
                                                    @foreach($order->order_service as $key=>$val)
                                                    <tr>
                                                        <td>{{$val->category->name}}</td>
                                                        <td>{{$val->price}}</td>
                                                        <td>{{$val->quantity}}</td>
                                                        <td>{{$val->total}}</td>
                                                        @php $final_total+=$val->total; @endphp
                                                    </tr>
                                                    @endforeach
                                                    @if($final_total>0)
                                                    <tr>
                                                        <td colspan="3">{{__('messages.order_final_total_text')}}</td>
                                                        <td>{{$final_total}}</td>
                                                    </tr>
                                                    @endif

                                                </tbody>
                                            </table>
                                            @if($order->transaction_detail)
                                            <p class="list-item-heading mb-4">{{__('messages.order_transaction_text')}}</p>
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>{{__('messages.order_transaction_id_text')}}</th>
                                                        <th>{{__('messages.order_total_text')}}</th>
                                                        <th>{{__('messages.order_due_amount_text')}}</th>
                                                        <th>{{__('messages.order_final_amount_text')}}</th>
                                                        <th>{{__('messages.order_payment_method_text')}}</th>
                                                        <th>{{__('messages.created_at_text')}}</th>              
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>{{$order->transaction_detail->transaction_id}}</td>
                                                        <td>{{$order->transaction_detail->total}}</td>
                                                        <td>{{$order->transaction_detail->due_amount}}</td>
                                                        <td>{{$order->transaction_detail->final_amount}}</td>
                                                        <td>@if($order->transaction_detail->payment_method==1)
                                                                {{__('messages.cash_pay')}}
                                                            @elseif($order->transaction_detail->payment_method==2)
                                                                {{__('messages.card_pay')}}
                                                            @else
                                                                No Payment
                                                            @endif
                                                            </td>
                                                        <td>{{$order->transaction_detail->created_at}}</td>
                                                    </tr>                                                    
                                                </tbody>
                                            </table>
                                            @endif
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        

                    </div>

                </div>
            </div>
        </div>
@endsection
@section('script_js')
<script type="text/javascript">
    $("#data-table").DataTable({
        sDom: '<"row view-filter"<"col-sm-12"<"float-right"l><"float-left"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',
        
        drawCallback: function () {
          $($(".dataTables_wrapper .pagination li:first-of-type"))
            .find("a")
            .addClass("prev");
          $($(".dataTables_wrapper .pagination li:last-of-type"))
            .find("a")
            .addClass("next");

          $(".dataTables_wrapper .pagination").addClass("pagination-sm");
        },
        language: {
          paginate: {
            previous: "<i class='simple-icon-arrow-left'></i>",
            next: "<i class='simple-icon-arrow-right'></i>"
          },
          search: "_INPUT_",
          searchPlaceholder: messages.data_table_search_placeholder_text,
          lengthMenu: messages.data_table_item_per_page_text+" _MENU_ ",
          info:messages.data_table_pagination_showing_text+" _START_ "+messages.data_table_pagination_to_text+" _END_ "+messages.data_table_pagination_of_text+" _TOTAL_ "+messages.data_table_pagination_entries_text,

        },
    });

    

</script>
@endsection