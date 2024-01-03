@extends('admin.layout.app')
@section('page_content')
<div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h1>{{__('messages.hairdresser_list_text')}}</h1>
                    <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                        <ol class="breadcrumb pt-0">
                            <li class="breadcrumb-item">
                                <a href="{{route('admin.dashboard')}}">{{__('messages.home_text')}}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="#">{{__('messages.hairdresser_list_text')}}</a>
                            </li>
                        </ol>
                    </nav>
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
                                        <th>{{__('messages.name_text')}}</th>
                                        <th>{{__('messages.email_text')}}</th>
                                        <th>{{__('messages.phone_number_text')}}</th>
                                        <th>{{__('messages.image_text')}}</th>
                                        <th>{{__('messages.address_text')}}</th>
                                        <th>{{__('messages.status_text')}}</th>
                                        <th>{{__('messages.created_at_text')}}</th>
                                        <th>{{__('messages.action_text')}}</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($list as $key=>$val)
                                    <tr>
                                        <td>{{$val->name}}</td>
                                        <td>{{$val->email}}</td>
                                        <td>{{$val->phone_number}}</td>
                                        <td>@if($val->image)
                                        <img src="{{url('/')}}/user/{{$val->image}}" width="100" height="100" style="margin-top: 10px" id="image">
                                            @else
                                        <img src="{{url('/')}}/user/default.jpg" width="100" height="100" style="margin-top: 10px" id="image">

                                            @endif</td>
                                        <td>{{$val->address}}</td>
                                        <td>
                                            <?php 
                                            $checked = '';
                                            if($val->is_active==1) {
                                                $checked = 'checked';
                                            }
                                            ?>
                                            <div class="custom-switch custom-switch-secondary">
                                                <input class="custom-switch-input toggle_changed" id="switch{{$key}}" type="checkbox" {{$checked}} data-id="{{$val->id}}">
                                                <label class="custom-switch-btn" for="switch{{$key}}"></label>
                                            </div>

                                        </td>
                                        <td>{{$val->created_at}}</td>
                                        <td>
                                        <a class="btn btn-info text-white" href="{{route('admin.hairdresser_detail',$val->id)}}">View</a></td>
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

    $(document).on('change','.toggle_changed',function(){
        is_active = 0;
        if($(this).is(':checked')) {
            is_active = 1;
        }
        user_id = $(this).attr('data-id');
        url = "{{route('admin.hairdresser_change_status')}}";
        form_data = 'user_id='+user_id;
        form_data+='&is_active='+is_active;
        $.post(url,form_data,function(response){
            //alert(response);
        });
    })


</script>
@endsection