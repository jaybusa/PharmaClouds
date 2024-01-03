@extends('admin.layout.app')
@section('page_content')
<div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h1>{{__('messages.sidebar_report_text')}}</h1>
                    <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                        <ol class="breadcrumb pt-0">
                            <li class="breadcrumb-item">
                                <a href="{{route('admin.dashboard')}}">{{__('messages.home_text')}}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="#">{{__('messages.sidebar_report_text')}}</a>
                            </li>
                        </ol>
                    </nav>
                    <!--<span><a href="Javascript:void(0)" data-toggle="modal"-->
                    <!--            data-target="#exampleModalContent1" data-whatever="" class="btn btn-primary mb-0 float-right add_cls">{{__('messages.add_banner_text')}}</a></span>-->
                    <div class="separator mb-5"></div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                           
                            <form method="POST" id="getReport" action="{{route('admin.report')}}">
                                @csrf
                                <div class="row mb-5">
                                    <div class="col-sm-4">

                                        <label><?=trans('messages.selectusersLbl')?></label>
                                        <select class="form-control select2-single" name="users" data-width="100%">
                                            <option value="-1">{{__('messages.all')}}</option>
                                            <?php foreach ($Dressers as $_user): ?>
                                                <option <?php echo $userId == $_user->id ?'selected':''?> value="<?=$_user->id?>"><?=$_user->name?> (<?=$_user->phone_number?>)</option>
                                            <?php endforeach;?>
                                        </select>

                                    </div>

                                    <div class="col-sm-3">

                                        <label><?=trans('messages.from_date')?></label>
                                        <input type="date" class="form-control" id="from_date" name="from_date" value="<?=$fromDate?>" max=<?=date('Y-m-d')?>>

                                    </div>

                                    <div class="col-sm-3">

                                        <label><?=trans('messages.to_date')?></label>
                                        <input type="date" class="form-control" id="to_date" name="to_date"  value="<?=$toDate?>" max=<?=date('Y-m-d')?>>

                                    </div>

                                    <div class="col-sm-2">
                                        <button type="submit" class="btn btn-primary float-right mt-4"><?=trans('messages.search')?></button>
                                    </div>

                                </div>
                            </form>
                            
                        </div>
                    </div>
                </div>
                @if(!empty($UserWalletHistory))
                 <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                           
                            <table class="data-table" id="data-table">
                                <thead>
                                    <tr>
                                        <th>{{__('messages.wallet_type_text')}}</th>
                                        <th>{{__('messages.mechanic_name_text')}}</th>
                                        <th>{{__('messages.wallet_amount_text')}}</th>
                                        <th>{{__('messages.wallet_order_text')}}</th>
                                        <th>{{__('messages.wallet_description_text')}}</th>
                                        <th>{{__('messages.created_at_text')}}</th>
                                    </tr>
                                </thead>
                                <?php $total = 0; ?>
                                <tbody>
                                @if(!empty($UserWalletHistory))
                                    @foreach($UserWalletHistory as $key=>$val)                                                    
                                        <tr>
                                            <?php $total += $val->amount; ?> 
                                            <td>{{$val->type}}</td>
                                            <td>{{$val->users->name}}</td>
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
                                @endif
                                </tbody>
                                @if(count($UserWalletHistory))
                                <tfoot style="background-color: #00365a;color: white;font-weight: bold;">
                                    <tr>
                                        <td></td>
                                        <td class="text-center">{{__('messages.total_text')}}</td>
                                        <td class="text-center">{{$total}}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                                 @endif
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
       
@endsection
@section('script_js')
<script type="text/javascript">
    $("#data-table").DataTable({
        sDom: '<"row view-filter"<"col-sm-12"<"float-right"l><"float-left"f><"text-center"B><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',
        
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