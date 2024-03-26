function myMap() {
    $(function () {
        var apiUrl = 'https://api.askgeo.com/v1/1962/bf9bfd22f43ce3248a7a23d01c9bfa813ab1a3559d133308901b5c3f3c1e4ae/query.json?databases=NaturalEarthCountry&points='

        function getStreetAddress() {
            var geocoder = new google.maps.Geocoder;
            var latlng = new google.maps.LatLng(marker.getPosition().lat(), marker.getPosition().lng());
            geocoder.geocode({'location':latlng}, function (results, status) {
                if (results[0] !== null) {
                    $('#address').val(results[0].formatted_address);
                    $('#form-address').val(results[0].formatted_address);
                    short_name = results[0].address_components.filter(component => // Go through the address components and select the one that is type 'country'
                        component.types.includes('country')
                    )[0].short_name; // I only need the short_name
                    $(`[data-code='${short_name}']`).prop('selected', true);
                }
            });
        }

        var mapProp = {
            center:new google.maps.LatLng($('#lat').val(), $('#lon').val()),
            zoom: 5
        };
        var map = new google.maps.Map(document.getElementById('map'), mapProp);
        window.marker = new google.maps.Marker({position:new google.maps.LatLng($('#lat').val(), $('#lon').val()), draggable: true});
        console.log($('#lat'))
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
                marker.setPosition(latlng);
                map.panTo(latlng);
                $('#address').val(address);
                $('#form-address').val(address);
                short_name = result[0].address_components.filter(component => // Go through the address components and select the one that is type 'country'
                    component.types.includes('country')
                )[0].short_name; // I only need the short_name
                $(`[data-code='${short_name}']`).prop('selected', true);
                $('#lat').val(lat);
                $('#lon').val(lon);
            });
        });
    });
}