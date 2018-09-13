<!DOCTYPE html>
<html style="overflow: hidden;">
<head>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{asset('/css/bootstrap.min.css')}}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

    <link href="{{asset('/css/gmap-nav.css')}}" rel="stylesheet">
    <link href="{{asset('/css/custom.css')}}" rel="stylesheet">
</head>
<body style="overflow: hidden;">

<!-- Search Bar -->
<div style=" width: 315px; border-radius: 3px; padding: 0.5%; background-color: #fff; position: absolute; top:20px; z-index: 9; left:0.5%;box-shadow: 5px 5px 3px 3px #888;">
    <div onclick="SwitchSideBar();" title="Menu" style=" width:25px; float: left; cursor: pointer; padding-left: 0px; margin-right: 5%; margin-left: 5%; padding-right: 0px;">
        <span style="font-size: 24px; font-weight: 100; color: #003D61;">
            <i class="fa fa-bars"></i>
        </span>
    </div>

    <div style="float: left; width:200px;  padding-left: 0px; padding-right: 0px;">
        <input title="Enter Code, Name or Address" onkeydown="if(event.keyCode == 13) consolidatedSearch();" style=" border: 0px;  box-shadow: none; padding: 0.5%; width: 100%;" type="text" placeholder="Search location, Branch or ATM" class="form-control consolidated-search" name="consolidatedSearch" id="consolidatedSearch">
    </div>

    <div onclick="consolidatedSearch();" title="Search" style="width:25px;  float: right; margin-top: 0.7%; cursor: pointer; margin-left: 5%; padding-left: 0px; padding-right: 0px;">
        <span style="font-size: 20px; color: #003D61;">
            <i class="fa fa-search"></i>
        </span>
    </div>

</div>
<!--Analysis Side Bar-->

<div id="sidebar" class="sidebar collapsed" style="position: absolute;  left:-39px; display: block; ">

    <!-- Nav tabs -->
    <div class="sidebar-tabs">
        <ul role="tablist">
            <li><a title="Analysis" href="#home" id="routeInfo" role="tab"><i class="fa fa-map"></i></a></li>
        </ul>
    </div>

    <div id="overlay">
        <div id="loaderImage">
            <img style="width: 150px;" src="{{asset('/img/loader.gif')}}">
        </div>
    </div>

    <!-- Tab panes -->
    <div class="sidebar-content clearfix">

        <div class="sidebar-pane clearfix" id="home">

            <h1 class="sidebar-header">Analysis<span class="sidebar-close"><i class="fa fa-caret-left"></i></span></h1>

            <div class="row" style="padding-top: 20px;">
                <div class="col-sm-12" style="padding-left: 0;">
                    <button style="border-radius: 0px;" onclick="switchTab('bankStories'); " id="bankStoriesTab" type="button" class="active btn btn-sm btn-outline btn-default col-sm-3 analysis-tab">
                        <img src="{{url('/img/sidebar/1.png')}}" style="height: 17px;" title="Branch">
                    </button>
                    <button style="border-radius: 0px;" onclick="switchTab('atmStories'); " id="atmStoriesTab" type="button" class="btn btn-sm btn-outline btn-default col-sm-3 analysis-tab">
                        <img src="{{url('/img/sidebar/2.png')}}" style="height: 17px;" title="ATM">
                    </button>
                    <button disabled style="border-radius: 0px;" onclick="switchTab('headquaterStories'); " id="headquaterStoriesTab" type="button" class="btn btn-sm btn-outline btn-default col-sm-3 analysis-tab">
                        <img src="{{url('/img/sidebar/4.png')}}" style="height: 17px;" title="Land">
                    </button>
                    <button disabled style="border-radius: 0px;" onclick="switchTab('locationStories'); " id="locationStoriesTab" type="button" class="btn btn-sm btn-outline btn-default col-sm-3 analysis-tab">
                        <img src="{{url('/img/sidebar/3.png')}}" style="height: 17px;" title="Office">
                    </button>
                </div>
            </div>

            <div class="row competition-div" style="display: none;" id="bankStoriesDiv">
                <div id="bankStoriesForm">
                    <div class="row col-lg-12" style="padding: 0px 0px 0px 18px;">
                        <div onclick=" selectRegion('bank',this); drawRegionMarker(this); setMapCenter(24.63375,46.716833,6);" title="Click to see the banks in central region." class="col-lg-6 maps" id="central_bank" style="cursor: pointer; background-color: #E0E0E0;border-style: groove;border: 6px solid aliceblue;">
                            <img src="{{url('/img/sa1.png')}}" style="    width: 102px;    height: 76px;
    margin-left: 10px">
                            <span id="central_bank_check" style=" display: none; position: absolute; top:5px; right: 5px; ">
                                <img style="width: 25px;" src="{{url('/img/correct.png')}}">
                            </span>
                        </div>
                        <div onclick="selectRegion('bank',this); drawRegionMarker(this); setMapCenter(31.681327, 39.147022,6);" title="Click to see the banks in northern region." class="col-lg-6 maps" id="northern_bank" style="cursor: pointer; background-color: #E0E0E0;border-style: groove;border: 6px solid aliceblue;">
                            <img src="{{url('/img/sa2.png')}}" style="width: 137px;padding: 9px 30px 8px 0px;height: 76px;margin-left: -4px;">
                            <span id="northern_bank_check" style="display: none; position: absolute; top:5px; right: 5px; ">
                                <img style="width: 25px;" src="{{url('/img/correct.png')}}">
                            </span>
                        </div>
                    </div>
                    <div class="row col-lg-12" style="padding: 0px 0px 0px 18px;">
                        <div class="col-lg-6" style="text-align: center;">
                            <strong>Central</strong>
                        </div>
                        <div class="col-lg-6" style="text-align: center;">
                            <strong>Northern</strong>
                        </div>
                    </div>
                    <br>
                    <div class="row col-lg-12" style="padding: 0px 0px 0px 18px;">
                        <div onclick="selectRegion('bank',this); drawRegionMarker(this); setMapCenter(23.004161, 51.669735,6);" class="col-lg-6 maps" title="Click to see the banks in eastern region." id="eastern_bank" style="cursor: pointer; background-color: #E0E0E0;border-style: groove;border: 6px solid aliceblue;">
                            <img src="{{url('/img/sa3.png')}}" style="width: 137px;padding: 9px 30px 8px 0px;height: 76px;margin-left: -4px;">
                            <span id="eastern_bank_check" style="display: none; position: absolute; top:5px; right: 5px; ">
                                <img style="width: 25px;" src="{{url('/img/correct.png')}}">
                            </span>
                        </div>
                        <div onclick="selectRegion('bank',this); drawRegionMarker(this); setMapCenter(21.485667,39.191417,6); " class="col-lg-6 maps" id="western_bank" title="Click to see the banks in western region." style="cursor: pointer; background-color: #E0E0E0;border-style: groove;border: 6px solid aliceblue;">
                            <img src="{{url('/img/sa4.png')}}" style="width: 137px;padding: 9px 30px 8px 0px;height: 76px;margin-left: -4px;">
                            <span id="western_bank_check" style="display: none; position: absolute; top:5px; right: 5px; ">
                                <img style="width: 25px;" src="{{url('/img/correct.png')}}">
                            </span>
                        </div>
                    </div>
                    <div class="row col-lg-12" style="padding: 0px 0px 0px 18px;">
                        <div class="col-lg-6" style="text-align: center;">
                            <strong>Eastern</strong>
                        </div>
                        <div class="col-lg-6" style="text-align: center;">
                            <strong>Western</strong>
                        </div>
                    </div>
                    <br>
                    <div class="row col-lg-12" style="padding: 0px 0px 0px 18px;">
                        <div onclick="selectRegion('bank',this); drawRegionMarker(this); setMapCenter(18.2073,42.518833,6);" class="col-lg-6 maps" id="southern_bank" title="Click to see the banks in southern region." style="cursor: pointer; background-color: #E0E0E0;border-style: groove;border: 6px solid aliceblue;">
                            <img src="{{url('/img/sa5.png')}}" style="width: 137px;padding: 9px 30px 8px 0px;height: 76px;margin-left: -4px;">
                            <span id="southern_bank_check" style="display: none; position: absolute; top:5px; right: 5px; ">
                                <img style="width: 25px;" src="{{url('/img/correct.png')}}">
                            </span>
                        </div>
                        <div onclick="selectRegion('bank',this); drawRegionMarker(this); setMapCenter(23.8859,45.0792,6);" class="col-lg-6 maps" id="kingdom_bank" title="Click to see the banks all over the kingdom." style="cursor: pointer; background-color: #E0E0E0;border-style: groove;border: 6px solid aliceblue;">
                            <img src="{{url('/img/sa6.png')}}" style="width: 137px;padding: 9px 30px 8px 0px;height: 76px;margin-left: -4px;">
                            <span id="kingdom_bank_check" style="display: none; position: absolute; top:5px; right: 5px; ">
                                <img style="width: 25px;" src="{{url('/img/correct.png')}}">
                            </span>
                        </div>
                    </div>
                    <div class="row col-lg-12" style="padding: 0px 0px 0px 18px;">
                        <div class="col-lg-6" style="text-align: center;">
                            <strong>Southern</strong>
                        </div>
                        <div class="col-lg-6" style="text-align: center;">
                            <strong>Kingdom</strong>
                        </div>
                    </div>
                    <br>
                    <div class="row" style="margin-top: 271px;">
                        <h2 class="sidebar-inside-header" style="text-align: center;margin: 14px 31px 0px 20px;">Drawing Tools</h2>
                    </div>
                    <div id="panel_bank">
                        <div id="color-palette_bank" title="Select color by which you want to fill the shape." style="float: left; padding: 5px;"> </div>
                        <button style="width: 27.5px; height: 27.5px; float: right; margin-right: 5%; margin-top: 2.2%;" id="delete-button_bank" class="btn btn-primary btn-circle" title="Deleted selected shape from map.">
                            <i class="fa fa-trash-o"></i>
                        </button>
                    </div>
                    <div class="row col-lg-12" style="padding: 0px 0px 0px 18px;margin-bottom: 15px;">
                        <div onclick="changeDrawingMode('line'); selectDrawingTool('bank',this);" title="Click to find the distance on map between routes." class="col-lg-6 tools" id="distance_bank" style="cursor: pointer; background-color: #4A89BF;border: 2px solid white;color: white;text-align: center;height: 76px;line-height: 32px;">
                            Measure distance<br>
                            <img src="{{url('/img/icons/connect-line.png')}}" style="height: 18px;margin-bottom: 15px;">
                            {{--<i class="fa fa-square-o"></i>--}}
                        </div>
                        <div onclick="changeDrawingMode('hexagon'); selectDrawingTool('bank',this);" class="col-lg-6 tools" title="Click to draw hexagon on map." id="hexagon_bank" style="cursor: pointer; background-color: #4A89BF;border: 2px solid white;color: white;text-align: center;height: 76px;line-height: 32px;">
                            Draw a hexagon<br>
                            <img src="{{url('/img/icons/polygon.png')}}" style="height: 18px;margin-bottom: 15px;">
                            {{--<i class="fa fa-circle-thin"></i>--}}
                        </div>
                    </div>
                    <div class="row col-lg-12" style="padding: 0px 0px 0px 18px;">
                        <div class="col-lg-6 tools" onclick="changeDrawingMode('square'); selectDrawingTool('bank',this);" title="Click to draw square on map." id="square_bank" style="cursor: pointer; background-color: #4A89BF;border: 2px solid white;color: white;text-align: center;height: 76px;line-height: 32px;">
                            Draw a square<br>
                            <img src="{{url('/img/icons/box.png')}}" style="height: 18px;margin-bottom: 15px;">
                            {{--<i class="fa fa-square-o"></i>--}}
                        </div>
                        <div class="col-lg-6 tools" onclick="changeDrawingMode('circle'); selectDrawingTool('bank',this);" title="Click to draw circle on map." id="circle_bank" style="cursor: pointer; background-color: #4A89BF;border: 2px solid white;color: white;text-align: center;height: 76px;line-height: 32px;">
                            Draw a circle<br>
                            <img src="{{url('/img/icons/circle.png')}}" style="height: 18px;margin-bottom: 15px;">
                            {{--<i class="fa fa-circle-thin"></i>--}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row competition-div" id="atmStoriesDiv">
                <div id="atmStoriesForm">
                    <div class="row col-lg-12" style="padding: 0px 0px 0px 18px;">
                        <div onclick="selectRegion('atm',this); drawRegionMarker(this); setMapCenter(24.63375,46.716833,6);" class="col-lg-6 maps" title="Click to see all the banks in central region." id="central_atm" style="cursor: pointer; background-color: #E0E0E0;border-style: groove;border: 6px solid aliceblue;">
                            <img src="{{url('/img/sa1.png')}}" style="width: 137px;padding: 9px 30px 8px 0px;height: 76px;margin-left: -4px;">
                            <span id="central_atm_check" style="position: absolute; top:5px; right: 5px; ">
                                <img style="width: 25px;" src="{{'/img/correct.png'}}">
                            </span>
                        </div>
                        <div onclick="selectRegion('atm',this); drawRegionMarker(this); setMapCenter(31.681327, 39.147022,6);" class="col-lg-6 maps" title="Click to see all the banks in northern region." id="northern_atm" style="cursor: pointer; background-color: #E0E0E0;border-style: groove;border: 6px solid aliceblue;">
                            <img src="{{url('/img/sa2.png')}}" style="width: 137px;padding: 9px 30px 8px 0px;height: 76px;margin-left: -4px;">
                            <span id="northern_atm_check" style="position: absolute; top:5px; right: 5px; ">
                                <img style="width: 25px;" src="{{url('/img/correct.png')}}">
                            </span>
                        </div>
                    </div>
                    <div class="row col-lg-12" style="padding: 0px 0px 0px 18px;">
                        <div class="col-lg-6" style="text-align: center;">
                            <strong>Central</strong>
                        </div>
                        <div class="col-lg-6" style="text-align: center;">
                            <strong>Northern</strong>
                        </div>
                    </div>
                    <br>
                    <div class="row col-lg-12" style="padding: 0px 0px 0px 18px;">
                        <div onclick="selectRegion('atm',this); drawRegionMarker(this); setMapCenter(23.004161, 51.669735,6);" class="col-lg-6 maps" title="Click to see all the banks in eastern region." id="eastern_atm" style="cursor: pointer; background-color: #E0E0E0;border-style: groove;border: 6px solid aliceblue;">
                            <img src="{{url('/img/sa3.png')}}" style="width: 137px;padding: 9px 30px 8px 0px;height: 76px;margin-left: -4px;">
                            <span id="eastern_atm_check" style="position: absolute; top:5px; right: 5px; ">
                                <img style="width: 25px;" src="{{url('/img/correct.png')}}">
                            </span>
                        </div>
                        <div onclick="selectRegion('atm',this); drawRegionMarker(this); setMapCenter(21.485667,39.191417,6);" class="col-lg-6 maps" title="Click to see all the banks in western region." id="western_atm" style="cursor: pointer; background-color: #E0E0E0;border-style: groove;border: 6px solid aliceblue;">
                            <img src="{{url('/img/sa4.png')}}" style="width: 137px;padding: 9px 30px 8px 0px;height: 76px;margin-left: -4px;">
                            <span id="western_atm_check"  style="position: absolute; top:5px; right: 5px; ">
                                <img style="width: 25px;" src="{{url('/img/correct.png')}}">
                            </span>
                        </div>
                    </div>
                    <div class="row col-lg-12" style="padding: 0px 0px 0px 18px;">
                        <div class="col-lg-6" style="text-align: center;">
                            <strong>Eastern</strong>
                        </div>
                        <div class="col-lg-6" style="text-align: center;">
                            <strong>Western</strong>
                        </div>
                    </div>
                    <br>
                    <div class="row col-lg-12" style="padding: 0px 0px 0px 18px;">
                        <div onclick="selectRegion('atm',this); drawRegionMarker(this); setMapCenter(18.2073,42.518833,6);" class="col-lg-6 maps" id="southern_atm" title="Click to see all the banks in southern region." style="cursor: pointer; background-color: #E0E0E0;border-style: groove;border: 6px solid aliceblue;">
                            <img src="{{url('/img/sa5.png')}}" style="width: 137px;padding: 9px 30px 8px 0px;height: 76px;margin-left: -4px;">
                            <span id="southern_atm_check" style="position: absolute; top:5px; right: 5px; ">
                                <img style="width: 25px;" src="{{url('/img/correct.png')}}">
                            </span>
                        </div>
                        <div onclick="selectRegion('atm',this); drawRegionMarker(this); setMapCenter(23.8859,45.0792,6); " class="col-lg-6 maps" id="kingdom_atm" title="Click to see the banks all over the kingdom." style="cursor: pointer; background-color: #E0E0E0;border-style: groove;border: 6px solid aliceblue;">
                            <img src="{{url('/img/sa6.png')}}" style="width: 137px;padding: 9px 30px 8px 0px;height: 76px;margin-left: -4px;">
                            <span id="kingdom_atm_check" style="position: absolute; top:5px; right: 5px; ">
                                <img style="width: 25px;" src="{{url('/img/correct.png')}}">
                            </span>
                        </div>
                    </div>
                    <div class="row col-lg-12" style="padding: 0px 0px 0px 18px;">
                        <div class="col-lg-6" style="text-align: center;">
                            <strong>Southern</strong>
                        </div>
                        <div class="col-lg-6" style="text-align: center;">
                            <strong>Kingdom</strong>
                        </div>
                    </div>
                    <br>
                    <div class="row" style="margin-top: 271px;">
                        <h2 class="sidebar-inside-header" style="text-align: center;margin: 14px 31px 0px 20px;">Drawing Tools</h2>
                    </div>
                    <div id="panel_atm">
                        <div id="color-palette_atm" title="Select color by which you want to fill the shape." style="float: left; padding: 5px;"> </div>
                        <button style="float: right; margin-right: 5%; margin-top: 3%;" id="delete-button_atm" class="btn btn-primary btn-circle" title="Deleted selected shape from map.">
                            <i class="fa fa-trash-o"></i>
                        </button>
                    </div>
                    <div class="row col-lg-12" style="padding: 0px 0px 0px 18px;margin-bottom: 15px;">
                        <div onclick="changeDrawingMode('line'); selectDrawingTool('atm',this);" title="Click to find the distance on map between routes." class="col-lg-6 tools" id="distance_atm" style="cursor: pointer; background-color: #4A89BF;border: 2px solid white;color: white;text-align: center;height: 76px;line-height: 32px;">
                            Measure distance<br>
                            <img src="{{url('/img/icons/connect-line.png')}}" style="height: 18px;margin-bottom: 15px;">
                            {{--<i class="fa fa-square-o"></i>--}}
                        </div>
                        <div onclick="changeDrawingMode('hexagon'); selectDrawingTool('atm',this);" title="Click to draw hexagon on map." class="col-lg-6 tools" id="hexagon_atm" style="cursor: pointer; background-color: #4A89BF;border: 2px solid white;color: white;text-align: center;height: 76px;line-height: 32px;">
                            Draw a hexagon<br>
                            <img src="{{url('/img/icons/polygon.png')}}" style="height: 18px;margin-bottom: 15px;">
                            {{--<i class="fa fa-circle-thin"></i>--}}
                        </div>
                    </div>
                    <div class="row col-lg-12" style="padding: 0px 0px 0px 18px;">
                        <div onclick="changeDrawingMode('square'); selectDrawingTool('atm',this);" title="Click to draw square on map." class="col-lg-6 tools" id="square_atm" style="cursor: pointer; background-color: #4A89BF;border: 2px solid white;color: white;text-align: center;height: 76px;line-height: 32px;">
                            Draw a square<br>
                            <img src="{{url('/img/icons/box.png')}}" style="height: 18px;margin-bottom: 15px;">
                            {{--<i class="fa fa-square-o"></i>--}}
                        </div>
                        <div onclick="changeDrawingMode('circle'); selectDrawingTool('atm',this);"  title="Click to draw circle on map." class="col-lg-6 tools" id="circle_atm" style="cursor: pointer; background-color: #4A89BF;border: 2px solid white;color: white;text-align: center;height: 76px;line-height: 32px;">
                            Draw a circle<br>
                            <img src="{{url('/img/icons/circle.png')}}" style="height: 18px;margin-bottom: 15px;">
                            {{--<i class="fa fa-circle-thin"></i>--}}
                        </div>
                    </div>
                </div>
            </div>

            {{--<div class="row competition-div" style="padding: 10px;display: none;" id="headquaterStoriesDiv">
                <div id="headquaterStoriesForm">
                    <div class="row">
                        <div class="col-sm-4">Region</div>
                        <div class="col-sm-8">
                            <select class="form-control input-sm" name="" id="region3-select">
                                <option value="">-All-</option>
                                <option value="AL">Alabama</option>
                                <option value="Am">Amalapuram</option>
                                <option value="An">Anakapalli</option>
                                <option value="Ak">Akkayapalem</option>
                                <option value="WY">Wyoming</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-4">City</div>
                        <div class="col-sm-8">
                            <select class="form-control input-sm" name="" id="city3-select">
                                <option value="">-All-</option>
                                <option value="AL">Alabama</option>
                                <option value="Am">Amalapuram</option>
                                <option value="An">Anakapalli</option>
                                <option value="Ak">Akkayapalem</option>
                                <option value="WY">Wyoming</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row competition-div" style="padding: 10px;display: none;" id="locationStoriesDiv">
                <div id="locationStoriesForm">
                    <div class="row">
                        <div class="col-sm-4">Region</div>
                        <div class="col-sm-8">
                            <select class="form-control input-sm" name="" id="region4-select">
                                <option value="">-All-</option>
                                <option value="AL">Alabama</option>
                                <option value="Am">Amalapuram</option>
                                <option value="An">Anakapalli</option>
                                <option value="Ak">Akkayapalem</option>
                                <option value="WY">Wyoming</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-4">City</div>
                        <div class="col-sm-8">
                            <select class="form-control input-sm" name="" id="city4-select">
                                <option value="">-All-</option>
                                <option value="AL">Alabama</option>
                                <option value="Am">Amalapuram</option>
                                <option value="An">Anakapalli</option>
                                <option value="Ak">Akkayapalem</option>
                                <option value="WY">Wyoming</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>--}}

            {{--<div class="row">--}}
            {{--<div class="col-sm-12 text-right clearfix">
                <button class="btn btn-sm"><i class="fa fa-undo"></i><strong> Reset</strong></button>
                <button class="btn btn-sm" type="submit" style="background-color: #003C61; color:white"><i class="fa fa-search"></i><strong>  Run Query</strong></button>
            </div>--}}
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

<div id="map"></div>

<div class="half-circle" style="box-shadow: 0.5px 5px 3px 3px #888;height: 40px;">
    {{--<div style="transform: rotate(270deg); background-color: #003D61;" class="badge" id="totalCountOfMarkers">0</div>--}}
    <div style="font-size: 14px; text-align: center; font-weight: 900; position: absolute; width: 100%;padding: 2px;">
        Total Records
    </div>
    <span style="font-size: 14px; text-align: center; color: black; font-weight: 900; position: absolute; top:19px; width: 100%;" class=" text-center" id="totalCountOfMarkers">
            Loading...
    </span>
</div>

{{--<div class="saveMap">--}}
    {{--<button class="btn btn-success" id="btnSave">Save Map</button>--}}
{{--</div>--}}

<!--Side Bar-->
<!-- End wrapper-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
{{--<script src="{{asset('/js/jquery.min.js')}}"></script>--}}
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script src="{{asset('/js/stories.js')}}"></script>
<script src="{{asset('/js/common.js')}}"></script>

<script src="{{asset('/js/markerClusterer.js')}}"></script>

<script src="https://cdn.jsdelivr.net/npm/measuretool-googlemaps-v3/lib/MeasureTool.min.js"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJo4u2hiqPKQbmW-2Hnd3ckPHBfyrHI7E&libraries=drawing,places,visualization&callback=initMap"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script src="{{asset('/js/gmap-nav.js')}}"></script>

<script src="{{asset('/js/html2canvas.js')}}"></script>

<script>
    var currentRoute = '{!!Route::currentRouteName()!!}';
    var sidebar = $('#sidebar').sidebar();
    var webUrl = "{{url('/')}}";

    $(function() {
        $("#btnSave").click(function() {

            html2canvas($("#map"), {
                useCORS: true,
                onrendered: function(canvas) {
                    var imageData= canvas.toDataURL("image/png");
                    //console.log(imageData);

                    $.ajax({
                        method: "POST",
                        url: webUrl+"/storiesmap/saveimagemap",
                        data: { imageData: imageData }
                    })
                    .done(function( msg ) {
                        console.log(msg);
                        // alert( "Data Saved: " + msg );
                    });

                }
            });

        });
    });
        setTimeout(function(){  $('#routeInfo').trigger( "click" ); }, 1500);

</script>

</body>
</html>
