function validarFormulario() {
    var descripcion = document.getElementById('descripcion').value;
    var precio = document.getElementById('precio').value;
    var provincia = document.getElementById('provincia').value;
    var ciudad = document.getElementById('ciudad').value;
    var ubicacion = document.getElementById('ubicacion').value;
    var codigoPostal = document.getElementById('codigo_postal').value;

    if (descripcion.trim() === '' || precio.trim() === '' || provincia.trim() === '' || ciudad.trim() === '' || ubicacion.trim() === '') {
        alert('Los campos de título, texto, provincia, ciudad y ubicación no pueden estar vacíos.');
        return false;
    }

    if (isNaN(parseFloat(precio))) {
        alert('El precio debe ser un número.');
        return false;
    }

    if (isNaN(parseInt(codigoPostal))) {
        alert('El código postal debe ser un número.');
        return false;
    }

    var textoRegex = /^[A-Za-zÁáÉéÍíÓóÚúÑñ\s]+$/;

    if (!textoRegex.test(provincia) || !textoRegex.test(ciudad) || !textoRegex.test(ubicacion)) {
        alert('Los campos de descripción, provincia, ciudad y ubicación deben contener solo letras.');
        return false;
    }

    var latitud = parseFloat(document.getElementById('lat').value);
    var longitud = parseFloat(document.getElementById('lng').value);

    if (isNaN(latitud) || isNaN(longitud)) {
        alert('Debe seleccionar una ubicación en el mapa.');
        return false;
    }

    return true;
}


function tipoPublicidad()
{
    document.getElementById("tipoPublicidad").addEventListener("change", function () {
        var seleccionado = this.options[this.selectedIndex].text;
        var mensajePiso = document.getElementById("mensajePiso");
        var mensajePubli = document.getElementById("mensajePubli");
        if (seleccionado === "Piso") {
            mensajePiso.style.display = "block";
            mensajePubli.style.display = "none";
        } else {
            mensajePiso.style.display = "none";
            mensajePubli.style.display = "block";
        }
    });
}


function vigilante()
{
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
                    });
                    
                    obtenerMision(markers);
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

    function obtenerMision(markers) {

        var botonSolicitarMision = document.getElementById('solicitarMision');
        botonSolicitarMision.addEventListener('click', seleccionarPunto);

        // Función para seleccionar un punto aleatorio y resaltarlo en el mapa
        function seleccionarPunto() {
            // Obtener todos los marcadores en el mapa
            var marcadores = L.layerGroup(markers); // Suponiendo que 'markers' es un array de objetos con atributos de marcadores

            // Generar un índice aleatorio
            var indiceAleatorio = Math.floor(Math.random() * marcadores.getLayers().length);

            // Obtener el objeto JSON correspondiente al índice aleatorio
            var marcadorJSON = markers[indiceAleatorio];
            

            // Obtener la ubicación (latitud y longitud) del marcador
            var latitud = marcadorJSON.latitud;
            var longitud = marcadorJSON.longitud;

            var data = "descripcion=" + encodeURIComponent("Ve al lugar y saca una foto");
            data += "&id_publicidad=" + encodeURIComponent(marcadorJSON.id_publicidad);
            var url = '../lib/ejecutarMision.php?subirMision';
            var xhr = new XMLHttpRequest();
            xhr.open('POST', url, true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        
                        var resultado = xhr.responseText.trim();
                        if (resultado === "true") {
                            
                            // Realiza la acción A
                        }

                        if (resultado === "false") {
                            

                            // Crear una tabla con las características de la misión:
                            // Obtener la tabla existente
                            var tabla = document.getElementById('tabla-puntos');

                            // Crear una nueva fila de tabla
                            var fila = document.createElement('tr');

                            // Crear las celdas de la fila con los datos del punto
                            var celdaId = document.createElement('td');
                            celdaId.textContent = marcadorJSON.ubicacion;
                            fila.appendChild(celdaId);

                            var celdaLatitud = document.createElement('td');
                            celdaLatitud.textContent = latitud;
                            fila.appendChild(celdaLatitud);

                            var celdaLongitud = document.createElement('td');
                            celdaLongitud.textContent = longitud;
                            fila.appendChild(celdaLongitud);

                            var celdaInput = document.createElement('td');
                            fila.appendChild(celdaInput);

                            // Agregar la fila a la tabla
                            tabla.appendChild(fila);

                            window.location.href = 'vigilante.php?misiones=';
                            exit();
                        }


                    } else {
                        console.log("Error en la solicitud AJAX. Estado de la respuesta: " + xhr.status);
                    }
                }
            };
            xhr.onerror = function () {
                console.log("Error en la solicitud AJAX");
            };
            xhr.send(data);

            // Crear un marcador de Leaflet utilizando la ubicación
            var marcadorLeaflet = L.marker([latitud, longitud]);

            // Hacer algo con el marcador seleccionado (por ejemplo, resaltarlo en el mapa)
            resaltarMarcadorEnMapa(marcadorLeaflet);
        }

        // Función para resaltar un marcador en el mapa
        function resaltarMarcadorEnMapa(marcador) {
            if (marcador) {
                // Código para resaltar el marcador
                marcador.setIcon(L.icon({
                    iconUrl: 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/39/Red_circle_frame_transparent.svg/1200px-Red_circle_frame_transparent.svg.png',
                    iconSize: [25, 25],
                    iconAnchor: [12, 12]
                }));

                // Agregar el marcador resaltado al mapa
                marcador.addTo(map);
            }
        }
    }
}