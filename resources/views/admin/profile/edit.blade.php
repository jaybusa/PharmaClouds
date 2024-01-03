@extends('admin.layout.app')
@section('page_content')
<div class="card mb-4">
    <div class="card-body">
        <h5 class="mb-4">{{__('messages.user_management_create_text1')}}</h5>
        <form action="{{ route('admin.update.profile',['user'=>$user->id])}}" method="post">
            @csrf
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 col-form-label">{{__('messages.add_parent_categories_name_text')}}</label>
                <div class="col-sm-10">
                    <input type="text" value="{{ $user->name }}" name="name" class="form-control" id="inputEmail3" placeholder="{{__('messages.add_parent_categories_name_text')}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 col-form-label">{{__('messages.email_text')}}</label>
                <div class="col-sm-10">
                    <input type="email" value="{{ $user->email }}" name="email" class="form-control" id="inputEmail3" placeholder="{{__('messages.email_text')}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="inputPassword3" class="col-sm-2 col-form-label">{{__('messages.user_role_text')}}</label>
                <div class="col-sm-10">
                    <input type="text" name="role" value="admin" class="form-control" id="inputPassword3" placeholder="{{__('messages.user_role_text')}}">
                </div>
            </div>
            <h5 class="mb-4">{{__('messages.user_management_create_text2')}}</h5>

            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 col-form-label">{{__('messages.username_text')}}</label>
                <div class="col-sm-10">
                    <input type="text" name="username" value="{{ $user->username }}" class="form-control" id="inputEmail3" placeholder="{{__('messages.username_text')}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="inputPassword3" class="col-sm-2 col-form-label">{{__('messages.password_text')}}</label>
                <div class="col-sm-10">
                    <input type="password" name="pass" class="form-control" id="inputPassword3" placeholder="{{__('messages.password_text')}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 col-form-label">{{__('messages.confirm_password_text')}}</label>
                <div class="col-sm-10">
                    <input type="password" name="confirm_pass" class="form-control" id="inputEmail3" placeholder="{{__('messages.confirm_password_text')}}">
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary mb-0">{{__('messages.save_text')}}</button>
                    <a href="{{ url()->previous() }}" type="submit" class="btn btn-primary mb-0">{{__('messages.cancel_text')}}</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
