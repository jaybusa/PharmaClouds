@extends('admin.layout.login_app')
@section('content')
<main>
    <div class="container">
        <div class="row h-100">
            <div class="col-12 col-md-10 mx-auto my-auto">
                <div class="card auth-card">
                    <div class="position-relative image-side ">

                        <p class=" text-white h2">{{__('messages.site_name')}} {{__('messages.admin_text')}}</p>

                        <p class="white mb-0">
                            {{trans('messages.login_left_title')}}
                            <!-- <br>If you are not a member, please
                            <a href="#" class="white">register</a>. -->
                        </p>
                    </div>
                    <div class="form-side">
                        <div class="mb-4">
                            {{trans('messages.select_theme_text')}}
                            <input type="radio" name="theme" id="light" checked=""> {{trans('messages.light_theme_text')}}
                            <input type="radio" name="theme" id="dark"> {{trans('messages.dark_theme_text')}}
                        </div>

                        <div class="mb-4">
                            {{trans('messages.select_language_text')}}
                            <select id="language">
                                <option value="en">{{trans('messages.english_text')}}</option>
                                <option value="ar">{{trans('messages.arabic_text')}}</option>
                            </select>
                        </div>

                    @error('email')
                    <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>    
                        <strong>{{ $message }}</strong>
                    </div>
                    @enderror
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

                        <!-- <a href="Dashboard.Default.html">
                            <span class="logo-single"></span>
                        </a>
                        <h6 class="mb-4">Login</h6> -->
                        <form id="loginForm" method="post" action="{{route('login.post')}}">
                            @csrf
                            <input type="hidden" name="role_id" value="1">
                            <label class="form-group has-float-label mb-4">
                                <input class="form-control" name="email" placeholder="{{trans('messages.email_text')}}" title="{{trans('messages.email_text')}}" />
                                <span>{{trans('messages.email_text')}}</span>

                            </label>
                            <label id="email-error" class="error" for="email"></label>

                            <label class="form-group has-float-label mb-4">
                                <input class="form-control" type="password" placeholder="{{trans('messages.password_text')}}" title="{{trans('messages.password_text')}}" name="password" />
                                <span>{{trans('messages.password_text')}}</span>
                            </label>
                            <label id="password-error" class="error" for="password" style="display: none;"></label>
                            <!-- <div class="form-group row">
                                    <div class="col-md-6 ">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember">

                                            <label class="form-check-label" for="remember">
                                                Remember Me
                                            </label>
                                        </div>
                                    </div>
                                </div> -->
                            <div class="d-flex justify-content-between align-items-center">
                                <!-- <a href="#">Forget password?</a> -->
                                <button class="btn btn-primary btn-lg btn-shadow login_btn" type="submit">{{trans('messages.login_text')}}</button>
<!--                                 <a href="{{route('admin.dashboard')}}" class="btn btn-primary btn-lg btn-shadow">LOGIN</a>
 -->                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
@section('script_js')
<script type="text/javascript">
    $("#loginForm").validate({
        rules: {
          
          email: {
            required: true,
            email: true,
            emailCheck:true,
          },
          
          password: {
            required: true,
            minlength: 6,
          },
          
        },
        messages: {
          email: {
            required:messages.email_required,
            email:messages.email_valid,
            emailCheck:messages.email_valid
          },
          password: {
            required:messages.password_required,
            minlength: messages.password_min
          },
        },
        submitHandler: function (form) {
          buttonDisabled(".login_btn");
          form.submit();

        },
      });
    $.validator.addMethod("emailCheck", function (value, element, param) {
        var check_result = false;
        result = this.optional( element ) || /[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}/.test( value );
        return result;
    });
</script>
@endsection