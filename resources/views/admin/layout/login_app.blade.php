<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{env('APP_NAME')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link rel="stylesheet" href="{{url('/')}}/font/iconsmind-s/css/iconsminds.css" />
    <link rel="stylesheet" href="{{url('/')}}/font/simple-line-icons/css/simple-line-icons.css" />

    <link rel="stylesheet" href="{{url('/')}}/css/vendor/bootstrap.min.css" />
    <link rel="stylesheet" href="{{url('/')}}/css/vendor/bootstrap.rtl.only.min.css" />
    <link rel="stylesheet" href="{{url('/')}}/css/vendor/bootstrap-float-label.min.css" />
    <link rel="stylesheet" href="{{url('/')}}/css/main.css" />
    <script type="text/javascript">
        SITE_URL = "{{url('/')}}/";
        locale = "<?php if(Session::has('locale')){echo Session::get('locale');}else {echo 'en';}?>";
        //alert(locale);
        <?php 
            $messages = \Lang::get('messages');
            ?>
        var messages = <?php echo json_encode($messages) ?>;
        //alert(messages.login.title);
    </script>
</head>

<body class="background show-spinner no-footer">
    <div class="fixed-background"></div>
    @yield('content')
    <script src="{{url('/')}}/js/vendor/jquery-3.3.1.min.js"></script>
    <script src="{{url('/')}}/js/vendor/bootstrap.bundle.min.js"></script>
    <script src="{{url('/')}}/js/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script src="{{url('/')}}/js/vendor/jquery.validate/additional-methods.min.js"></script>
    <script src="{{url('/')}}/js/dore.script.js"></script>
    <script src="{{url('/')}}/js/scripts.js?v=1.1.3"></script>
    <script src="{{url('/')}}/js/common.js?v=1.1.3"></script>
    @yield('script_js')
</body>

</html>