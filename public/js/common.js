var circle;
var geocoder;
var circleMarker = [];
var heatmapVisibility = true;
var boundriesVisibility = true;
aoelogic.heatmap = null;
aoelogic.heatmapData = [];
var population = {};

$(window).load(function() {
    mapJsonLayer();
    activateHeatMap();
    $('#'+currentRoute+'Id').css({"color":"white"});
    $('#'+currentRoute+'Id').css({"font-weight":"900"});

    $(".holoform input[type=checkbox]").each(function(){

        var values = $(this).attr("checked")=='checked'?$(this).next('label').attr('data-on'):$(this).next('label').attr('data-off');

        message += $(this).attr("name") + " is " + values + "\r\n";
        alert(message);
    })
})

/*
document.getElementById('textInput').innerHTML = 1;

function updateTextInput(val) {
    document.getElementById('textInput').innerHTML = val;
}
*/

function showLoader(heatmapLoad) {
    /*if(!heatmapLoad) {
     $(".half-circle").animate({height: "0px"});
     }*/
    /*document.getElementById("map-data-go").disabled = true;
    document.getElementById("map-data-reset").disabled = true;*/
    document.getElementById("overlay").style.display = "block";
}

function removeLoader(heatmapLoad) {
    /*document.getElementById("map-data-go").disabled = false;
    document.getElementById("map-data-reset").disabled = false;*/
    document.getElementById("overlay").style.display = "none";
    if(!heatmapLoad) {
        setTimeout(function () {
            $(".half-circle").animate({height: "40px"}, 1000);
        }, 1000);
    }
}

function gisLogOut() {

    swal({
        title: 'Are you sure?',
        text: "You want to logout!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
    }).then(function(result) {
        if (result.value) {
            window.location = webUrl+'/logout';
        }
    });
}

var toggleFilters = function(that){

    /** -- reseting filters  ---  **/

    // resetMapData('#map-filters-div', 'map-data-go');
    /** removing active class from li  -- **/
    $(that).parent().find("li").each(function(){
        $(this).removeAttr('class');
    });

    $(that).addClass('active');  /** setting active to the selected ;i -- **/

    $("#selected_filters_type").html($(that).find('a').html());

    $(".drag-info").hide();

};

function resetCircleFilter(onResetButton){

    if(circle){
        for (i in circleMarker) {
            circleMarker[i].setMap(null);
        }
        circle.setMap(null);
        circle = null;

        $('#address').val("");
        $('#myRange').val(1);
        document.getElementById('textInput').innerHTML = 1;
        setMapCenter(23.8859,45.0792,6);

        if(onResetButton) {
            if (currentRoute == 'stories') {
                drawRegionMarker(null, null, null);
            } else if (currentRoute == 'analysis') {
                placeMarkers(null, null, null);
            } else if (currentRoute == 'competition') {
                placeMarkers(null, null, null);
            }
            else {
                runQueryCommon(null,'topBar',null);
            }
        }
        circleMarker = [];
    }

}

function drawCircle(e){

    e.preventDefault();
    for (i in circleMarker) {
        circleMarker[i].setMap(null);
    }
    if(circle){
        circle.setMap(null);
    }
    // var formData = $('#radiusSearchId').serializeArray();

    var formObj = {};
    formObj['address'] = document.getElementById('address').value;
    formObj['range'] = document.getElementById('myRange').value;

    if(formObj['address'] == '' || formObj['address'] == null){
        alert('Please enter an address');
        return;
    }

    geocoder = new google.maps.Geocoder();
    geocoder.geocode( { 'address': formObj['address']}, function(results, status) {

        if (status == 'OK') {
            aoelogic.map.setCenter(results[0].geometry.location);
            var icon = {
                url: '/img/marker/userMarker.png', // url
                scaledSize: new google.maps.Size(55, 50), // scaled size
            };
            var marker = new google.maps.Marker({
                map: aoelogic.map,
                // draggable:true,
                icon: icon,
                position: results[0].geometry.location,
                title: formObj['address']
            });
            circleMarker.push(marker);
            circle = new google.maps.Circle({
                map: aoelogic.map,
                radius: (formObj['range'])*1000,    // in metres
                fillColor: '#6A9CCD',
                fillOpacity: 0.5,
                strokeColor: "#200",
                strokeWeight: 0
            });
            circle.bindTo('center', marker, 'position');
            aoelogic.map.setCenter(results[0].geometry.location);

            zoomLevel =  (formObj['range'] < 2) ? 14 : (formObj['range'] > 2 && formObj['range'] < 10 ) ? 12 : (formObj['range'] > 10 && formObj['range'] < 30 ) ? 10 : 8 ;
            aoelogic.map.setZoom(zoomLevel);

            if(currentRoute == 'stories'){
                drawRegionMarker(null,results[0].geometry.location,formObj['range']);
            }else if(currentRoute == 'analysis'){
                placeMarkers(null,results[0].geometry.location,formObj['range']);
            }else if(currentRoute == 'competition'){
                placeMarkers(null,results[0].geometry.location,formObj['range']);
            } else {
                formObj['locationDet'] = results[0].geometry.location;
                runQueryCommon(null,'topBar',formObj);
            }

        } else {
            console.log('Geocode was not successful for the following reason: ' + status);
        }
    });
}

function getCurrentLocation()
{
    if( navigator.geolocation )
    {
// Call getCurrentPosition with success and failure callbacks
        navigator.geolocation.getCurrentPosition( success, fail );
    }
    else
    {
        alert("Sorry, your browser does not support geolocation services.");
    }
}

function success(position)
{
    $long = position.coords.longitude;
    $lat = position.coords.latitude;
    GetAddress($lat,$long);
}

function fail()
{
    alert('Oops, Something went wrong!!!');
}

function SwitchSideBar() {
    // setTimeout(function(){
    $('#routeInfo').trigger( "click" );
    $('.consolidated-search').val("");
    // }, 500);
}

function GetAddress($lat,$long) {
    var lat = $lat;
    var lng = $long;
    var latlng = new google.maps.LatLng(lat, lng);
    var geocoder = geocoder = new google.maps.Geocoder();
    geocoder.geocode({ 'latLng': latlng }, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            if (results.length) {
                $("#address").val(results[0].formatted_address);
                $('#map-data-go').trigger('click');
            }else{
                alert('Sorry, browser cannot track your location.')
            }
        }
    });
}

function deactivateMapJsonLayer() {
    aoelogic.map.data.forEach(function (feature) {
        aoelogic.map.data.remove(feature);
    });
}

function mapJsonLayer(switchButton) {

    if(switchButton){
        showLoader(true);
    }
    boundriesVisibility = !boundriesVisibility;

    if(!boundriesVisibility){
        $('#boundriesLabelDiv').hide();
        deactivateMapJsonLayer();
        if(switchButton){
            removeLoader();
        }
        return;
    }

    var promise = $.getJSON("saudi.json"); //same as map.data.loadGeoJson();
    promise.then(function(data){
        cachedGeoJson = data; //save the geojson in case we want to update its values
        aoelogic.map.data.addGeoJson(cachedGeoJson,{idPropertyName:"id"});
    });
    colorValues = {
        'Al Bahah Region':"#ffdac2", //1
        'Al Jawf Region':"#ffdac2", //2
        'Al Madinah Region':"#ffdac2", //3
        'Al-Qassim Region':"#ffdac2", //4
        'Asir Region':"#fd6500", //5
        'Eastern Region':"#d55500", //6
        'Jazan Region':"#ffdac2", //6
        'Makkah Region':"#ff8839", //6
        'Najran Region':"#ffb787", //6
        'Northern Borders Region':"#ffdac2", //6
        'Riyadh Region':"#ff8839", //6
        'Tabuk Region':"#ffdac2", //6
        'Hayel Region':"#ffdac2" //6
    };

    aoelogic.map.data.setStyle({
        fillColor: 'transparent',
        strokeWeight: 0.5,
        visible: true,
        fillOpacity:0.2
    });

    var setColorStyleFn = function(feature) {
        if(feature.f.name){
            return {
                fillColor: colorValues[feature.f.name],
                strokeWeight: 1,
                visible: true,
                fillOpacity:0.4
            };

        }
    }

    // Set the stroke width, and fill color for each polygon, with default colors at first
    aoelogic.map.data.setStyle(setColorStyleFn);
    // $('#boundriesLabelDiv').show();

    if(switchButton){
        removeLoader(true);
    }

}

function consolidatedSearch() {

    var userInput = $('#consolidatedSearch').val().trim();
    if(userInput == undefined || userInput == '' || userInput == null){
        return;
    }

    if(parseInt(userInput, 10) == userInput){

        $.ajax({
            type: "POST",
            url: '/storiesmap/consolidated-search',
            data: {
                'search-input': userInput,
                'search-for' : 'code',
                'currentRoute' : currentRoute
            },
            success: function (response) {
                if (response.status == 'success') {
                    showLoader();
                    deleteMarkers();

                    if(currentRoute == 'analysis' || currentRoute == 'assets'){
                        var totalData = response.data.allData.length;
                    }else{
                        var totalData = response.data.length;
                    }
                    var typeReturn = '';
                    if(totalData){

                        if(currentRoute == 'analysis' || currentRoute == 'assets'){
                            var latitude = response.data.allData[0].latitude;
                            var longitude = response.data.allData[0].longitude;
                        }else{
                            var latitude = response.data[0].latitude;
                            var longitude = response.data[0].longitude;
                        }
                        if(response.type == 'Branch'){
                            typeReturn = 'bank';
                        }else{
                            typeReturn = 'atm';
                        }
                        setMapCenter(latitude,longitude,7);
                        setTimeout(function(){
                            setMapCenter(latitude,longitude,9);
                        }, 500);

                    }else{
                        alert('No Bank or ATM found for given input');
                        setMapCenter(23.8859,45.0792,6);
                    }

                    drawMarkers(response.data,typeReturn);
                    // setMapCenter();


                }
            }
        });
    }else{

        geocoder = new google.maps.Geocoder();
        var request = {
            address: userInput
        }
        geocoder.geocode( request, function(results, status) {

            var type;
            if (status == 'OK') {
                type = 'address';
                var lat = results[0].geometry.location.lat();
                var lng = results[0].geometry.location.lng();
                setMapCenter(lat,lng,14);
            }else{
                alert('No Bank or ATM found for given input');
                setMapCenter(23.8859,45.0792,6);
            }

        });
    }

}

/* Heat Map Code Start */

function deactivateHeatMap() {
    if (aoelogic.heatmap){
        aoelogic.heatmap.setMap(null);
    }
};
function activateHeatMap(switchButton){

    if(switchButton){
        showLoader(true);
    }

    heatmapVisibility = !heatmapVisibility;
    if(!heatmapVisibility){
        deactivateHeatMap();
        if(switchButton){
            removeLoader();
        }
        return;
    }

    $.ajax({
        type: "POST",
        url: 'get-population-data',
        data: {
            // 'zone': region,
            // 'lat':lat,
            // 'lng':lng,
            // 'range':range
        },
        success: function (response) {
            if (response.status == 'success') {

                var radiusWeight = 0;
                population = {};

                deactivateHeatMap();

                var PopulationData = response.data;
                $.each(PopulationData, function(index, value){
                    //console.log(me.getHeatMapWeight(value['population']));
                    var point = {
                        location : new google.maps.LatLng(value['latitude'], value['longitude']),
                        weight : (Number(value['population'])) ? getHeatMapWeight(value['population']) : 100
                        //weight : Number(me.getTotalHeatMapWeight(value))
                    };
                    aoelogic.heatmapData.push(point);
                    if (point.weight > 0) {
                        radiusWeight = 70
                    }
                });

                // fillPopulation();

                aoelogic.heatmap = new google.maps.visualization.HeatmapLayer({
                    data: aoelogic.heatmapData,
                    radius: radiusWeight
                });
                aoelogic.heatmap.setMap(aoelogic.map);
                if(switchButton){
                    removeLoader(true);
                }
            }
        }
    });
};

function setDynamicCount(response,hasMarker) {
    google.maps.event.addListener(aoelogic.map, 'idle', function() {
        $('#totalCountOfMarkers').text('Loading...');
        var totalVisibleMarkers = 0;
        var counterData = {};
        var visibleArray = [];
        for (var i = 0; i < aoelogic.markers.length; i++) {
            if (aoelogic.map.getBounds().contains(aoelogic.markers[i].getPosition())) {
                visibleArray.push(aoelogic.markers[i]);
                totalVisibleMarkers++;
                if (!counterData[aoelogic.markers[i].name]) {
                    counterData[aoelogic.markers[i].name] = 1;
                } else {
                    counterData[aoelogic.markers[i].name]++;
                }
                if(hasMarker !== undefined && hasMarker==false) {
                    aoelogic.markers[i].setMap(aoelogic.map);
                }

            } else {
                aoelogic.markers[i].setMap(null);
            }
        }
        if(hasMarker !== undefined && hasMarker==true){
            aoelogic.markerCluster.repaint();
        }
        consolidateSearchType = '';
        // result counter
        $('#totalCountOfMarkers').text(visibleArray.length)
    });
}

function getHeatMapWeight(population){
    var populationDigitCount = Number((population).trim().length);
    var stanadardPopulation = 1;
    for(var i = 1; i < populationDigitCount; i++){
        stanadardPopulation = (stanadardPopulation * 10);
    }
    return Number((Number(population) / stanadardPopulation).toFixed(2));
};

function clearHeatMap(){
    aoelogic.heatmap.setMap(null);
}


/* Heat Map Code End */

function hijriToEnglish(element) {
    if(element == 'profile') {
        var profilebtnText = $('.profile').text();
        var profileIssuanceDateArray = $('.dateResult_profilePurchase').text();
        var profileRenewDateArray = $('.dateResult_profileEval').text();
        var profileIssuanceDateSplit = profileIssuanceDateArray.split("-");
        var profileRenewDateSplit = profileRenewDateArray.split("-");
        if(profilebtnText == 'Hijri') {
            var profileIssuanceDateUrl = "http://www.hebcal.com/converter/?cfg=json&gy=" + profileIssuanceDateSplit[2] + "&gm=" + profileIssuanceDateSplit[1] + "&gd=" + profileIssuanceDateSplit[0] + "&g2h=1";
            var profileRenewDateUrl = "http://www.hebcal.com/converter/?cfg=json&gy=" + profileRenewDateSplit[2] + "&gm=" + profileRenewDateSplit[1] + "&gd=" + profileRenewDateSplit[0] + "&g2h=1";
        } else if(profilebtnText == 'English') {
            var profileIssuanceDateUrl = "http://www.hebcal.com/converter/?cfg=json&hy=" + profileIssuanceDateSplit[2] + "&hm=" + profileIssuanceDateSplit[1] + "&hd=" + profileIssuanceDateSplit[0] + "&h2g=1";
            var profileRenewDateUrl = "http://www.hebcal.com/converter/?cfg=json&hy=" + profileRenewDateSplit[2] + "&hm=" + profileRenewDateSplit[1] + "&hd=" + profileRenewDateSplit[0] + "&h2g=1";
        }
        $.ajax({
            type: "GET",
            url: profileIssuanceDateUrl,
            cache: false,
            processData: false,
            contentType: false,
            success: function(response) {
                if (profilebtnText == 'Hijri') {
                    $('.dateResult_profilePurchase').text(response.hd + '-' + response.hm + '-' + response.hy);
                } else if (profilebtnText == 'English') {
                    $('.dateResult_profilePurchase').text(response.gd + '-' + response.gm + '-' + response.gy);
                }
            }
        });

        $.ajax({
            type: "GET",
            url: profileRenewDateUrl,
            cache: false,
            processData: false,
            contentType: false,
            success: function(response) {
                if (profilebtnText == 'Hijri') {
                    $('.dateResult_profileEval').text(response.hd + '-' + response.hm + '-' + response.hy);
                    $('.profile').text('English');
                } else if (profilebtnText == 'English') {
                    $('.dateResult_profileEval').text(response.gd + '-' + response.gm + '-' + response.gy);
                    $('.profile').text('Hijri');
                }
            }
        });
    }

    if(element == 'baladiya') {
        var btnText = $('.baladiya').text();
        var issuanceDateArray = $('.dateResult_baladiyaIssuance').text();
        var renewDateArray = $('.dateResult_baladiyaRenew').text();
        var issuanceDateSplit = issuanceDateArray.split("-");
        var renewDateSplit = renewDateArray.split("-");
        if(btnText == 'Hijri') {
            var issuanceDateUrl = "http://www.hebcal.com/converter/?cfg=json&gy=" + issuanceDateSplit[2] + "&gm=" + issuanceDateSplit[1] + "&gd=" + issuanceDateSplit[0] + "&g2h=1";
            var renewDateUrl = "http://www.hebcal.com/converter/?cfg=json&gy=" + renewDateSplit[2] + "&gm=" + renewDateSplit[1] + "&gd=" + renewDateSplit[0] + "&g2h=1";
        } else if(btnText == 'English') {
            var issuanceDateUrl = "http://www.hebcal.com/converter/?cfg=json&hy=" + issuanceDateSplit[2] + "&hm=" + issuanceDateSplit[1] + "&hd=" + issuanceDateSplit[0] + "&h2g=1";
            var renewDateUrl = "http://www.hebcal.com/converter/?cfg=json&hy=" + renewDateSplit[2] + "&hm=" + renewDateSplit[1] + "&hd=" + renewDateSplit[0] + "&h2g=1";
        }
        $.ajax({
            type: "GET",
            url: issuanceDateUrl,
            cache: false,
            processData: false,
            contentType: false,
            success: function(response) {
                if (btnText == 'Hijri') {
                    $('.dateResult_baladiyaIssuance').text(response.hd + '-' + response.hm + '-' + response.hy);
                } else if (btnText == 'English') {
                    $('.dateResult_baladiyaIssuance').text(response.gd + '-' + response.gm + '-' + response.gy);
                }
            }
        });

        $.ajax({
            type: "GET",
            url: renewDateUrl,
            cache: false,
            processData: false,
            contentType: false,
            success: function(response) {
                if (btnText == 'Hijri') {
                    $('.dateResult_baladiyaRenew').text(response.hd + '-' + response.hm + '-' + response.hy);
                    $('.baladiya').text('English');
                } else if (btnText == 'English') {
                    $('.dateResult_baladiyaRenew').text(response.gd + '-' + response.gm + '-' + response.gy);
                    $('.baladiya').text('Hijri');
                }
            }
        });
    }

    if(element == 'civil') {
        var civilBtnText = $('.civil').text();
        var civilIssuanceDateArray = $('.dateResult_civilIssuance').text();
        var civilissuanceDateSplit = civilIssuanceDateArray.split("-");
        if(civilBtnText == 'Hijri') {
            var civilissuanceDateUrl = "http://www.hebcal.com/converter/?cfg=json&gy=" + civilissuanceDateSplit[2] + "&gm=" + civilissuanceDateSplit[1] + "&gd=" + civilissuanceDateSplit[0] + "&g2h=1";
        } else if(civilBtnText == 'English') {
            var civilissuanceDateUrl = "http://www.hebcal.com/converter/?cfg=json&hy=" + civilissuanceDateSplit[2] + "&hm=" + civilissuanceDateSplit[1] + "&hd=" + civilissuanceDateSplit[0] + "&h2g=1";
        }
        $.ajax({
            type: "GET",
            url: civilissuanceDateUrl,
            cache: false,
            processData: false,
            contentType: false,
            success: function(response) {
                if (civilBtnText == 'Hijri') {
                    $('.dateResult_civilIssuance').text(response.hd + '-' + response.hm + '-' + response.hy);
                    $('.civil').text('English');
                } else if (civilBtnText == 'English') {
                    $('.dateResult_civilIssuance').text(response.gd + '-' + response.gm + '-' + response.gy);
                    $('.civil').text('Hijri');
                }
            }
        });
    }

    if(element == 'sijil') {
        var sijilbtnText = $('.sijil').text();
        var sijilissuanceDateArray = $('.dateResult_sijilIssuance').text();
        var sijilrenewDateArray = $('.dateResult_sijilRenew').text();
        var sijilissuanceDateSplit = sijilissuanceDateArray.split("-");
        var sijilrenewDateSplit = sijilrenewDateArray.split("-");
        if(sijilbtnText == 'Hijri') {
            var sijilissuanceDateUrl = "http://www.hebcal.com/converter/?cfg=json&gy=" + sijilissuanceDateSplit[2] + "&gm=" + sijilissuanceDateSplit[1] + "&gd=" + sijilissuanceDateSplit[0] + "&g2h=1";
            var sijilrenewDateUrl = "http://www.hebcal.com/converter/?cfg=json&gy=" + sijilrenewDateSplit[2] + "&gm=" + sijilrenewDateSplit[1] + "&gd=" + sijilrenewDateSplit[0] + "&g2h=1";
        } else if(sijilbtnText == 'English') {
            var sijilissuanceDateUrl = "http://www.hebcal.com/converter/?cfg=json&hy=" + sijilissuanceDateSplit[2] + "&hm=" + sijilissuanceDateSplit[1] + "&hd=" + sijilissuanceDateSplit[0] + "&h2g=1";
            var sijilrenewDateUrl = "http://www.hebcal.com/converter/?cfg=json&hy=" + sijilrenewDateSplit[2] + "&hm=" + sijilrenewDateSplit[1] + "&hd=" + sijilrenewDateSplit[0] + "&h2g=1";
        }
        $.ajax({
            type: "GET",
            url: sijilissuanceDateUrl,
            cache: false,
            processData: false,
            contentType: false,
            success: function(response) {
                if (sijilbtnText == 'Hijri') {
                    $('.dateResult_sijilIssuance').text(response.hd + '-' + response.hm + '-' + response.hy);
                } else if (sijilbtnText == 'English') {
                    $('.dateResult_sijilIssuance').text(response.gd + '-' + response.gm + '-' + response.gy);
                }
            }
        });

        $.ajax({
            type: "GET",
            url: sijilrenewDateUrl,
            cache: false,
            processData: false,
            contentType: false,
            success: function(response) {
                if (sijilbtnText == 'Hijri') {
                    $('.dateResult_sijilRenew').text(response.hd + '-' + response.hm + '-' + response.hy);
                    $('.sijil').text('English');
                } else if (sijilbtnText == 'English') {
                    $('.dateResult_sijilRenew').text(response.gd + '-' + response.gm + '-' + response.gy);
                    $('.sijil').text('Hijri');
                }
            }
        });
    }

    if(element == 'sama') {
        var samaBtnText = $('.sama').text();
        var samaIssuanceDateArray = $('.dateResult_samaIssuance').text();
        var samaissuanceDateSplit = samaIssuanceDateArray.split("-");
        if(samaBtnText == 'Hijri') {
            var samaissuanceDateUrl = "http://www.hebcal.com/converter/?cfg=json&gy=" + samaissuanceDateSplit[2] + "&gm=" + samaissuanceDateSplit[1] + "&gd=" + samaissuanceDateSplit[0] + "&g2h=1";
        } else if(samaBtnText == 'English') {
            var samaissuanceDateUrl = "http://www.hebcal.com/converter/?cfg=json&hy=" + samaissuanceDateSplit[2] + "&hm=" + samaissuanceDateSplit[1] + "&hd=" + samaissuanceDateSplit[0] + "&h2g=1";
        }
        $.ajax({
            type: "GET",
            url: samaissuanceDateUrl,
            cache: false,
            processData: false,
            contentType: false,
            success: function(response) {
                if (samaBtnText == 'Hijri') {
                    $('.dateResult_samaIssuance').text(response.hd + '-' + response.hm + '-' + response.hy);
                    $('.sama').text('English');
                } else if (samaBtnText == 'English') {
                    $('.dateResult_samaIssuance').text(response.gd + '-' + response.gm + '-' + response.gy);
                    $('.sama').text('Hijri');
                }
            }
        });
    }

    if(element == 'deed') {
        var deedBtnText = $('.deed').text();
        var deedIssuanceDateArray = $('.dateResult_deedIssuance').text();
        var deedissuanceDateSplit = deedIssuanceDateArray.split("-");
        if(deedBtnText == 'Hijri') {
            var deedissuanceDateUrl = "http://www.hebcal.com/converter/?cfg=json&gy=" + deedissuanceDateSplit[2] + "&gm=" + deedissuanceDateSplit[1] + "&gd=" + deedissuanceDateSplit[0] + "&g2h=1";
        } else if(deedBtnText == 'English') {
            var deedissuanceDateUrl = "http://www.hebcal.com/converter/?cfg=json&hy=" + deedissuanceDateSplit[2] + "&hm=" + deedissuanceDateSplit[1] + "&hd=" + deedissuanceDateSplit[0] + "&h2g=1";
        }
        $.ajax({
            type: "GET",
            url: deedissuanceDateUrl,
            cache: false,
            processData: false,
            contentType: false,
            success: function(response) {
                if (deedBtnText == 'Hijri') {
                    $('.dateResult_deedIssuance').text(response.hd + '-' + response.hm + '-' + response.hy);
                    $('.deed').text('English');
                } else if (deedBtnText == 'English') {
                    $('.dateResult_deedIssuance').text(response.gd + '-' + response.gm + '-' + response.gy);
                    $('.deed').text('Hijri');
                }
            }
        });
    }

    if(element == 'rent') {
        var rentbtnText = $('.rent').text();
        var rentIssuanceDateArray = $('.dateResult_rentContract').text();
        var rentRenewDateArray = $('.dateResult_rentRenew').text();
        var rentNoticeDateArray = $('.dateResult_rentNotice').text();
        var rentIssuanceDateSplit = rentIssuanceDateArray.split("-");
        var rentRenewDateSplit = rentRenewDateArray.split("-");
        var rentNoticeDateSplit = rentNoticeDateArray.split("-");
        if(rentbtnText == 'Hijri') {
            var rentIssuanceDateUrl = "http://www.hebcal.com/converter/?cfg=json&gy=" + rentIssuanceDateSplit[2] + "&gm=" + rentIssuanceDateSplit[1] + "&gd=" + rentIssuanceDateSplit[0] + "&g2h=1";
            var rentRenewDateUrl = "http://www.hebcal.com/converter/?cfg=json&gy=" + rentRenewDateSplit[2] + "&gm=" + rentRenewDateSplit[1] + "&gd=" + rentRenewDateSplit[0] + "&g2h=1";
            var rentNoticeDateUrl = "http://www.hebcal.com/converter/?cfg=json&gy=" + rentNoticeDateSplit[2] + "&gm=" + rentNoticeDateSplit[1] + "&gd=" + rentNoticeDateSplit[0] + "&g2h=1";
        } else if(rentbtnText == 'English') {
            var rentIssuanceDateUrl = "http://www.hebcal.com/converter/?cfg=json&hy=" + rentIssuanceDateSplit[2] + "&hm=" + rentIssuanceDateSplit[1] + "&hd=" + rentIssuanceDateSplit[0] + "&h2g=1";
            var rentRenewDateUrl = "http://www.hebcal.com/converter/?cfg=json&hy=" + rentRenewDateSplit[2] + "&hm=" + rentRenewDateSplit[1] + "&hd=" + rentRenewDateSplit[0] + "&h2g=1";
            var rentNoticeDateUrl = "http://www.hebcal.com/converter/?cfg=json&hy=" + rentNoticeDateSplit[2] + "&hm=" + rentNoticeDateSplit[1] + "&hd=" + rentNoticeDateSplit[0] + "&h2g=1";
        }
        $.ajax({
            type: "GET",
            url: rentIssuanceDateUrl,
            cache: false,
            processData: false,
            contentType: false,
            success: function(response) {
                if (rentbtnText == 'Hijri') {
                    $('.dateResult_rentContract').text(response.hd + '-' + response.hm + '-' + response.hy);
                } else if (rentbtnText == 'English') {
                    $('.dateResult_rentContract').text(response.gd + '-' + response.gm + '-' + response.gy);
                }
            }
        });

        $.ajax({
            type: "GET",
            url: rentRenewDateUrl,
            cache: false,
            processData: false,
            contentType: false,
            success: function(response) {
                if (rentbtnText == 'Hijri') {
                    $('.dateResult_rentRenew').text(response.hd + '-' + response.hm + '-' + response.hy);
                } else if (rentbtnText == 'English') {
                    $('.dateResult_rentRenew').text(response.gd + '-' + response.gm + '-' + response.gy);
                }
            }
        });

        $.ajax({
            type: "GET",
            url: rentNoticeDateUrl,
            cache: false,
            processData: false,
            contentType: false,
            success: function(response) {
                if (rentbtnText == 'Hijri') {
                    $('.dateResult_rentNotice').text(response.hd + '-' + response.hm + '-' + response.hy);
                    $('.rent').text('English');
                } else if (rentbtnText == 'English') {
                    $('.dateResult_rentNotice').text(response.gd + '-' + response.gm + '-' + response.gy);
                    $('.rent').text('Hijri');
                }
            }
        });
    }
}