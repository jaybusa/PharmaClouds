@extends('admin.layout.app')
@section('page_content')
<div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h1>{{__('messages.promocode_list_text')}}</h1>
                    <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                        <ol class="breadcrumb pt-0">
                            <li class="breadcrumb-item">
                                <a href="{{route('admin.dashboard')}}">{{__('messages.home_text')}}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="#">{{__('messages.promocode_list_text')}}</a>
                            </li>
                        </ol>
                    </nav>
                    <span><a href="Javascript:void(0)" data-toggle="modal"
                                data-target="#exampleModalContent1" data-whatever="" class="btn btn-primary mb-0 float-right add_cls">{{__('messages.add_promocode_text')}}</a></span>
                    <div class="separator mb-5"></div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>    
                        <strong>{{ $message }}</strong>
                    </div>
                    @endif
                    @if ($message = Session::get('danger'))
                    <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>    
                        <strong>{{ $message }}</strong>
                    </div>
                    @endif
                            <table class="data-table" id="data-table">
                                <thead>
                                    <tr>
                                        <th>{{__('messages.promode_name_text')}}</th>
                                        <th>{{__('messages.promocode_code_text')}}</th>
                                        <th>{{__('messages.promocode_percentage_text')}}</th>

                                        <th>{{__('messages.promocode_min_total_text')}}</th>
                                        <th>{{__('messages.promocode_total_user_text')}}</th>
                                        <th>{{__('messages.promocode_expired_date_text')}}</th>
                                        <th>{{__('messages.created_at_text')}}</th>
                                        <th>{{__('messages.action_text')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($promocode as $key=>$val)
                                    <tr>
                                        <td>{{$val->name}}</td>
                                        <td>{{$val->code}}</td>
                                        <td>{{$val->percentage}}</td>
                                        <td>{{$val->min_total}}</td>
                                        <td>@if($val->total_user_limit){{$val->total_user_limit}}@else - @endif</td>
                                        <td>@if($val->expired_date){{date('d-m-Y',strtotime($val->expired_date))}}@else - @endif</td>
                                        <td>{{$val->created_at}}</td>
                                        <td>
                                            <a href="javascript:void(0)" data-id="{{$val->id}}" data-name="{{$val->name}}" data-code="{{$val->code}}" data-min-total="{{$val->min_total}}" data-percentage="{{$val->percentage}}" data-total_user_limit="{{$val->total_user_limit}}" data-expired_date="@if($val->expired_date){{date('Y-m-d',strtotime($val->expired_date))}}@endif" class="edit_cls btn btn-success text-white">Edit</a>
                                            <a href="javascript:void(0)" data-id="{{$val->id}}" class="delete_cls btn btn-danger text-white">Delete</a>
                                            
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
        <div class="modal fade" id="exampleModalContent1" tabindex="-1" role="dialog"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title title_cls" id="">{{__('messages.add_promocode_text')}}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="add_form" action="{{route('admin.post_promocode')}}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="" id="id">
                                                <div class="form-group">
                                                    <label for=""
                                                        class="col-form-label">{{__('messages.promode_name_text')}}</label>
                                                    <input type="text" class="form-control" id="name" name="name">
                                                </div>

                                                <div class="form-group">
                                                    <label for=""
                                                        class="col-form-label">{{__('messages.promocode_code_text')}}</label>
                                                    <input type="text" class="form-control" id="code" name="code">
                                                </div>

                                                <div class="form-group">
                                                    <label for=""
                                                        class="col-form-label">{{__('messages.promocode_percentage_text')}}</label>
                                                    <input type="number" class="form-control" id="percentage" name="percentage">
                                                </div>

                                                <div class="form-group">
                                                    <label for=""
                                                        class="col-form-label">{{__('messages.promocode_min_total_text')}}</label>
                                                    <input type="number" class="form-control" id="min_total" name="min_total">
                                                </div>

                                                <div class="form-group">
                                                    <label for=""
                                                        class="col-form-label">{{__('messages.promocode_total_user_text')}}</label>
                                                    <input type="number" class="form-control" id="total_user_limit" name="total_user_limit">
                                                </div>

                                                <div class="form-group">
                                                    <label for=""
                                                        class="col-form-label">{{__('messages.promocode_expired_date_text')}}</label>
                                                    <input type="date" class="form-control" id="expired_date" name="expired_date">
                                                </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">{{__('messages.cancel_text')}}</button>
                                            <button type="submit" class="btn btn-primary save_btn">{{__('messages.save_text')}}</button>
                                        </div>
                                        </form>
                                            
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('messages.delete_promocode_text') }}</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body pb-4">
                {{__('messages.delete_promocode_title_text')}}
            </div>
            <div class="modal-footer">
                <form id="delete-form" action="{{ route('admin.delete_promocode') }}" method="POST">
                    @csrf
                <button class="btn btn-dark" type="button" data-dismiss="modal">{{ __('messages.cancel_text') }}</button>                       
                <button class="btn btn-primary delete_btn" type="submit">
                    {{ __('messages.ok_text') }}
                </button>
                <input type="hidden" name="id" value="" id="d_id">
                </form>
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


    $(document).on('click','.edit_cls',function(){
        id= $(this).attr('data-id');
        name= $(this).attr('data-name');
        code= $(this).attr('data-code');
        min_total= $(this).attr('data-min-total');
        percentage = $(this).attr('data-percentage');
        total_user_limit = $(this).attr('data-total_user_limit');
        expired_date = $(this).attr('data-expired_date');

        $(".title_cls").html(messages.edit_promcode_text);
        $("#id").val(id);
        $("#name").val(name);
        $("#code").val(code);
        $("#min_total").val(min_total);
        $("#percentage").val(percentage);
        $("#total_user_limit").val(total_user_limit);
        $("#expired_date").val(expired_date);

        $("#exampleModalContent1").modal('show');
    });

    $(document).on('click','.delete_cls',function(){
        id= $(this).attr('data-id');
        
        $("#d_id").val(id);
        
        $("#deleteModal").modal('show');
    });

    $(document).on('click','.delete_btn',function(){
        buttonDisabledWithoutDisabled(".delete_btn");
    });

    $(document).on('click','.add_cls',function(){
        $(".title_cls").html(messages.add_promocode_text);
        $("#id").val('');
        $("#name").val('');
        $("#code").val('');
        $("#min_total").val('');
        $("#percentage").val('');

        $("#total_user_limit").val('');
        $("#expired_date").val('');
    });

    $("#add_form").validate({
        rules: {
          
        name: {
            required: true, 
        },
        percentage: {
            required:true,
            greaterZero:true,
        },
        code: {
            required: true,
            remote: {
                url: SITE_URL + "/admin/check-promocode",
                type: "post",
                data: {
                    code: function () {
                        return $("#code").val();
                    },
                    id: function () {
                        return $("#id").val();
                    },
                },
            },
        },
        min_total: {
            required: true, 
            greaterZero:true,
        },
        total_user_limit: {
            //required:messages.percentage_required,
            greaterZeroNew:true,
            //number:true,
          },

          
        },
        messages: {
          name: {
            required:messages.name_required,
          },
          code: {
            required:messages.promo_code_required,
            remote:messages.promocde_already_exits,

          },
          min_total: {
            required:messages.promo_min_total_required,
            greaterZero:messages.promocode_min_total_required,
          },
          percentage: {
            required:messages.percentage_required,
            greaterZero:messages.promocode_min_percentage_required,
          },

          total_user_limit: {
            //required:messages.percentage_required,
            greaterZeroNew:messages.promocode_total_user_limit_min_required,
            number:messages.promocode_total_user_limit_min_num_required,
          },

        },
        submitHandler: function (form) {
          buttonDisabled(".save_btn");
          form.submit();

        },
      });

        $.validator.addMethod("greaterZero", function (value, element, param) {
        if(value<=0) {
            return false;
        } else {
            return true;
        }
    });

        $.validator.addMethod("greaterZeroNew", function (value, element, param) {
            if(value=='') {
                return true;
            }else if(value<=0) {
                return false;
            } else {
                return true;
            }

    });


</script>
@endsection