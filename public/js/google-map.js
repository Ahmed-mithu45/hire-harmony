var google;

function init() {
    // 1. Get the HTML DOM element that will contain your map 
    var mapElement = document.getElementById('map');

    // 2. ONLY run if the 'map' div exists on the current page
    if (mapElement) {
        
        // The latitude and longitude to center the map
        var myLatlng = new google.maps.LatLng(40.69847032728747, -73.9514422416687);
        
        var mapOptions = {
            // How zoomed in you want the map to start at
            zoom: 7,

            // The center position
            center: myLatlng,

            // Disable scroll zoom
            scrollwheel: false,

            // Custom Map Styles
            styles: [
                {
                    "elementType": "geometry",
                    "stylers": [{ "color": "#f5f5f5" }]
                },
                {
                    "elementType": "labels.icon",
                    "stylers": [{ "visibility": "off" }]
                },
                {
                    "elementType": "labels.text.fill",
                    "stylers": [{ "color": "#616161" }]
                },
                {
                    "elementType": "labels.text.stroke",
                    "stylers": [{ "color": "#f5f5f5" }]
                },
                {
                    "featureType": "administrative.land_parcel",
                    "elementType": "labels.text.fill",
                    "stylers": [{ "color": "#bdbdbd" }]
                },
                {
                    "featureType": "poi",
                    "elementType": "geometry",
                    "stylers": [{ "color": "#eeeeee" }]
                },
                {
                    "featureType": "poi",
                    "elementType": "labels.text.fill",
                    "stylers": [{ "color": "#757575" }]
                },
                {
                    "featureType": "poi.park",
                    "elementType": "geometry",
                    "stylers": [{ "color": "#e5e5e5" }]
                },
                {
                    "featureType": "poi.park",
                    "elementType": "labels.text.fill",
                    "stylers": [{ "color": "#9e9e9e" }]
                },
                {
                    "featureType": "road",
                    "elementType": "geometry",
                    "stylers": [{ "color": "#ffffff" }]
                },
                {
                    "featureType": "road.arterial",
                    "elementType": "labels.text.fill",
                    "stylers": [{ "color": "#757575" }]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "geometry",
                    "stylers": [{ "color": "#dadada" }]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "labels.text.fill",
                    "stylers": [{ "color": "#616161" }]
                },
                {
                    "featureType": "road.local",
                    "elementType": "labels.text.fill",
                    "stylers": [{ "color": "#9e9e9e" }]
                },
                {
                    "featureType": "transit.line",
                    "elementType": "geometry",
                    "stylers": [{ "color": "#e5e5e5" }]
                },
                {
                    "featureType": "transit.station",
                    "elementType": "geometry",
                    "stylers": [{ "color": "#eeeeee" }]
                },
                {
                    "featureType": "water",
                    "elementType": "geometry",
                    "stylers": [{ "color": "#c9c9c9" }]
                },
                {
                    "featureType": "water",
                    "elementType": "labels.text.fill",
                    "stylers": [{ "color": "#9e9e9e" }]
                }
            ]
        };

        // Create the Google Map
        var map = new google.maps.Map(mapElement, mapOptions);
        
        // Addresses to place markers
        var addresses = ['New York'];
        var geocoder = new google.maps.Geocoder();

        for (var x = 0; x < addresses.length; x++) {
            (function(address) {
                geocoder.geocode({ 'address': address }, function(results, status) {
                    if (status === google.maps.GeocoderStatus.OK) {
                        new google.maps.Marker({
                            position: results[0].geometry.location,
                            map: map,
                            icon: 'images/loc.png'
                        });
                    } else {
                        console.error('Geocode was not successful for the following reason: ' + status);
                    }
                });
            })(addresses[x]);
        }
    } else {
        // Log to console if the map div is missing, preventing the InvalidValueError
        console.log("Map container element not found. Initialization skipped.");
    }
}

// Load the map
google.maps.event.addDomListener(window, 'load', init);