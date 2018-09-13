<!DOCTYPE html>
<html lang="en" ng-app="vgsApp">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="{{asset('img/'.\Config::get('client.favicon'))}}" type="image/png" sizes="16x16">
    <title>{{ \Config::get('client.title')}}</title>

    <!-- Global stylesheets -->
    {{--<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">--}}
    <link href="{{ asset('assets/css/icons/icomoon/styles.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/bootstrap.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/core.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/components.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/colors.css') }}" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css">
    <!-- Css & Fonts Libraries -->
    <link href="{{ asset('css/sweetalert.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery.timepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <!-- /Css & Fonts Libraries -->

    <!-- Core JS files -->
    <script type="text/javascript" src="{{ asset('assets/js/plugins/loaders/pace.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/core/libraries/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/core/libraries/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/loaders/blockui.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/ui/nicescroll.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/ui/drilldown.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/media/fancybox.min.js')}}"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/pages/gallery.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/core/app.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/pages/login.js') }}"></script>

    <!-- /theme JS files -->

    <!-- Angular Script -->

    <script src="{{ asset('js/angular/angular.js') }}"></script>
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-router/0.3.2/angular-ui-router.js"></script>--}}
    <script src="{{ asset('js/angular-ui-router.js') }}" type='text/javascript'></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/notifications/pnotify.min.js')}}"></script>

    <script src="{{ asset('js/module/app.js') }}" type='text/javascript'></script>

    <script type="text/javascript" src="{{ asset('assets/js/pages/components_notifications_pnotify.js')}}"></script>
    <script src="{{ asset('js/angular/underscore-min.js') }}"></script>
    <!-- Angular Script -->

    <!-- Angular Dependiences -->
    <script src="{{ asset('angular-theme/js/plugins/uiTree/angular-ui-tree.min.js') }}"></script>
    <script src="{{ asset('angular-theme/js/bootstrap/ui-bootstrap-tpls-0.11.0.min.js') }}"></script>
    <script src="{{ asset('angular-theme/js/angular/lodash.min.js') }}"></script>
    <script src="{{ asset('angular-theme/js/angular/angular-szn-autocomplete.js') }}"></script>
    {{--<script src="{{ asset('angular-theme/js/angular/angularjs-dropdown-multiselect.js') }}"></script>--}}
    {{--<script src="{{ asset('js/module/multiselect.js') }}"></script>--}}
    <script src="{{ asset('js/angular/angular-sanitize.js') }}"></script>

    <script src="{{ asset('js/module/services/service.js') }}" type='text/javascript'></script>
    <script src="{{ asset('js/module/directives/directive.js') }}" type='text/javascript'></script>
    <script src="{{ asset('js/module/directives/multislect-directive.js') }}" type='text/javascript'></script>
    <script src="{{ asset('js/module/filters/filter.js') }}" type='text/javascript'></script>
    {{--<script src="{{ asset('js/module/controllers/controller.js') }}" type='text/javascript'></script>--}}
    <script src="{{ asset('js/module/controllers/DashboardController.js') }}" type='text/javascript'></script>

{{--Vedio Plugin--}}

<!-- jQuery UI -->
    <script src="{{ asset('js/plugins/jquery-ui/jquery-ui.min.js') }}"></script>


    <!-- Sweet Alert -->
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>


    <!-- Global Variables -->
    <script>
        var role = '<?php echo isset($currentUser->role) ? $currentUser->role : ""; ?>';
        var baseUrl ="{{ \Config::get('client.baseUrl')}}";
        var userRoleId = 0;
    </script>
    <!-- /Global Variables -->

    {{--Google API--}}
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC8HdkhY4Iy8jo4bM0uIJVnaekAw7A0Xm8&libraries=visualization,places" ></script>
    <script src="{{ asset('js/richmarker.js') }}"></script>
    <script src="{{ asset('js/infobubble.js') }}"></script>

</head>
<body class="login-container" id="app-layout">

    @yield('template')

    @yield('content')
</div>

</body>
<script src="{{ asset('js/custom.js') }}"></script>

@yield('script')
{{--<script src="{{ asset('js/ticketplugin.js') }}"></script>--}}
</html>
