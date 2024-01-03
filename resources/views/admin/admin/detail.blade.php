@extends('admin.layout.app')
@section('page_content')
<div class="container-fluid">
            <div class="row ">
                <div class="col-12 survey-app">
                    <div class="mb-2">
                        <h1>{{__('messages.customer_detail_text')}}</h1>
                        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                        <ol class="breadcrumb pt-0">
                            <li class="breadcrumb-item">
                                <a href="{{route('admin.dashboard')}}">{{__('messages.home_text')}}</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{route('admin.customer')}}">{{__('messages.sidebar_users_customer_text')}}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="#">{{__('messages.customer_detail_text')}}</a>
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
                                            <p class="list-item-heading mb-4">{{__('messages.summary_text')}}</p>
                                            <p class="text-muted text-small mb-2">{{__('messages.name_text')}}</p>
                                            <p class="mb-3">
                                                {{$user->name}}
                                            </p>
                                            <p class="text-muted text-small mb-2">{{__('messages.email_text')}}</p>
                                            <p class="mb-3">
                                                {{$user->email}}
                                            </p>

                                            <p class="text-muted text-small mb-2">{{__('messages.phone_number_text')}}</p>
                                            <p class="mb-3">
                                                {{$user->phone_number}}
                                            </p>

                                            <p class="text-muted text-small mb-2">{{__('messages.image_text')}}</p>
                                            <p class="mb-3">
                                                @if($user->image)
			                                        <img src="{{url('/')}}/user/{{$user->image}}" width="100" height="100" style="margin-top: 10px" id="image">
			                                            @else
			                                        <img src="{{url('/')}}/user/default.jpg" width="100" height="100" style="margin-top: 10px" id="image">

                                            @endif
                                            </p>

                                            <p class="text-muted text-small mb-2">{{__('messages.about_text')}}</p>
                                            <p class="mb-3">
                                                {{$user->about}}
                                            </p>

                                            <p class="text-muted text-small mb-2">{{__('messages.address_text')}}</p>
                                            <p class="mb-3">
                                                {{$user->address}}
                                            </p>
<!--
                                            <p class="text-muted text-small mb-2">{{__('messages.lat_text')}}</p>
                                            <p class="mb-3">
                                                {{$user->latitude}}
                                            </p>

                                            <p class="text-muted text-small mb-2">{{__('messages.long_text')}}</p>
                                            <p class="mb-3">
                                                {{$user->longitude}}
                                            </p> -->

                                            <p class="text-muted text-small mb-2">{{__('messages.status_text')}}</p>
                                            <p class="mb-3">
                                                @if($user->is_active==1)
                                                Active
                                                @else
                                                Deactive
                                                @endif
                                            </p>
                                            
                                            <p class="text-muted text-small mb-2">{{__('messages.wallet_balance_lbl')}}</p>
                                            <p class="mb-3">
                                                {{$user->wallet_total}}
                                            </p>
                                            
                                            <p class="text-muted text-small mb-2">{{__('messages.created_at_text')}}</p>
                                            <p class="mb-3">
                                                {{$user->created_at}}
                                            </p>


                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8 col-12 mb-8">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <p class="list-item-heading mb-4">{{__('messages.orders_text')}}</p>

                                            <table class="data-table" id="data-table">
				                                <thead>
				                                    <tr>
				                                        <th>{{__('messages.order_number_text')}}</th>
				                                        <th>{{__('messages.customer_name_text')}}</th>
				                                        <th>{{__('messages.mechanic_name_text')}}</th>
				                                        <th>{{__('messages.address_text')}}</th>
				                                        <th>{{__('messages.total_text')}}</th>
				                                        <th>{{__('messages.status_text')}}</th>
				                                        <th>{{__('messages.created_at_text')}}</th>
				                                    </tr>
				                                </thead>
				                                <tbody>
				                                	@foreach($user->orders as $key=>$val)
				                                    <tr>
				                                        <td>{{$val->order_code}}</td>
				                                        <td>{{$val->customer->name}}</td>
				                                        <td>{{$val->hairdresser->name}}</td>
				                                        <td>{{$val->address}}</td>
				                                        <td>{{$val->final_total}}</td>
				                                        <td>
                                                           @if($val->order_status==ORDER_REQUEST)
                                                            	{{__('messages.order_request')}}
                                                            @elseif($val->order_status==ORDER_TIMEOUT)
                                                            	{{__('messages.order_timeout')}}
                                                            @elseif($val->order_status==ORDER_CANCEL)
                                                            	{{__('messages.order_cancel')}}
                                                            @elseif($val->order_status==ORDER_ACCEPT)
                                                            	{{__('messages.order_accept')}}
                                                            @elseif($val->order_status==ORDER_ON_THE_WAY)
                                                            	{{__('messages.order_on_the_way')}}
                                                            @elseif($val->order_status==ORDER_PROCESSING)
                                                            	{{__('messages.order_processing')}}
                                                            @elseif($val->order_status==ORDER_COMPLETE)
                                                            	{{__('messages.order_complete')}}
                                                            @elseif($val->order_status==ORDER_REJECT)
                                                            	{{__('messages.order_rejected')}}
                                                            @elseif($val->order_status==ORDER_PAID)
                                                            	{{__('messages.order_paid')}}
                                                            @elseif($val->order_status==PAYMENT_TIMEOUT)
                                                            	{{__('messages.payment_timeout')}}
                                                            @endif
                                                        </td>
				                                        <td>{{$val->created_at}}</td>
				                                    </tr>
				                                    @endforeach

				                                </tbody>
				                            </table>

                                            <p class="list-item-heading mb-4">{{__('messages.wallet_title')}}</p>
                                                <div class="top-right-button-container mb-2">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-outline-primary mb-2" onclick="addMoneyPopup()" >{{__('messages.add_money_wallet_records')}}</button>
                                                    </div>
                                                </div>
                                            <table class="data-table" id="data-table-wallet-history">
                                                <thead>
                                                    <tr>
                                                        <th>{{__('messages.wallet_type_text')}}</th>
                                                        <th>{{__('messages.wallet_customer_paid_text')}}</th>
                                                        <th>{{__('messages.wallet_amount_text')}}</th>
                                                        <th>{{__('messages.wallet_order_text')}}</th>
                                                        <th>{{__('messages.wallet_description_text')}}</th>
                                                        <th>{{__('messages.created_at_text')}}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($UserWalletHistory as $key=>$val)
                                                    <tr>
                                                        <td>{{$val->type}}</td>
                                                        <td>{{$val->user_paid_amount}}</td>
                                                        <td>{{$val->amount}}</td>
                                                        <?php if($val->order_detail):?>
                                                            <td>{{$val->order_detail->order_code}}</td>
                                                        <?php else: ?>
                                                            <td> - </td>
                                                        <?php endif; ?>
                                                        <?php if(Lang::locale() == 'en'):?>
                                                            <td>{{$val->description}}</td>
                                                        <?php else: ?>
                                                            <td>{{$val->arabic_description}}</td>
                                                        <?php endif; ?>
                                                        <td>{{$val->created_at}}</td>
                                                    </tr>
                                                    @endforeach

                                                </tbody>
                                                <tfoot style="background-color: #00365a;color: white;font-weight: bold;">
                                                    <tr>
                                                        <td></td>
                                                        <td class="text-center">{{__('messages.total_text')}}</td>
                                                        <td class="text-center">{{$user->wallet_total}}</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </tfoot>
                                            </table>



                                        </div>
                                    </div>
                                </div>
                            </div>


                    </div>

                </div>
            </div>
        </div>
        
        <div class="modal fade" id="exampleModalContent1" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="/admin/add-wallet-amount" method="post" role="form">
                        @csrf
                        <input type="hidden" id='user_id' name="user_id" value='<?=$user->id?>'>
                        
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalTitleLabel1">{{__('messages.wallet_title')}} : </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">{{__('messages.wallet_amount_text')}} : </label>
                                <input type="number" class="form-control" id="coupen_code" value='' name="amount" min="0" required>
                            </div>
                            <div class="form-group">
                                <label for="message-text" class="col-form-label">{{__('messages.wallet_description_text')}} : </label>
                                <input type="text" class="form-control" id="amount" value='' name="reason" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-primary" data-dismiss="modal">{{__('messages.close_text')}}</button>
                            <button type="submit" class="btn btn-primary">{{__('messages.save_text')}}</button>
                        </div>
                    </form>
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

    $("#data-table-wallet-history").DataTable({
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

    function addMoneyPopup(){
        $('#exampleModalContent1').modal('show'); 
        $('#exampleModalTitleLabel1').html("<?=__('messages.wallet_title')?>"); 
    }
</script>
@endsection
