<!DOCTYPE html>
<html>
    <head>
        <title>Place Autocomplete</title>
    </head>
    <body>

        <input id="pac-input" type="text" placeholder="Enter a location">
        <div id="map"></div>
        <script>
            function initMap() {
                var map = new google.maps.Map(document.getElementById('map'), {center: {lat: -33.8688, lng: 151.2195},zoom: 13 });        
                var input = document.getElementById('pac-input');
                var autocomplete = new google.maps.places.Autocomplete(input);
            
                // Bind the map's bounds (viewport) property to the autocomplete object,
                // so that the autocomplete requests use the current map bounds for the
                // bounds option in the request.
                autocomplete.bindTo('bounds', map);
            }
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBrAT6XIzO4FSwU1_iXBgvvOkAqqx8GRBw&libraries=places&callback=initMap" async defer></script>
    </body>
</html>