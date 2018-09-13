<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

    <title>Trafic Analyzer</title>

    <link href="{{asset('/css/custom.css')}}" rel="stylesheet">
    <link href="{{asset('/css/gmap-nav.css')}}" rel="stylesheet">
    <link href="{{asset('/css/style.css')}}" rel="stylesheet">


    <script>
        {{--var token  = '{!! $token !!}';--}}
        {{--var webUrl = '{!! $webUrl !!}';--}}
        {{--var apiUrl =  '{!! $apiUrl !!}';--}}
    </script>
</head>
<body>

<div id="wrapper">

    <div id ="loader"  class="overlay" style="height: inherit;width:100%;display: none">
        <div style="position: absolute;  margin-left: 50%;  margin-top: 25%;">
            <img src="{{url('img/loader.gif')}}" />
        </div>
    </div>


    <nav class="navbar navbar-default navbar-fixed-top" style="background-color: #003C61;">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <div  class="left-text">
                    <strong><a style="color: white" href="#">Team ARD</a></strong>
                </div>
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a style="color: white" href="#">Assets</a>
                    </li>
                    <li>
                        <a href="#">Competetion</a>
                    </li>
                    <li>
                        <a href="#">Analysis</a>
                    </li>
                    <li>
                        <a href="#">Stories</a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="drpdown-toggle  count-info" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bell"></i>
                            <span id="notificationCount" class="label label-danger">0</span>
                        </a>
                        <ul class="dropdown-menu"></ul>
                    <li>
                        <a onclick="gisLogOut()"><i class="fa fa-sign-out"></i> Log out</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!--Side Bar-->

    <div id="sidebar" class="sidebar collapsed" style="display: block; ">
        <!-- Nav tabs -->
        <div class="sidebar-tabs">
            <ul role="tablist">
                <li><a title="Assets" href="#home" id="routeInfo" role="tab"><i class="fa fa-road"></i></a></li>
                <li><a title="Competetion" href="#gis_chart" role="tab"><i class="fa fa-bar-chart"></i></a></li>
                {{--<li><a href="#messages" role="tab"><i class="fa fa-envelope"></i></a></li>--}}
            </ul>
            {{--<ul role="tablist">
                <li><a href="#settings" role="tab"><i class="fa fa-gear"></i></a></li>
            </ul>--}}
        </div>

        <!-- Tab panes -->
        <div class="sidebar-content clearfix">
            <div class="sidebar-pane clearfix" id="home">
                <h1 class="sidebar-header">Assets<span class="sidebar-close"><i class="fa fa-caret-right"></i></span></h1>

                <div class="row" style="padding-top: 20px;">
                    <div class="col-sm-12">
                        <div class="form-group-sm">
                                <label class="checkbox-inline"><input name="q" type="radio" value="option1" id="inlineCheckbox1"> Land </label>
                                <label class="checkbox-inline"><input name="q" type="radio" value="option2" id="inlineCheckbox2"> Branch </label>
                                <label class="checkbox-inline"><input name="q" type="radio" value="option3" id="inlineCheckbox3"> ATM </label>
                                <label class="checkbox-inline"><input name="q" type="radio" value="option3" id="inlineCheckbox3"> Offices </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">Class</div>
                    <div class="col-sm-8">
                        <select class="form-control input-sm" name="" id="">
                            <option value="">-All-</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">Type</div>
                    <div class="col-sm-8">
                        <select class="form-control input-sm" name="" id="">
                            <option value="">-All-</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">Ownership</div>
                    <div class="col-sm-8">
                        <select class="form-control input-sm" name="" id="">
                            <option value="">-All-</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">Compliance</div>
                    <div class="col-sm-8">
                        <select class="form-control input-sm" name="" id="">
                            <option value="">-All-</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">Built Area</div>
                    <div class="col-sm-8">
                        <select class="form-control input-sm" name="" id="">
                            <option value="">-All-</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">Region</div>
                    <div class="col-sm-8">
                        <select class="form-control input-sm" name="" id="">
                            <option value="">-All-</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">City</div>
                    <div class="col-sm-8">
                        <select class="form-control input-sm" name="" id="">
                            <option value="">-All-</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 text-right clearfix">
                        <button class="btn"><i class="fa fa-undo"></i> Reset</button>
                        <button class="btn" type="submit" style="background-color: #003C61; color:white"><i class="fa fa-search"></i>  Run Query</button>
                    </div>
                </div>
                {{--<div class="row">--}}
                    {{--<div class="col-sm-4"></div>--}}
                    {{--<div class="col-sm-8">--}}
                        {{--<div class="col-sm-4"><button class="btn">Run Query</button></div>--}}
                        {{--<div class="col-sm-4"><button class="btn">Reset</button></div>--}}
                    {{--</div>--}}
                {{--</div>--}}

            </div>

            <div class="sidebar-pane" id="gis_chart">
                <h1 class="sidebar-header">Competition<span class="sidebar-close"><i class="fa fa-caret-right"></i></span></h1>
                <div id="gis_chart_container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
            </div>

            {{--<div class="sidebar-pane" id="messages">
                <h1 class="sidebar-header">Messages<span class="sidebar-close"><i class="fa fa-caret-left"></i></span></h1>
            </div>

            <div class="sidebar-pane" id="settings">
                <h1 class="sidebar-header">Settings<span class="sidebar-close"><i class="fa fa-caret-left"></i></span></h1>
            </div>--}}
        </div>
    </div>

    <!--Side Bar-->

    <div id="map"></div>
</div>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<script src="{{asset('/js/gmap-nav.js')}}"></script>
<script>
    var sidebar = $('#sidebar').sidebar();
    $('#routeInfo').trigger( "click" );

</script>

<!--Highcharts Scripts-->
<script src="{{asset('/js/highcharts.js')}}"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>

<script src="{{asset('/js/gis.js')}}"></script>
<script src="{{asset('/js/gis_chart.js')}}"></script>


<script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJo4u2hiqPKQbmW-2Hnd3ckPHBfyrHI7E&callback=initMap" async defer></script>


<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
{{--<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>--}}
<script src="https://unpkg.com/sweetalert2@7.20.5/dist/sweetalert2.all.js"></script>

</body>
</html>