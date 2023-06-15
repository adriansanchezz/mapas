function mapaEmpresa() {
    try {
        // Creación del mapa.
        var map = L.map('map').setView([43.3828500, -3.2204300], 7);

        // Se definen las coordenadas límites de España (más o menos).
        var spainBounds = L.latLngBounds(
            L.latLng(36.0000, -9.3922), // Coordenada superior izquierda (Latitud, Longitud)
            L.latLng(43.7486, 4.3273)  // Coordenada inferior derecha (Latitud, Longitud)
        );


        // Selección de cuanto zoom tendrá y más atributos necesarios.
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
            maxZoom: 18,
        }).addTo(map);
        
    } catch (error) {
        var errorElement = document.getElementById('errorUsuario');
        errorElement.textContent = error;
    }

    // Llamada AJAX para obtener los datos de los marcadores
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '../lib/obtenerMarcadoresEmpresa.php', true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            var publicidades = JSON.parse(xhr.responseText);
            posicionarPublicidades(publicidades);
        }
    };
    xhr.send();

    // Función para agregar los marcadores al mapa
    function posicionarPublicidades(publicidades) {
        publicidades.forEach(function (publicidad) {
            var latitud = publicidad.latitud;
            var longitud = publicidad.longitud;
            var descripcion = publicidad.descripcion;
            var ubicacion = publicidad.ubicacion;
            var precio = publicidad.precio;
            var nombreTipo = publicidad.nombre_tipo;
            var imageUrl = publicidad.imageUrl;
            var mostrarImagen = publicidad.mostrarImagen;

            var marker = L.marker([latitud, longitud]).addTo(map);
            marker.bindPopup(`
                <div class='popup-content'>
                    <h3>${nombreTipo} ${ubicacion} ${precio}€</h3>
                    <p>${descripcion}</p>
                    <img src='${imageUrl}' alt='Imagen de la ubicación' class='imagen_mapa'>
                </div>
                <form action='empresa.php' method='POST'>
                    <input type='hidden' name='publicidad_id' value='${publicidad.id_publicidad}'>
                    <input type='hidden' name='lat' value='${latitud}'>
                    <input type='hidden' name='lng' value='${longitud}'>
                    <input type='hidden' name='ubicacion' value='${ubicacion}'>
                    <input type='hidden' name='descripcion' value='${descripcion}'>
                    Imagen Google: 
                    Imagen usuario:<br> ${mostrarImagen}
                    <input type='hidden' name='precio' value='${precio}'>
                    <p style='color: ${publicidad.seleccionada ? 'red' : 'black'};'>${publicidad.seleccionada ? 'YA SELECCIONADA' : ''}</p>
                    <button type='submit' name='add_to_cart' value='1'>Seleccionar</button>
                </form>
            `);
        });
    }

    // Se indica que los límites máximos son los establecidos en spainBounds.
    map.setMaxBounds(spainBounds); // Establecer límites máximos

    // Permitir arrastrar el mapa fuera de los límites para navegar
    map.on('drag', function () {
        map.panInsideBounds(spainBounds, { animate: false });
    });

    // Permitir hacer zoom fuera de los límites para navegar
    map.on('zoomend', function () {
        if (!spainBounds.contains(map.getCenter())) {
            map.setView([43.3828500, -3.2204300], 13);
        }
    });

    // Función para buscar una dirección mediante una barra de búsqueda.
    function buscarDireccion() {
        var direccion = document.getElementById('direccion').value;

        // Realizar la petición de geocodificación mediante un fetch.then.then.catch para asegurarse de que funciona.
        fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + direccion)
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                if (data.length > 0) {
                    var latitud = parseFloat(data[0].lat);
                    var longitud = parseFloat(data[0].lon);

                    // Centrar el mapa en la ubicación encontrada.
                    map.setView([latitud, longitud], 13);

                } else {
                    alert("No se encontró la dirección especificada.");
                }
            })
            .catch(function (error) {
                // Por consola se señala el error.
                console.log('Error:', error);
            });
    }
    document.getElementById('buscarDireccion').addEventListener('click', buscarDireccion);
}




function mapaUsuario() {
    var marker;
    try {
        // Se crea el mapa.
        var map = L.map('map').setView([43.3828500, -3.2204300], 7);

        // Se definen las coordenadas límites de España (más o menos).
        var spainBounds = L.latLngBounds(
            L.latLng(36.0000, -9.3922), // Coordenada superior izquierda (Latitud, Longitud)
            L.latLng(43.7486, 4.3273)  // Coordenada inferior derecha (Latitud, Longitud)
        );

        // Se añade al mapa con un zoom de 18.
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
            maxZoom: 18,
        }).addTo(map);


    } catch (error) {
        var error = document.getElementById('errorUsuario');
        error.textContent = error;
    }

    var xhr = new XMLHttpRequest();
    xhr.open('GET', '../lib/obtenerMarcadoresUsuarios.php', true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            var marcadores = JSON.parse(xhr.responseText);
            posicionarMarcadores(marcadores);
        }
    };
    xhr.send();

    function posicionarMarcadores(marcadores) {
        for (var i = 0; i < marcadores.length; i++) {
            var marcador = marcadores[i];

            // Crear el marcador y añadirlo al mapa
            marker = L.marker([marcador.latitud, marcador.longitud]).addTo(map);

            // Asignar los datos del marcador al pop-up
            var popupContent = `<div class='popup-content vista_mapa'>
                                <h3 class='popup-title'>${marcador.nombre_tipo}</h3>
                                <h4 class='popup-location'>${marcador.ubicacion}</h4>
                                <h4 class='popup-price'>${marcador.precio}</h4>
                                <p class='popup-description'>${marcador.descripcion}</p>
                                Imagen Google:<br> <img class='popup-image imagen_mapa' src='${marcador.imageUrl}' alt='Imagen de la ubicación'><br>
                                Imagen usuario:<br>${marcador.mostrarImagen}<br>`;

                            if (marcador.compra == 0) {
                                popupContent += `<form action='usuario.php' method='POST'>
                                    <input type='hidden' name='id_publicidad' value='${marcador.id_publicidad}'>
                                    <button class='popup-delete-button' type='submit' name='borrarMarcador'>Borrar</button>
                                </form>`;
                            }
                            else
                            {
                                popupContent += `<p>No puedes borrar una publicidad vendida a una empresa.</p>`;
                            }

                            popupContent += `</div>`;

            marker.bindPopup(popupContent);
        }
    }



    // Se indica que los límites máximos son los establecidos en spainBounds.
    map.setMaxBounds(spainBounds); // Establecer límites máximos

    // Permitir arrastrar el mapa fuera de los límites para navegar
    map.on('drag', function () {
        map.panInsideBounds(spainBounds, { animate: false });
    });

    // Permitir hacer zoom fuera de los límites para navegar
    map.on('zoomend', function () {
        if (!spainBounds.contains(map.getCenter())) {
            map.setView([43.3828500, -3.2204300], 13);
        }
    });


    // Variable marcador.
    var marker2;

    // Event listener de click.
    map.on('click', function (e) {
        try {


            // Control para saber si el click ha sido dentro de los límites establecidos.
            if (!spainBounds.contains(e.latlng)) {
                alert('Por favor, coloque puntos dentro de España.');
                // Si no lo está, entonces se le manda una alerta.
                return;
            }

            // Si existe un marcador se borra.
            if (marker2) {
                map.removeLayer(marker2);
            }

            // Se crea un nuevo marcador y se añade al mapa.
            marker2 = L.marker(e.latlng).addTo(map);
            var apiKey = 'AIzaSyADr5gpzLPePzkWwz8C94wBQ21DzQ4GGVU'; 

            var img = new Image();
            img.src = 'https://maps.googleapis.com/maps/api/streetview?size=400x300&location=' + e.latlng.lat + ',' + e.latlng.lng + '&key=' + apiKey;


            marker2.bindPopup("<img src='" + img.src + "' width='400' height='300' id='imagenPopUp'><br>Ubicación seleccionada").openPopup();

            // Actualizar campos ocultos en el formulario con las coordenadas.
            document.getElementById('lat').value = e.latlng.lat;
            document.getElementById('lng').value = e.latlng.lng;
            var apiKey = 'AIzaSyADr5gpzLPePzkWwz8C94wBQ21DzQ4GGVU'; // Reemplaza con tu propia API Key de Google Maps Static


            // Realizar la solicitud de geocodificación a Nominatim.
            var url = 'https://nominatim.openstreetmap.org/reverse?lat=' + e.latlng.lat + '&lon=' + e.latlng.lng + '&format=json';
            // Y se realiza un fetch a esa url mediante then then catch para controlar todos los errores posibles o lentitudes en la solicitud.
            fetch(url)
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    if (data && data.address) {
                        // Se obtienen los elementos mediante id y se rellenan con los obtenido.
                        document.getElementById('provincia').value = data.address.state || '';
                        document.getElementById('ciudad').value = data.address.city || '';
                        document.getElementById('ubicacion').value = data.address.road || '';
                        document.getElementById('codigo_postal').value = data.address.postcode || '';
                    }
                })
                .catch(function (error) {
                    console.log('Error:', error);
                });
        } catch (error) {
            var error = document.getElementById('errorUsuario');
            error.textContent = error;
        }
    });



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

        if (!textoRegex.test(provincia)) {
            alert('El campo de provincia debe contener solo letras.');
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


    // Función para buscar una dirección mediante una barra de búsqueda.
    function buscarDireccion() {
        var direccion = document.getElementById('direccion').value;

        // Realizar la petición de geocodificación mediante un fetch.then.then.catch para asegurarse de que funciona.
        fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + direccion)
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                if (data.length > 0) {
                    var latitud = parseFloat(data[0].lat);
                    var longitud = parseFloat(data[0].lon);

                    // Centrar el mapa en la ubicación encontrada.
                    map.setView([latitud, longitud], 13);

                    if (marker) {
                        map.removeLayer(marker);
                    }

                    marker = L.marker([latitud, longitud]).addTo(map);
                    marker.bindPopup("Ubicación encontrada").openPopup();

                    // Actualizar campos ocultos en el formulario con las coordenadas.
                    document.getElementById('lat').value = latitud;
                    document.getElementById('lng').value = longitud;
                } else {
                    alert("No se encontró la dirección especificada.");
                }
            })
            .catch(function (error) {
                // Por consola se señala el error.
                console.log('Error:', error);
            });
    }
    document.getElementById('buscarDireccion').addEventListener('click', buscarDireccion);

}








function vigilante() {


    try {
        // Creación del mapa.
        var map = L.map('map').setView([43.3828500, -3.2204300], 7);

        // Selección de cuanto zoom tendrá y más atributos necesarios.
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
            maxZoom: 18,
        }).addTo(map);
    } catch (error) {
        var errorElement = document.getElementById('errorUsuario');
        errorElement.textContent = error;
    }

    // Llamada AJAX para obtener los datos de los marcadores
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '../lib/obtenerMarcadoresMisiones.php', true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            var marcadores = JSON.parse(xhr.responseText);
            resaltarMarcadoresEnMapa(marcadores);
        }
    };
    xhr.send();

    function resaltarMarcadoresEnMapa(marcadores) {
        for (var i = 0; i < marcadores.length; i++) {
            var marcador = L.marker([marcadores[i].latitud, marcadores[i].longitud]);

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
            fetch('../lib/obtenerMarcadoresVigilante.php', {
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
            var url = '../lib/solicitarMision.php?subirMision';
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

    if (!textoRegex.test(provincia)) {
        alert('El campo provincia solo puede tener letras.');
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


function tipoPublicidad() {
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




