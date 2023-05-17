var map = L.map('map').setView([43.3828500, -3.2204300], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
    maxZoom: 18,
}).addTo(map);

// Obtener la ubicación actual del usuario
if ("geolocation" in navigator) {
    navigator.geolocation.getCurrentPosition(function (position) {
        var userLat = position.coords.latitude;
        var userLng = position.coords.longitude;

        // Centrar el mapa en la ubicación del usuario
        map.setView([userLat, userLng], 13);

        // Crear un círculo alrededor de la ubicación del usuario
        var userCircle = L.circle([userLat, userLng], {
            color: 'blue',
            fillColor: 'blue',
            fillOpacity: 0.2,
            radius: 500 // Radio del círculo en metros
        }).addTo(map);

        // Obtener los límites del círculo
        var circleBounds = userCircle.getBounds();
        var circleBoundsNE = circleBounds.getNorthEast();
        var circleBoundsSW = circleBounds.getSouthWest();
        var circleBoundsLatMin = circleBoundsSW.lat;
        var circleBoundsLngMin = circleBoundsSW.lng;
        var circleBoundsLatMax = circleBoundsNE.lat;
        var circleBoundsLngMax = circleBoundsNE.lng;

        // Consultar los marcadores dentro del límite
        // Reemplaza esta sección con el código PHP correspondiente
        fetch('../lib/obtenerMarcadores.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                latMin: circleBoundsLatMin,
                lngMin: circleBoundsLngMin,
                latMax: circleBoundsLatMax,
                lngMax: circleBoundsLngMax
            })
        })
            .then(function (response) {
                return response.json();
            })
            .then(function (markers) {
                markers.forEach(function (marker) {
                    var lat = marker.latitud;
                    var lng = marker.longitud;
                    var nombreTipo = marker.nombre_tipo;
                    var ubicacion = marker.ubicacion;
                    var precio = marker.precio;
                    var descripcion = marker.descripcion;
                    var imageUrl = marker.imagen_url;

                    // Crear un marcador para cada registro de la base de datos dentro del límite
                    var marker = L.marker([lat, lng]).addTo(map);
                    marker.bindPopup("<div><p>" + descripcion + "</p></div>");
                })
            })
            .catch(function (error) {
                console.log("Error al obtener los marcadores: " + error.message);
            });


    }, function (error) {
        console.log("Error al obtener la ubicación del usuario: " + error.message);
    });
} else {
    console.log("Geolocalización no es compatible en este navegador.");
}