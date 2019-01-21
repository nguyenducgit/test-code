var map;
var markers = [];
var geocoder = new google.maps.Geocoder();
var vietnam = new google.maps.LatLng(15.9030623, 105.8066925, 5);
$(document).ready(function() {
    if ($('#wrap_ggmap').length > 0) { google.maps.event.addDomListener(window, 'load', initialize) }
    $('.rightstore #region').on('change', function() { goSearchShop() });
    $('.google-search').on('keyup', function(e) { var code = event.keyCode || event.which; if (code == 13) { goSearchShop() } })
});

function initialize() {
    var mapOptions = { zoom: 5, center: vietnam };
    map = new google.maps.Map(document.getElementById('wrap_ggmap'), mapOptions);
    $('.coord').each(function() {
        var id = $(this).attr('data-id');
        var lat = $(this).attr('data-lat');
        var lng = $(this).attr('data-lng');
        var title = $(this).attr('data-title');
        var address = $(this).attr('data-address');
        var place, marker, hasLatLng = 1;
        if (lat != 0 && lng != 0) {
            place = new google.maps.LatLng(lat, lng);
            marker = new google.maps.Marker({ position: place, map: map, title: title });
            var markerInfo = { 'id': id, 'marker': marker };
            markers.push(markerInfo)
        } else {
            hasLatLng = 0;
            codeAddress(id, title, address)
        }
        $(this).on('click', function() {
            var id = $(this).attr('data-id');
            $('.actstore').removeClass('actstore');
            $(this).addClass('actstore');
            if (hasLatLng == 1) { zoomMapByLocation(id, place, 20) } else { zoomMapByAdress(id, address, 20) }
        })
    });
    zoomMapbyRegion()
}

function codeAddress(id, title, address) {
    address += ', Việt Nam';
    geocoder.geocode({ 'address': address }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            var marker = new google.maps.Marker({ position: results[0].geometry.location, map: map, title: title });
            var markerInfo = { 'id': id, 'marker': marker };
            markers.push(markerInfo)
        } else { alert("Geocode was not successful for the following reason: " + status) }
    })
}

function zoomMapbyRegion() {
    var region = $('#region').val();
    var address = 'Việt Nam';
    var lat = 0;
    var lng = 0;
    var zoom = 5;
    if (region > 0) {
        address = $('#region option[value="' + region + '"]').text() + ', Việt Nam';
        if ($('.coord').length > 0) {
            address = $('.coord:first').data('address') + ', Việt Nam';
            lat = $('.coord:first').data('lat');
            lng = $('.coord:first').data('lng')
        }
        zoom = 10
    }
    if (lat != 0 && lng != 0) {
        var place = new google.maps.LatLng(lat, lng);
        zoomMapByLocation(0, place, zoom)
    } else { zoomMapByAdress(0, address, zoom) }
}

function zoomMapByAdress(id, address, zoom) {
    zoom = zoom || 20;
    geocoder.geocode({ 'address': address }, function(results, status) { if (status == google.maps.GeocoderStatus.OK) { zoomMapByLocation(id, results[0].geometry.location, zoom) } else { alert("Geocode was not successful for the following reason: " + status) } })
}

function zoomMapByLocation(id, location, zoom) {
    zoom = zoom || 20;
    map.setCenter(location);
    map.setZoom(zoom);
    if (id > 0) {
        for (var i = 0; i < markers.length; i++) {
            var locationID = markers[i].id;
            if (locationID == id) {
                console.log('id = ' + id);
                markers[i].marker.setMap(map)
            } else {
                console.log('id != ' + locationID);
                markers[i].marker.setMap(null)
            }
        }
    }
}