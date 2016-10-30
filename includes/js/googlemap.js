function CenterControl(controlDiv, map) {
  var chicago = {lat: 41.85, lng: -87.65};
  var controlUI = document.createElement('div');
  controlUI.style.backgroundColor = '#fff';
  controlUI.style.border = '2px solid #fff';
  controlUI.style.borderRadius = '3px';
  controlUI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
  controlUI.style.cursor = 'pointer';
  controlUI.style.marginBottom = '22px';
  controlUI.style.textAlign = 'center';
  controlUI.title = 'Click to recenter the map';
  controlDiv.appendChild(controlUI);

  var controlText = document.createElement('div');
  controlText.style.color = 'rgb(25,25,25)';
  controlText.style.fontFamily = 'Roboto,Arial,sans-serif';
  controlText.style.fontSize = '10px';
  controlText.style.lineHeight = '20px';
  controlText.style.paddingLeft = '5px';
  controlText.style.paddingRight = '5px';
  controlText.innerHTML = 'Center Map';
  controlUI.appendChild(controlText);

  controlUI.addEventListener('click', function() {
    map.setCenter(chicago);
  });

}


function init_map(lati, long, address) {
    /*地圖參數相關設定 Start*/
    var Options = {
        zoom: 15, /*縮放比例*/
        center: new google.maps.LatLng(lati, long), /*所查詢位置的經緯度位置*/
        scaleControl:false,
        zoomControl: false
    };

    map = new google.maps.Map(document.getElementById("googleMapHere"), Options);
    /*地圖參數相關設定 End*/
    var centerControlDiv = document.createElement('div');
    var centerControl = new CenterControl(centerControlDiv, map);

    centerControlDiv.index = 1;
    map.controls[google.maps.ControlPosition.TOP_CENTER].push(centerControlDiv);

    marker = new google.maps.Marker({
        map: map,
        position: new google.maps.LatLng(lati, long), /*圖標經緯度位置*/
    });
    /*自行設定圖標 End*/

    /*所查詢位置詳細資料 Start*/
    infowindow = new google.maps.InfoWindow({
        content: address
    });

    infowindow.open(map, marker);
    /*所查詢位置詳細資料 End*/

    /*
    事件偵聽器
    (可參閱：https://developers.google.com/maps/documentation/javascript/events)
    */
    // google.maps.event.addDomListener(window, 'load', init_map);

    map.addListener("click", function(where){
      placeMarkerAndPanTo(where.latLng, map);
    });
    function placeMarkerAndPanTo(latLng, map) {
        var marker = new google.maps.Marker({
          position: latLng,
          map: map
        });
        map.panTo(latLng);
}
    marker.addListener("click",function(){

    });
}
