var source, destination, distanceDisplay, truckType, duration, distanceCalculate;
var directionsDisplay, directionsService;

function initMap() {
  directionsService = new google.maps.DirectionsService();
  var sou = new google.maps.places.SearchBox(jQuery('.txtSource')[0]);
  var des = new google.maps.places.SearchBox(jQuery('.txtDestination')[0]);
  var onChangeHandler = function() {
    getRoute();
  };
  des.addListener('places_changed', onChangeHandler);
  directionsDisplay = new google.maps.DirectionsRenderer({
    'draggable': false
  });
  var mapOptions = {
    zoom: 10,
    center: {
      lat: 21.0236175,
      lng: 105.8263214
    },
    scrollwheel: false
  };
  map = new google.maps.Map(document.getElementById('dvMap'), mapOptions);
  directionsDisplay.setMap(map);
  directionsDisplay.setPanel(document.getElementById('dvPanel'));
  jQuery('.divTotal').hide();
  jQuery('#divResult').hide();
  jQuery( document ).ready( function() {
    jQuery( document )
    .on('click', '.btnSubmitTinhcuoc', function() {
      if (!$('div.nf-error-msg').is(':visible')) jQuery('#divResult').show();
    })
    .on('change', '.txtTruckType', function() {
      truckType = jQuery('.txtTruckType').val();
      if (jQuery('.txtDestination').val() && jQuery('.txtSource').val()) calculateTotal(distanceCalculate,truckType);
    });
  });
}

function getRoute() {
  source = document.getElementsByClassName('txtSource')[0].value;
  destination = document.getElementsByClassName('txtDestination')[0].value;
  var request = {
    origin: source,
    destination: destination,
    travelMode: google.maps.TravelMode.DRIVING
  };
  directionsService.route(request, function(response, status) {
    if (status == google.maps.DirectionsStatus.OK) {
      directionsDisplay.setDirections(response);
    }
  });
  var service = new google.maps.DistanceMatrixService();
  service.getDistanceMatrix({
    origins: [source],
    destinations: [destination],
    travelMode: google.maps.TravelMode.DRIVING,
    unitSystem: google.maps.UnitSystem.METRIC,
    avoidHighways: false,
    avoidTolls: false
  }, function(response, status) {
    if (status == google.maps.DistanceMatrixStatus.OK && response.rows[0].elements[0].status != "ZERO_RESULTS") {
      distanceDisplay = response.rows[0].elements[0].distance.text;
      distanceCalculate = response.rows[0].elements[0].distance.value;
      duration = response.rows[0].elements[0].duration.text;
      var truckType = jQuery('.txtTruckType').val();
      calculateTotal(distanceCalculate, truckType);
    } else {
      alert("Unable to find the distance via road.");
    }
  });
}

function calculateTotal(distance, truckType){
  var pricePerKM = [
  [160000,19,15,12,11.5],
  [180000,21,16,12.5,12],
  [200000,22,17,13,12.5],
  [220000,23,18,13.6,13],
  [250000,25,19,14.5,14]
];
  var total = pricePerKM[truckType-1][0];
  if (distance <= 4000) total = pricePerKM[truckType-1][0];
  else if (4000 < distance && distance <= 10000) total = pricePerKM[truckType-1][0] + pricePerKM[truckType-1][1]*(distance-4000);
  else if (10000 < distance && distance <= 40000) total = pricePerKM[truckType-1][0] + pricePerKM[truckType-1][1]*(6000)+ pricePerKM[truckType-1][2]*(distance-10000);
  else if (40000 < distance && distance <= 50000) total = pricePerKM[truckType-1][3]*(50000);
  else if (50000 < distance && distance <= 100000) total = pricePerKM[truckType-1][3]*(distance);
  else if (100000 < distance) total = pricePerKM[truckType-1][4]*(distance);
  jQuery('.txtDistance').val(distanceDisplay);
  $('.txtTime').val(duration);
  var tot = total.toLocaleString('vi-VN', {
    style: 'currency',
    currency: 'vnd'
  });
  document.getElementsByClassName('txtTotal')[0].value = tot;
  jQuery('.txtDistance').trigger('change');
  jQuery('.txtTime').trigger('change');
  jQuery('.txtSource').trigger('change');
  jQuery('.txtDestination').trigger('change');
  jQuery('.txtTotal').trigger('change');
  var resultTxt = "";
  resultTxt += "<strong>Điểm đi:</strong> " + jQuery('.txtSource').val() + "<br />";
  resultTxt += "<strong>Điểm đến:</strong> " + jQuery('.txtDestination').val() + "<br />";
  resultTxt += "<strong>Loại xe:</strong> " + jQuery('.txtTruckType option:selected').text() + "<br />";
  resultTxt += "<strong>Khoảng cách:</strong> " + distanceDisplay + "<br />";
  resultTxt += "<strong>Thời gian:</strong> " + duration + "<br/>";
  resultTxt += "<strong>Chi phí dự tính:</strong> " + tot + "<br/><strong>Ghí chú: Quý khách hàng lưu ý</strong> <br>- Giá trên chưa bao gồm phí cầu đường, bến bãi<br>- Giá trên chưa bao gồm VAT 10%<br>- Giá trên chưa bao gồm phụ phí đi các tỉnh vùng cao và phí lưu đêm(nếu có)";
  resultTxt += "<input class='ninja-forms-field btnSubmitTinhcuoc nf-element' type='button' value='Đặt xe' onclick=\"alert('Xin cảm ơn.\\nChúng tôi sẽ liên hệ tới quý khách sớm nhất có thể.');\">";
  resultTxt += " Hoặc gọi <a href='tel:0886326326'>0886326326</a>";
  jQuery('#divResult').html(resultTxt);
}

function initMapForFooter() {
  var map;
  if ($('#map').length > 0) {
    map = new GMaps({
      el: '#map',
      lat: 20.9686452,
      lng: 105.8249196,
      scrollwheel: false
    });
    map.addMarker({
      lat: 20.9686452,
      lng: 105.8249196,
      title: 'Tan sang',
      infoWindow: {
        content: '<p>Cong ty Tan Sang</p>'
      }
    });
  }

  function placeMarker(location) {
    var clickedLocation = new google.maps.LatLng(location);
    var marker = new google.maps.Marker({
      position: location,
      map: map
    });
    map.setCenter(location);
  }
  google.maps.event.addListener(map, 'click', function(event) {
    var myLatLng = event.latLng;
    var lat = myLatLng.lat();
    var lng = myLatLng.lng();
    alert(lat);
  });
}
$(function() {
    //initMapForFooter();
});
// google.maps.event.addDomListener(window, 'load', initMapForFooter);
