@extends('admin.layout.app')
@section('page_content')

    <link rel="stylesheet" href="/css/vendor/quill.snow.css" />
    <link rel="stylesheet" href="/css/vendor/quill.bubble.css" />
<div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <h1>{{trans('messages.sidebar_settings_text')}}</h1>
                <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                    <ol class="breadcrumb pt-0">
                        <li class="breadcrumb-item">
                            <a href="{{route('admin.dashboard')}}">{{trans('messages.home_text')}}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{trans('messages.sidebar_settings_text')}}</li>
                    </ol>
                </nav>
                <div class="separator mb-5"></div>
            </div>
        </div>


        <div class="row">

            <div class="col-12 col-lg-12">
                <div class="card mb-4">
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

                        <form id="settingForm" class="tooltip-label-right" method="post" action="{{route('admin.post_settings')}}">
                            @csrf
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group position-relative">
                                        <label class="font-weight-bold">{{__('messages.login_otp_enable_disable_text')}}</label>
                                        <div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="jQueryCustomRadio1" name="login_otp" class="custom-control-input" required="" value="1" @if(isset($settings_data['login_otp']) && $settings_data['login_otp']==1) checked="" @endif>
                                                <label class="custom-control-label" for="jQueryCustomRadio1">{{__('messages.enable_text')}}</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="jQueryCustomRadio2" name="login_otp" class="custom-control-input" required="" value="0" @if(isset($settings_data['login_otp']) && $settings_data['login_otp']==0) checked="" @endif>
                                                <label class="custom-control-label" for="jQueryCustomRadio2">{{__('messages.disable_text')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group position-relative error-l-50">
                                        <label class="font-weight-bold">{{__('messages.tax_text')}}</label>
                                        <input type="number" class="form-control" value="@if(isset($settings_data['tax'])){{$settings_data['tax']}}@endif" name="tax" id="tax">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group position-relative error-l-50">
                                        <label class="font-weight-bold">{{__('messages.commission_text')}}</label>
                                        <input type="number" class="form-control" value="@if(isset($settings_data['commission'])){{$settings_data['commission']}}@endif" name="commission" id="commission">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group position-relative error-l-50">
                                        <label class="font-weight-bold">{{__('messages.support_email_text')}}</label>
                                        <input type="email" class="form-control" value="@if(isset($settings_data['support_email'])){{$settings_data['support_email']}}@endif" name="support_email">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group position-relative error-l-50">
                                        <label class="font-weight-bold">{{__('messages.support_phone_text')}}</label>
                                        <input type="text" class="form-control" value="@if(isset($settings_data['support_phone'])){{$settings_data['support_phone']}}@endif" name="support_phone">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group position-relative error-l-50">
                                        <label class="font-weight-bold">{{__('messages.cash_limit')}}</label>
                                        <input type="number" class="form-control" value="@if(isset($settings_data['cash_limit'])){{$settings_data['cash_limit']}}@endif" name="cash_limit">
                                    </div>
                                </div>
                                <!-- <div class="col-md-4">
                                    <div class="form-group position-relative error-l-50">
                                        <label class="font-weight-bold">{{__('messages.copyright_text')}}</label>
                                        <input type="text" class="form-control" required="" value="" name="copyrightText">
                                    </div>`
                                </div> -->

                                <div class="col-md-3">
                                    <div class="form-group position-relative error-l-50">
                                        <label class="font-weight-bold">{{__('messages.minimum_cash_limit')}}</label>
                                        <input type="number" class="form-control" value="@if(isset($settings_data['min_cash_limit'])){{$settings_data['min_cash_limit']}}@endif" name="min_cash_limit" id="min_cash_limit">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group position-relative error-l-50">
                                        <label class="font-weight-bold">{{__('messages.maximum_cash_limit')}}</label>
                                        <input type="number" class="form-control" value="@if(isset($settings_data['max_cash_limit'])){{$settings_data['max_cash_limit']}}@endif" name="max_cash_limit" id="max_cash_limit">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group position-relative error-l-50">
                                        <label class="font-weight-bold">{{__('messages.app_fee')}}</label>
                                        <input type="number" class="form-control" value="@if(isset($settings_data['app_fee'])){{$settings_data['app_fee']}}@endif" name="app_fee" id="app_fee">
                                    </div>
                                </div>
                                @if(Lang::locale() == 'en')
                                    <div class="col-md-12">
                                        <div class="card mb-4">
                                            <div class="card-body" style="padding: 0 !important;">
                                                <label class="font-weight-bold">{{__('messages.terms_en')}}</label>
                                                <textarea name="terms_en" id="ckEditorClassic">
                                                    @if(isset($settings_data['terms_en']))
                                                        {{$settings_data['terms_en']}}
                                                    @endif
                                                </textarea>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if(Lang::locale() == 'ar')
                                    <div class="col-md-12">
                                        <div class="card mb-4">
                                            <div class="card-body" style="padding: 0 !important;">
                                                <label class="font-weight-bold">{{__('messages.terms_ar')}}</label>
                                                <textarea name="terms_ar" id="ckEditorClassic">
                                                    @if(isset($settings_data['terms_ar']))
                                                        {{$settings_data['terms_ar']}}
                                                    @endif
                                                </textarea>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary mb-0 btn_submit">{{__('messages.submit_text')}}</button>
                        </form>
                    </div>
                </div>


        </div>


</div>
    </div>
@endsection
@section('script_js')
<script src="/js/vendor/ckeditor5-build-classic/ckeditor.js"></script>
<script type="text/javascript">
    $("#settingForm").validate({
        rules: {

          tax: {
            required: true,
            taxCheck:true,
          },
          commission:{
            required:true,
            commissionCheck:true,
          },
          cash_limit:{
            required:true,
            commissionCheck:true,
          },
          support_email:{
            required:true,
            email:true,
            emailCheck:true,
          },
          support_phone:{
            required:true,
          },
            app_fee:{
                required:true,
            },
            minimum_cash_limit:{
                required:true,
            },
            max_cash_limit:{
                required:true,
            }

        },
        messages: {
          tax: {
            required:messages.tax_required,
            taxCheck:messages.tax_valid,
          },
          commission: {
            required:messages.commission_required,
            commissionCheck:messages.commission_valid,
          },
          cash_limit: {
            required:messages.cash_limit_required,
            commissionCheck:messages.cash_limit_valid,
          },
          support_email: {
            required:messages.email_required,
            email:messages.email_valid,
            emailCheck:messages.email_valid
          },
          support_phone: {
            required:messages.phone_number_required,
          },
            minimum_cash_limit: {
                required:messages.minimum_cash_limit_required,
            },
            max_cash_limit: {
                required:messages.minimum_cash_limit_required,
            },
            app_fee: {
                required:messages.app_fee_required,
            },
        },
        submitHandler: function (form) {
          buttonDisabled(".btn_submit");
          form.submit();

        },
      });
    $.validator.addMethod("emailCheck", function (value, element, param) {
        var check_result = false;
        result = this.optional( element ) || /[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}/.test( value );
        return result;
    });

    $.validator.addMethod("taxCheck", function (value, element, param) {
        if(value<=0) {
            return false;
        } else {
            return true;
        }
    });

    $.validator.addMethod("commissionCheck", function (value, element, param) {
        if(value<=0) {
            return false;
        } else {
            return true;
        }
    });
</script>
@endsection
