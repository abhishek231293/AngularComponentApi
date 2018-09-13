var map;
var aoelogic = {};
aoelogic.markers = [];
aoelogic.markerCluster = [];
aoelogic.poiMarkerCluster = [];
var defaultTabId = 'bankStories';
var all_overlays = [];
var drawingManager;
var selectedShape;
var colors = ['#1E90FF', '#FF1493', '#32CD32', '#FF8C00'];
var selectedColor;
var colorButtons = {};
var markers = [];
var tabType = 'bankStories';
var type = 'bank';
var selectedRegion = 'kingdom_bank';

/* Function is used to show selected tab in sidebar and hide other all tab*/
function switchTab(tabId){
    $('input[name="poiName"]').prop('checked', false);
    tabType = tabId;
    $(".analysis-tab").removeClass("active");
    $("#"+tabId+'Tab').addClass("active");
    $(".competition-div").hide();
    $("#"+tabId+'Div').show();
    resetFiltersWithMapOnTabSwitch(tabId);
    buildColorPalette(type);
}

function resetFiltersWithMapOnTabSwitch(tabId){

    if(tabId == 'bankStories'){
        type = 'bank';
    }else if(tabId == 'atmStories'){
        type = 'atm';
    }
    resetDrawingSelection();
    deleteAllShape();
    drawingManager.setDrawingMode(null);

    var regionSelected = document.getElementById('kingdom_'+type);
    selectRegion(type,regionSelected);
    setMapCenter(23.8859,45.0792,6);

    deleteMarkers();
    placeMarkers('kingdom',type);
}

function resetDrawingSelection(){

    if(tabType == 'bankStories'){
        type = 'bank';
    }else if(tabType == 'atmStories'){
        type = 'atm';
    }

    $('#hexagon_'+type).css('border-color', 'white');
    $('#square_'+type).css('border-color', 'white');
    $('#circle_'+type).css('border-color', 'white');
    $('#distance_'+type).css('border-color', 'white');
}

function selectDrawingTool(type,element){

    var id = element.id;
    $(element).css('border-width', '4px');
    if(id == 'distance_'+type) {
        $(element).css('border-color', '#003D61');
        $('#hexagon_'+type).css('border-color', 'white');
        $('#square_'+type).css('border-color', 'white');
        $('#circle_'+type).css('border-color', 'white');
    } else if(id == 'hexagon_'+type) {
        $(element).css('border-color', '#003D61');
        $('#distance_'+type).css('border-color', 'white');
        $('#square_'+type).css('border-color', 'white');
        $('#circle_'+type).css('border-color', 'white');
    } else if(id == 'square_'+type) {
        $(element).css('border-color', '#003D61');
        $('#distance_'+type).css('border-color', 'white');
        $('#hexagon_'+type).css('border-color', 'white');
        $('#circle_'+type).css('border-color', 'white');
    } else if(id == 'circle_'+type) {
        $(element).css('border-color', '#003D61');
        $('#distance_'+type).css('border-color', 'white');
        $('#hexagon_'+type).css('border-color', 'white');
        $('#square_'+type).css('border-color', 'white');
    }
}

function selectRegion(type,element){

    if(element){
        resetCircleFilter();
        var id = element.id;
        selectedRegion = id;
        $(element).css('border-width', '4px');
    }else{
        $( "#"+selectedRegion ).trigger( "click" );
        return;
    }

    if(id == 'central_'+type) {
        $(element).css('border-color', '#003D61');
        $('#northern_'+type).css('border-color', 'aliceblue');
        $('#eastern_'+type).css('border-color', 'aliceblue');
        $('#western_'+type).css('border-color', 'aliceblue');
        $('#southern_'+type).css('border-color', 'aliceblue');
        $('#kingdom_'+type).css('border-color', 'aliceblue');

        $('#central_'+type+'_check').show();
        $('#eastern_'+type+'_check').hide();
        $('#western_'+type+'_check').hide();
        $('#southern_'+type+'_check').hide();
        $('#northern_'+type+'_check').hide();
        $('#kingdom_'+type+'_check').hide();

    } else if(id == 'northern_'+type) {
        $(element).css('border-color', '#003D61');
        $('#central_'+type).css('border-color', 'aliceblue');
        $('#eastern_'+type).css('border-color', 'aliceblue');
        $('#western_'+type).css('border-color', 'aliceblue');
        $('#southern_'+type).css('border-color', 'aliceblue');
        $('#kingdom_'+type).css('border-color', 'aliceblue');

        $('#central_'+type+'_check').hide();
        $('#eastern_'+type+'_check').hide();
        $('#western_'+type+'_check').hide();
        $('#southern_'+type+'_check').hide();
        $('#northern_'+type+'_check').show();
        $('#kingdom_'+type+'_check').hide();
    } else if(id == 'eastern_'+type) {
        $(element).css('border-color', '#003D61');
        $('#central_'+type).css('border-color', 'aliceblue');
        $('#northern_'+type).css('border-color', 'aliceblue');
        $('#western_'+type).css('border-color', 'aliceblue');
        $('#southern_'+type).css('border-color', 'aliceblue');
        $('#kingdom_'+type).css('border-color', 'aliceblue');

        $('#central_'+type+'_check').hide();
        $('#eastern_'+type+'_check').show();
        $('#western_'+type+'_check').hide();
        $('#southern_'+type+'_check').hide();
        $('#northern_'+type+'_check').hide();
        $('#kingdom_'+type+'_check').hide();
    } else if(id == 'western_'+type) {
        $(element).css('border-color', '#003D61');
        $('#central_'+type).css('border-color', 'aliceblue');
        $('#northern_'+type).css('border-color', 'aliceblue');
        $('#eastern_'+type).css('border-color', 'aliceblue');
        $('#southern_'+type).css('border-color', 'aliceblue');
        $('#kingdom_'+type).css('border-color', 'aliceblue');

        $('#central_'+type+'_check').hide();
        $('#eastern_'+type+'_check').hide();
        $('#western_'+type+'_check').show();
        $('#southern_'+type+'_check').hide();
        $('#northern_'+type+'_check').hide();
        $('#kingdom_'+type+'_check').hide();
    } else if(id == 'southern_'+type) {
        $(element).css('border-color', '#003D61');
        $('#central_'+type).css('border-color', 'aliceblue');
        $('#northern_'+type).css('border-color', 'aliceblue');
        $('#eastern_'+type).css('border-color', 'aliceblue');
        $('#western_'+type).css('border-color', 'aliceblue');
        $('#kingdom_'+type).css('border-color', 'aliceblue');

        $('#central_'+type+'_check').hide();
        $('#eastern_'+type+'_check').hide();
        $('#western_'+type+'_check').hide();
        $('#southern_'+type+'_check').show();
        $('#northern_'+type+'_check').hide();
        $('#kingdom_'+type+'_check').hide();
    } else if(id == 'kingdom_'+type) {
        $(element).css('border-color', '#003D61');
        $('#central_'+type).css('border-color', 'aliceblue');
        $('#northern_'+type).css('border-color', 'aliceblue');
        $('#eastern_'+type).css('border-color', 'aliceblue');
        $('#western_'+type).css('border-color', 'aliceblue');
        $('#southern_'+type).css('border-color', 'aliceblue');

        $('#central_'+type+'_check').hide();
        $('#eastern_'+type+'_check').hide();
        $('#western_'+type+'_check').hide();
        $('#southern_'+type+'_check').hide();
        $('#northern_'+type+'_check').hide();
        $('#kingdom_'+type+'_check').show();
    }
}

function drawRegionMarker(element,location,range){
    $('input[name="poiName"]').prop('checked', false);
    deletePoiMarkers();

    if(element != null){
        var id = element.id;
    }else{
        id = selectedRegion;
    }

    var options =  id.split("_");
    var region = options[0];
    var type = options[1];

    aoelogic.selectedZone = region;

    // setTimeout(function(){

    deleteMarkers();
    placeMarkers(region,type,location,range);
    // }, 1500);


}


function setMapCenter(lat,long,zoomLevel){
    setTimeout(function(){
        aoelogic.map.setZoom(zoomLevel);
    }, 1000);

    aoelogic.map.setZoom(4);
    // This will trigger a zoom_changed on the map
    aoelogic.map.setCenter(new google.maps.LatLng(lat, long));
}


function clearSelection () {
    if (selectedShape) {
        if (selectedShape.type !== 'marker') {
            selectedShape.setEditable(false);
        }

        selectedShape = null;
    }
}

function setSelection (shape) {
    if (shape.type !== 'marker') {
        clearSelection();
        shape.setEditable(true);
        selectColor(shape.get('fillColor') || shape.get('strokeColor'));
    }

    selectedShape = shape;
}

function deleteAllShape() {
    for (var i=0; i < all_overlays.length; i++)
    {
        all_overlays[i].overlay.setMap(null);
    }
    all_overlays = [];
}

function deleteSelectedShape (e) {
    if (selectedShape) {
        e.preventDefault();
        selectedShape.setMap(null);

    }else {
        resetDrawingSelection();
    }
    aoelogic.measureTool.end();
}
function selectColor (color) {
    selectedColor = color;
    for (var i = 0; i < colors.length; ++i) {
        var currColor = colors[i];
        colorButtons[currColor].style.border = currColor == color ? '2px solid #789' : '2px solid #fff';
    }
    // Retrieves the current options from the drawing manager and replaces the
    // stroke or fill color as appropriate.
    var polylineOptions = drawingManager.get('polylineOptions');
    polylineOptions.strokeColor = color;
    drawingManager.set('polylineOptions', polylineOptions);
    var rectangleOptions = drawingManager.get('rectangleOptions');
    rectangleOptions.fillColor = color;
    drawingManager.set('rectangleOptions', rectangleOptions);
    var circleOptions = drawingManager.get('circleOptions');
    circleOptions.fillColor = color;
    drawingManager.set('circleOptions', circleOptions);
    var polygonOptions = drawingManager.get('polygonOptions');
    polygonOptions.fillColor = color;
    drawingManager.set('polygonOptions', polygonOptions);
}

function setSelectedShapeColor (color) {
    if (selectedShape) {
        if (selectedShape.type == google.maps.drawing.OverlayType.POLYLINE) {
            selectedShape.set('strokeColor', color);
        } else {
            selectedShape.set('fillColor', color);
        }
    }
}

function makeColorButton (color) {
    var button = document.createElement('button');
    button.className = 'color-button btn btn-circle';
    button.style.backgroundColor = color;
    google.maps.event.addDomListener(button, 'click', function (e) {
        e.preventDefault();
        selectColor(color);
        setSelectedShapeColor(color);
    });
    return button;
}

function buildColorPalette (type) {

    var colorPalette = document.getElementById('color-palette_'+type);
    colorPalette.innerHTML = '';
    for (var i = 0; i < colors.length; ++i) {
        var currColor = colors[i];
        var colorButton = makeColorButton(currColor);
        colorPalette.appendChild(colorButton);
        colorButtons[currColor] = colorButton;
    }
    selectColor(colors[0]);
}

function changeDrawingMode(mode){

    if(mode == 'circle'){
        drawingManager.setDrawingMode(google.maps.drawing.OverlayType.CIRCLE);
    }else if(mode == 'square'){
        drawingManager.setDrawingMode(google.maps.drawing.OverlayType.RECTANGLE);
    }else if(mode == 'hexagon'){
        drawingManager.setDrawingMode(google.maps.drawing.OverlayType.POLYGON);
    }else {
        // drawingManager.setDrawingMode(google.maps.drawing.OverlayType.POLYLINE);

        aoelogic.measureTool = new MeasureTool(aoelogic.map, {
            showSegmentLength: true,
            contextMenu: false,
            // unit: MeasureTool.UnitTypeId.IMPERIAL,
            unit: MeasureTool.UnitTypeId.METRIC,
            strokeColor: '#FF0000',
        });

        /*aoelogic.measureTool.addListener('measure_start', function() {
         polygon.setOptions({clickable: false});
         });
         aoelogic.measureTool.addListener('measure_end', function() {
         polygon.setOptions({clickable: true});
         });*/

        aoelogic.measureTool.start();
    }

}

function placeMarker(position, map) {
    var marker = new google.maps.Marker({
        position: position,
        map: aoelogic.map
    });
    markers.push(marker);
    aoelogic.map.panTo(position);
}

function resetCircle(e){
    e.preventDefault();
}

function setMapOnAll(map) {
    for (var i = 0; i < aoelogic.markers.length; i++) {
        aoelogic.markers[i].setMap(map);
    }
}


function setMapOnAll(map) {
    for (var i = 0; i < aoelogic.markers.length; i++) {
        aoelogic.markers[i].setMap(map);
    }
}

function deleteMarkers() {
    setMapOnAll(null);
    aoelogic.markers = [];
    if(aoelogic.markerCluster.length != 0) {
        aoelogic.markerCluster.clearMarkers();
    }
}

function deletePoiMarkers() {
    if(aoelogic.poiMarkerCluster.length == undefined){
        aoelogic.poiMarkerCluster.clearMarkers();
    }
}

function drawMarkers(response,consolidateSearchType) {

    // console.log(response);
    var button, imagePath, h, w, code, atm_code, branchType, ownership, area, model, icon;

    var date = new Date();

    // InfoWindow content
    /*var icon = {
     url: '/img/bank_markers/alrajhi_logo.png', // url
     scaledSize: new google.maps.Size(32, 40), // scaled size
     origin: new google.maps.Point(0,0), // origin
     anchor: new google.maps.Point(0, 0) // anchor
     };*/

    // icon = '/img/bank_markers/alrajhi_logo.png';
    aoelogic.markers = [];
    $.each(response, function(idx, obj) {
        var icon = {};

        branchType = "'"+obj.type+"'";
        if(obj.code != null && ((!consolidateSearchType && type == 'bank') || consolidateSearchType == 'bank' )) {
            // button = '<a id="notifyOfficerBtn" onclick="goToBranchProfile(' + obj.code + ',' + branchType + ');" class="iw-botton" style="font-size: 12px; font-weight: 600; cursor: pointer"> Explore</a>';
        }else if(obj.atm_code != null && (!consolidateSearchType && type == 'atm') || (consolidateSearchType == 'atm')) {
            // button = '<a id="notifyOfficerBtn" onclick="goToATMProfile(' + obj.atm_code + ');" class="iw-botton" style="font-size: 12px; font-weight: 600; cursor: pointer"> Explore</a>';
        }
        if(obj.image_path) {
            imagePath = obj.image_path;
            h = 250;
            w = 187;
        } else {
            imagePath = '/img/atm_image.jpg';
            h = 250;
            w = 187;
        }

        if(obj.code != null) {
            code = obj.code;
        } else{
            code = 'NA';
        }

        if(obj.atm_code != null) {
            atm_code = obj.atm_code;
        } else{
            atm_code = 'NA';
        }

        if(obj.ownership) {
            ownership = obj.ownership;
        } else {
            ownership = 'NA';
        }

        if(obj.atm_model) {
            model = obj.atm_model;
        } else {
            model = 'NA';
        }

        if(obj.renewal_date) {
            var parts = obj.renewal_date.split("-");
            var renewal_date = new Date(parts[2], parts[1] - 1, parts[0]);

            if (date > renewal_date) {
                icon = {
                    url: '/img/bank_markers/alrajhi_logo_2.png', // url
                    scaledSize: new google.maps.Size(32, 40), // scaled size
                    origin: new google.maps.Point(0,0), // origin
                    anchor: new google.maps.Point(0, 0) // anchor
                };
            } else {
                icon = {
                    url: '/img/bank_markers/alrajhi_logo.png', // url
                    scaledSize: new google.maps.Size(32, 40), // scaled size
                    origin: new google.maps.Point(0,0), // origin
                    anchor: new google.maps.Point(0, 0) // anchor
                };
            }
        } else if(obj.poi_type == 'Hospital') {
            imagePath = '/img/icons/Hospital.png';
            h='150';
            w='';
            icon = {
                url: '/img/icons/Hospital.png', // url
                scaledSize: new google.maps.Size(25, 35), // scaled size
                origin: new google.maps.Point(0,0), // origin
                anchor: new google.maps.Point(0, 0) // anchor
            };
        } else if(obj.poi_type == 'University') {
            imagePath = '/img/icons/university.png';
            h='150';
            w='';
            icon = {
                url: '/img/icons/university.png', // url
                scaledSize: new google.maps.Size(25, 35), // scaled size
                origin: new google.maps.Point(0,0), // origin
                anchor: new google.maps.Point(0, 0) // anchor
            };
        } else if(obj.poi_type == 'Restaurant') {
            imagePath = '/img/icons/Restaurants.png';
            h='150';
            w='';
            icon = {
                url: '/img/icons/Restaurants.png', // url
                scaledSize: new google.maps.Size(25, 35), // scaled size
                origin: new google.maps.Point(0,0), // origin
                anchor: new google.maps.Point(0, 0) // anchor
            };
        } else {
            icon = {
                url: '/img/bank_markers/alrajhi_logo.png', // url
                scaledSize: new google.maps.Size(32, 40), // scaled size
                origin: new google.maps.Point(0,0), // origin
                anchor: new google.maps.Point(0, 0) // anchor
            };

        }

        if(obj.poi_type) {
            var contentString = '<div id="row" style="overflow: hidden; width: 185px;min-height: 353px;" >' +
                '<div class="row">' +
                '<div class="iw-subTitle" style="text-align: center;color: #fff;font-size: 20px; background-color: #003D61;">' + obj.poi_type + '</div>' +
                '<div class="iw-content" style="overflow: hidden;">' +
                '<div class="col-lg-12" style="padding: 0px;">' +
                '<div class="col-lg-12" style="padding: 0px;"><img class="pull-left" src="' + imagePath + '" height="' + h + '" width="' + w + '" style="margin-left: 37px;"></div>';
            contentString += '<div class="col-lg-12" style="margin-top: 10px;">' +
                '<div style="font-weight: 900; color: #003c61;">Name: <span style="padding: 0px; color: #000;font-weight: 100;">' + obj.name + '</span></div>'+
                '<div style="font-weight: 900; color: #003c61;">Address: <span style="padding: 0px; color: #000;font-weight: 100;">' + obj.address + '</span></div>';
        } else {
            if ((!consolidateSearchType && type == 'bank') || (consolidateSearchType == 'bank') ) {

                var contentString = '<div id="row" style="overflow: hidden; width: 185px;min-height: 353px;" >' +
                    '<div class="row">' +
                    '<div class="iw-subTitle" style="text-align: center;color: #fff;font-size: 20px; background-color: #003D61;">Branch ' + code + '</div>' +
                    '<div class="iw-content" style="overflow: hidden;">' +
                    '<div class="col-lg-12" style="padding: 0px;">' +
                    '<div class="col-lg-12" style="padding: 0px;"><img class="pull-left" src="' + imagePath + '" height="' + h + '" width="' + w + '" style=""></div>';
                contentString += '<div class="col-lg-12" style="margin-top: 10px;">' +
                    '<div style="font-weight: 900; color: #003c61;">Name: <span style="padding: 0px; color: #000;font-weight: 100;">' + obj.name + '</span></div>'+
                    '<div style="font-weight: 900; color: #003c61;">Type: <span style="padding: 0px; color: #000;font-weight: 100;">' + obj.type + '</span></div>'+
                    '<div style="font-weight: 900; color: #003c61;">Ownership: <span style="padding: 0px; color: #000;font-weight: 100;">' + ownership + '</span></div>';
            } else {
                var contentString = '<div id="row" style="overflow: hidden; width: 185px;min-height: 353px;" >' +
                    '<div class="row">' +
                    '<div class="iw-subTitle" style="text-align: center;color: #fff;font-size: 20px; background-color: #003D61;">ATM ' + atm_code + '</div>' +
                    '<div class="iw-content" style="overflow: hidden;">' +
                    '<div class="col-lg-12" style="padding: 0px;">' +
                    '<div class="col-lg-12" style="padding: 0px;"><img class="pull-left" src="' + imagePath + '" height="' + h + '" width="' + w + '" style=""></div>';
                contentString += '<div class="col-lg-12" style="margin-top: 10px;">' +
                    '<div style="font-weight: 900; color: #003c61;">Site: <span style="padding: 0px; color: #000;font-weight: 100;">' + obj.site + '</span></div>'+
                    '<div style="font-weight: 900; color: #003c61;">Location: <span style="padding: 0px; color: #000;font-weight: 100;">' + obj.site_location + '</span></div>'+
                    '<div style="font-weight: 900; color: #003c61;">Model: <span style="padding: 0px; color: #000;font-weight: 100;">' + model + '</span></div>';
            }
            if(button) {
                contentString += '<div style="margin-top: 5%; margin-left: -15%; " class="col-lg-12">'+button+'</div>'+
                    '<div class="col-lg-12"></div>'+
                    '<div class="col-lg-12"></div>'+
                    '<div class="col-lg-12"></div>';
            }

            contentString += '</div></div>';
        }


        var infowindow = new google.maps.InfoWindow({
            content: contentString,
            minWidth: 342

        });
        // var position = new google.maps.LatLng(obj.latitude,obj.longitude);
        var myLatLng = {lat: parseFloat(obj.latitude), lng: parseFloat(obj.longitude)};
        var storiesMarker =  new google.maps.Marker({
            position: myLatLng,
            icon: icon,
            // animation: google.maps.Animation.BOUNCE,
            draggable: false,
            //map: aoelogic.map
        });
        google.maps.event.addListener(storiesMarker, 'click', function() {
            // setTimeout(function() {
            //     aoelogic.map.setZoom(8);
            // },500);
            // aoelogic.map.setZoom(6);
            aoelogic.map.setCenter(storiesMarker.getPosition());
            aoelogic.infowindow.setContent(contentString);
            aoelogic.infowindow.open(aoelogic.map, storiesMarker);
        });
        // console.log(aoelogic.markers);
        // console.log(obj.latitude, obj.longitude);
        aoelogic.markers.push(storiesMarker);

    });

    if(aoelogic.markers){
        aoelogic.markerCluster = new MarkerClusterer(aoelogic.map, aoelogic.markers,
            {imagePath: webUrl+'/img/clusters/m'});
    }

    setDynamicCount(response, true);

    removeLoader();
    showCountOfMarkers(aoelogic.markers.length);

}

/*function drawPoiMarkers(response) {

 var icon, imagePath, h, w, code, atm_code, branchType, ownership, area, model;

 // InfoWindow content

 // icon = '/img/bank_markers/alrajhi_logo.png';
 aoelogic.poiMarkers = [];
 $.each(response, function(idx, obj) {
 if(obj.type == 'Hospital') {
 imagePath = '/img/icons/Hospital.png';
 icon = {
 url: '/img/icons/Hospital.png', // url
 scaledSize: new google.maps.Size(25, 35), // scaled size
 origin: new google.maps.Point(0,0), // origin
 anchor: new google.maps.Point(0, 0) // anchor
 };
 } else if(obj.type == 'University') {
 imagePath = '/img/icons/university.png';
 icon = {
 url: '/img/icons/university.png', // url
 scaledSize: new google.maps.Size(25, 35), // scaled size
 origin: new google.maps.Point(0,0), // origin
 anchor: new google.maps.Point(0, 0) // anchor
 };
 } else if(obj.type == 'Restaurant') {
 imagePath = '/img/icons/Restaurants.png';
 icon = {
 url: '/img/icons/Restaurants.png', // url
 scaledSize: new google.maps.Size(25, 35), // scaled size
 origin: new google.maps.Point(0,0), // origin
 anchor: new google.maps.Point(0, 0) // anchor
 };
 }

 var contentString = '<div id="iw-container">' +
 '<div class="">' +
 '<div class="iw-subTitle" style="text-align: center;color: #003c61;font-size: 20px;">' + obj.type + '</div>' +
 '<div class="iw-content" style="overflow: hidden;">' +
 '<div class="col-lg-6">' +
 '<img class="pull-left" src="' + imagePath + '" height="' + h + '" width="' + w + '" style="margin-left: -23px;"></div>';
 contentString += '<div class="col-lg-6" style="padding: 0px 0px;">' +
 '<strong style="color: #003c61;">Name:</strong> ' + obj.name + '<br><br><strong style="color: #003c61;">Address:</strong> ' + obj.address + '';
 contentString += '</div></div>';


 var infowindow = new google.maps.InfoWindow({
 content: contentString,
 minWidth: 342

 });
 // var position = new google.maps.LatLng(obj.latitude,obj.longitude);
 var myLatLng = {lat: parseFloat(obj.latitude), lng: parseFloat(obj.longitude)};
 var storiesMarker =  new google.maps.Marker({
 position: myLatLng,
 icon: icon,
 // animation: google.maps.Animation.BOUNCE,
 draggable: false,
 //map: aoelogic.map
 });
 google.maps.event.addListener(storiesMarker, 'click', function() {
 // setTimeout(function() {
 //     aoelogic.map.setZoom(8);
 // },500);
 // aoelogic.map.setZoom(6);
 aoelogic.map.setCenter(storiesMarker.getPosition());
 aoelogic.infowindow.setContent(contentString);
 aoelogic.infowindow.open(aoelogic.map, storiesMarker);
 });
 // console.log(aoelogic.markers);
 // console.log(obj.latitude, obj.longitude);
 aoelogic.poiMarkers.push(storiesMarker);

 });

 if(aoelogic.poiMarkers){
 aoelogic.poiMarkerCluster = new MarkerClusterer(aoelogic.map, aoelogic.poiMarkers,
 {imagePath: '/img/clusters/m'});
 }

 setDynamicCount(response, true);
 removeLoader();
 showCountOfMarkers(aoelogic.markers.length);

 }*/

function goToBranchProfile(code, branchType) {
    window.open('/branches-profile/'+code+'&type='+branchType, '_blank');
}

function goToATMProfile(code) {
    window.open('/atm-profile/'+code, '_blank');
}

function showCountOfMarkers(total){
    $('#totalCountOfMarkers').html(total);
    $('#typeOfExternalTab').html(type);
}

function placeMarkers(region,tabType,location,range){

    showLoader();
    showCountOfMarkers(0);

    if(tabType == null){
        tabType = type;
    }

    var lat = null;
    var lng = null;


    if(location != null){
        lat = location.lat();
        lng = location.lng();
    }

    if(tabType == 'atm') {

        $.ajax({
            type: "POST",
            url: webUrl+'/storiesmap/get-atm-details',
            data: {
                'zone': region,
                'lat':lat,
                'lng':lng,
                'range':range
            },
            success: function (response) {
                if (response.status == 'success') {

                    aoelogic.atmDetails = response.data;
                    // deleteMarkers();
                    drawMarkers(aoelogic.atmDetails);


                }
            }
        });
    }else if(tabType == 'bank'){
        $.ajax({
            type: "POST",
            url: webUrl+'/storiesmap/get-branch-details',
            data: {
                'zone': region,
                'lat':lat,
                'lng':lng,
                'range':range
            },
            success: function (response) {
                if (response.status == 'success') {

                    aoelogic.branchDetails = response.data;
                    // console.log(aoelogic.branchDetails);
                    // deleteMarkers();
                    drawMarkers(aoelogic.branchDetails);


                }
            }
        });
    }

}

/*function setPopUp() {
 google.maps.event.addListener(aoelogic.infowindow, 'domready', function() {


 var iwOuter = $('.gm-style-iw');
 //
 var iwBackground = iwOuter.prev();
 //
 //
 iwBackground.children(':nth-child(2)').css({'display' : 'none'});
 //
 //
 iwBackground.children(':nth-child(4)').css({'display' : 'none'});
 //
 //
 // iwOuter.parent().parent().css({left: '115px'});
 //
 //
 // iwBackground.children(':nth-child(3)').attr('style', function(i,s){ return s + 'left: 76px !important;'});
 //
 // iwBackground.children(':nth-child(3)').find('div').children().css({'box-shadow': 'rgba(72, 181, 233, 0.6) 0px 1px 6px', 'z-index' : '1'});
 //
 var iwCloseBtn = iwOuter.next();
 //
 iwCloseBtn.css({
 opacity: '1', // by default the close button has an opacity of 0.7
 right: '30px', top: '22px', // button repositioning
 // border: '7px solid #48b5e9', // increasing button border and new color
 // 'border-radius': '13px', // circular effect
 // 'box-shadow': '0 0 5px #3990B9' // 3D effect to highlight the button
 });
 //
 iwCloseBtn.mouseout(function(){
 $(this).css({opacity: '1'});
 });
 });
 }*/

window.initMap = function(){

    aoelogic.trafficLayer = new google.maps.TrafficLayer();

    directionsDisplay = new google.maps.DirectionsRenderer();

    aoelogic.map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 23.8859, lng: 45.0792},
        mapTypeControl: true,
        mapTypeControlOptions: {
            style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
            position: google.maps.ControlPosition.RIGHT_BOTTOM
        },
        zoomControl: true,
        zoomControlOptions: {
            position: google.maps.ControlPosition.RIGHT_TOP
        },
        scaleControl: true,
        streetViewControl: true,
        streetViewControlOptions: {
            position: google.maps.ControlPosition.RIGHT_BOTTOM
        },
        fullscreenControl: true
    });

    directionsDisplay.setMap(aoelogic.map);
    var request = {
        travelMode: google.maps.TravelMode.DRIVING
    };
    google.maps.event.addListener(aoelogic.map, 'click', function(e) {
        // placeMarker(e.latLng, map);
    });
    aoelogic.trafficLayer.setMap(aoelogic.map);


    var lineSymbol = {
        path: google.maps.SymbolPath.CIRCLE,
        fillOpacity: 1,
        scale: 3
    };
    var polylineDotted = {
        strokeColor: 'red',
        strokeOpacity: 0,
        fillOpacity: 0,
        icons: [{
            icon: lineSymbol,
            offset: '0',
            repeat: '18px'
        }],
    };
    var rendererOptions = {
        map: aoelogic.map,
        suppressMarkers: false,
        polylineOptions: polylineDotted
    };

    aoelogic.directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
    aoelogic.directionsDisplay.setMap(this.map);

    aoelogic.infowindow = new google.maps.InfoWindow();

    var polyOptions = {
        strokeWeight: 0,
        fillOpacity: 0.45,
        editable: true,
        draggable: true
    };

    // Creates a drawing manager attached to the map that allows the user to draw
    // markers, lines, and shapes.
    drawingManager = new google.maps.drawing.DrawingManager({
        markerOptions: {
            draggable: true
        },
        drawingControlOptions: {
            position: google.maps.ControlPosition.BOTTOM_RIGHT,
            drawingModes: ['circle', 'polygon', 'polyline', 'rectangle']
        },
        polylineOptions: {
            editable: true,
            draggable: true
        },
        rectangleOptions: polyOptions,
        circleOptions: polyOptions,
        polygonOptions: polyOptions,
        map: aoelogic.map
    });

    drawingManager.setOptions({ zIndex: -1 });

    google.maps.event.addListener(drawingManager, 'overlaycomplete', function (e) {
        var newShape = e.overlay;
        all_overlays.push(e);
        newShape.type = e.type;

        if (e.type !== google.maps.drawing.OverlayType.MARKER) {

            // Switch back to non-drawing mode after drawing a shape.
            drawingManager.setDrawingMode(null);
            // Add an event listener that selects the newly-drawn shape when the user
            // mouses down on it.
            google.maps.event.addListener(newShape, 'click', function (e) {
                if (e.vertex !== undefined) {
                    if (newShape.type === google.maps.drawing.OverlayType.POLYGON) {
                        var path = newShape.getPaths().getAt(e.path);
                        path.removeAt(e.vertex);
                        if (path.length < 3) {
                            newShape.setMap(null);
                        }
                    }
                    if (newShape.type === google.maps.drawing.OverlayType.POLYLINE) {
                        var path = newShape.getPath();
                        path.removeAt(e.vertex);
                        if (path.length < 2) {
                            newShape.setMap(null);
                        }
                    }
                }
                setSelection(newShape);
            });
            setSelection(newShape);

            resetDrawingSelection();
        }else {
            google.maps.event.addListener(newShape, 'click', function (e) {
                setSelection(newShape);
            });
            setSelection(newShape);
        }
    });

    // var ctaLayer1 = new google.maps.KmlLayer('https://raw.githubusercontent.com/abhishek231293/power/master/dist/Power_Station.kml');
    // ctaLayer1.setMap(aoelogic.map);
    consolidateSearchType = '';
    if(tabType == 'bankStories'){
        type = 'bank';
    }else if(tabType == 'atmStories'){
        type = 'atm';
    }
    // setPopUp();
    // Clear the current selection when the drawing mode is changed, or when the
    // map is clicked.
    google.maps.event.addListener(drawingManager, 'drawingmode_changed', clearSelection);
    google.maps.event.addListener(aoelogic.map, 'click', clearSelection);
    google.maps.event.addDomListener(document.getElementById('delete-button_'+type), 'click', deleteSelectedShape);
    // buildColorPalette(type);
    // aoelogic.map = map;




}

$(document).ready(function() {
    $('.spin-icon').click(function () {
        $(".theme-config-box").toggleClass("show");
    });
    setTimeout(function(){
        $('.spin-icon').trigger('click');
    }, 1500);

});


$(window).load(function() {

    // document.getElementsByClassName('gmnoprint');
    switchTab(defaultTabId);

/*    var options = {
        componentRestrictions: {country: "SAU"}
    };

    var input = document.getElementById('address');
    var autocomplete = new google.maps.places.Autocomplete(input,options);

    var consolidatedSearch = document.getElementById('consolidatedSearch');
    var autocomplete = new google.maps.places.Autocomplete(consolidatedSearch,options);*/
})
/*

$('.poiName').on('change',function () {
    // showLoader();
    setMapCenter(23.8859,45.0792,6);
    var poiData = [];
    var url;
    $('input[name="poiName"]:checked').each(function()
    {
        poiData.push($(this).val());
    });

    if(type == 'atm') {
        url = '/stories/get-atm-details';
    }else if(type == 'bank'){
        url = '/stories/get-branch-details';
    }

    $.ajax({
        type: "POST",
        url: url,
        data: {
            'data' : poiData,
            'zone' : aoelogic.selectedZone
        },
        success: function (response) {
            if (response.status == 'success') {

                aoelogic.poiDetails = response.data;
                deleteMarkers();
                drawMarkers(aoelogic.poiDetails);


            }
        }
    });
});*/
