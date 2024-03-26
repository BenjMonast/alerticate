function myMap(lat, lon) {
    function getStreetAddress() {
        var geocoder = new google.maps.Geocoder;
        var latlng = new google.maps.LatLng(marker.position.lat(), marker.position.lng());
        geocoder.geocode({'location':latlng}, function (results, status) {
            if (results[0] !== null) {
                $('#address').val(results[0].formatted_address);
            }
        });
    }

    var mapProp = {
        center:new google.maps.LatLng(lat, lon),
        zoom: 5
    };

    var map = new google.maps.Map(document.getElementById('map'), mapProp);
    var marker = new google.maps.Marker({position:new google.maps.LatLng(lat, lon), draggable: true});
    marker.setMap(map);
    marker.addListener('dragend', function () {
        $('#lat').val(marker.getPosition().lat());
        $('#lon').val(marker.getPosition().lng());
        getStreetAddress();
    });

    //Geolocation API Parsing
    $('#address').on('change', function() {
        var geocoder = new google.maps.Geocoder();
        var result = geocoder.geocode({
            'address': $('#address').val()
        }, function (result, status) {
            var address = result[0].formatted_address;
            var lat = result[0].geometry.location.lat();
            var lon = result[0].geometry.location.lng();
            var latlng = new google.maps.LatLng(lat, lon);
            console.log(JSON.stringify(result[0], null, 2));
            marker.setPosition(latlng);
            map.panTo(latlng);
            $('#address').val(address);
            $('#lat').val(lat);
            $('#lon').val(lon);
        });
    });

    getStreetAddress();
}