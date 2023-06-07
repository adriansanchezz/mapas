'use strict';

function administradorProductos() {
    // Obtén todos los elementos con la clase "editable"
    let editables = document.getElementsByClassName('editableProducto');

    // Agrega un controlador de eventos a cada elemento editable
    for (var i = 0; i < editables.length; i++) {
        editables[i].addEventListener('click', function () {
            if (!this.classList.contains('editing')) {
                // Marcar el elemento como en edición
                this.classList.add('editing');

                // Obtén el texto actual del valor
                var valorActual = this.innerText;

                // Obtén el nombre de la columna desde el id del elemento
                var columna = this.id;

                // Crea un input para editar
                var inputEdicion = document.createElement('input');
                inputEdicion.type = 'text';
                inputEdicion.value = valorActual;

                // Reemplaza el elemento "valor" con el input de edición
                this.innerText = '';
                this.appendChild(inputEdicion);

                // Poner el foco en el input de edición
                inputEdicion.focus();

                // Guardar los cambios al presionar Enter en el input de edición
                inputEdicion.addEventListener('keyup', function (event) {
                    if (event.key === 'Enter') {
                        // Obtén el nuevo valor del input de edición
                        var nuevoValor = inputEdicion.value;

                        // Realizar la actualización en el mismo archivo PHP
                        var productoId = this.parentNode.getAttribute('data-producto-id');
                        var url = 'administrador.php?editarProducto=' + productoId;
                        var data = 'nuevoValor=' + nuevoValor + '&columna=' + columna;

                        // Realizar una solicitud POST utilizando AJAX
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', url, true);
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhr.onreadystatechange = function () {
                            if (xhr.readyState === 4 && xhr.status === 200) {
                                // Restaurar el elemento original con el nuevo valor
                                var parentElement = inputEdicion.parentNode;
                                parentElement.innerText = '';
                                parentElement.textContent = nuevoValor;
                                parentElement.classList.remove('editing');
                            }
                        };
                        xhr.send(data);
                    }
                });

                // Cancelar la edición al presionar Esc en el input de edición
                inputEdicion.addEventListener('keyup', function (event) {
                    if (event.key === 'Escape') {
                        // Restaurar el elemento original sin realizar la actualización
                        var parentElement = inputEdicion.parentNode;
                        parentElement.innerText = valorActual;
                        parentElement.classList.remove('editing');
                    }
                });
            }
        });
    }
}

function administradorUsuarios() {
    // Obtén todos los elementos con la clase "editable"
    let editables = document.getElementsByClassName('editableUsuario');

    // Agrega un controlador de eventos a cada elemento editable
    for (var i = 0; i < editables.length; i++) {
        editables[i].addEventListener('click', function () {
            if (!this.classList.contains('editing')) {
                // Marcar el elemento como en edición
                this.classList.add('editing');

                // Obtén el texto actual del valor
                var valorActual = this.innerText;

                // Obtén el nombre de la columna desde el id del elemento
                var columna = this.id;

                // Crea un input para editar
                var inputEdicion = document.createElement('input');
                inputEdicion.type = 'text';
                inputEdicion.value = valorActual;

                // Reemplaza el elemento "valor" con el input de edición
                this.innerText = '';
                this.appendChild(inputEdicion);

                // Poner el foco en el input de edición
                inputEdicion.focus();

                // Guardar los cambios al presionar Enter en el input de edición
                inputEdicion.addEventListener('keyup', function (event) {
                    if (event.key === 'Enter') {
                        // Obtén el nuevo valor del input de edición
                        var nuevoValor = inputEdicion.value;

                        // Realizar la actualización en el mismo archivo PHP
                        var usuarioId = this.parentNode.getAttribute('data-usuario-id');
                        var url = 'administrador.php?editarUsuario=' + usuarioId;
                        var data = 'nuevoValor=' + nuevoValor + '&columna=' + columna;

                        // Realizar una solicitud POST utilizando AJAX
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', url, true);
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhr.onreadystatechange = function () {
                            if (xhr.readyState === 4 && xhr.status === 200) {
                                // Restaurar el elemento original con el nuevo valor
                                var parentElement = inputEdicion.parentNode;
                                parentElement.innerText = '';
                                parentElement.textContent = nuevoValor;
                                parentElement.classList.remove('editing');

                            }
                        };
                        xhr.send(data);
                    }
                });

                // Cancelar la edición al presionar Esc en el input de edición
                inputEdicion.addEventListener('keyup', function (event) {
                    if (event.key === 'Escape') {
                        // Restaurar el elemento original sin realizar la actualización
                        var parentElement = inputEdicion.parentNode;
                        parentElement.innerText = valorActual;
                        parentElement.classList.remove('editing');
                    }
                });
            }
        });
    }
}

function administradorPublicidades() {
    // Obtén todos los elementos con la clase "editable"
    let editables = document.getElementsByClassName('editablePublicidad');

    // Agrega un controlador de eventos a cada elemento editable
    for (var i = 0; i < editables.length; i++) {
        editables[i].addEventListener('click', function () {
            if (!this.classList.contains('editing')) {
                // Marcar el elemento como en edición
                this.classList.add('editing');

                // Obtén el texto actual del valor
                var valorActual = this.innerText;

                // Obtén el nombre de la columna desde el id del elemento
                var columna = this.id;

                // Crea un input para editar
                var inputEdicion = document.createElement('input');
                inputEdicion.type = 'text';
                inputEdicion.value = valorActual;

                // Reemplaza el elemento "valor" con el input de edición
                this.innerText = '';
                this.appendChild(inputEdicion);

                // Poner el foco en el input de edición
                inputEdicion.focus();

                // Guardar los cambios al presionar Enter en el input de edición
                inputEdicion.addEventListener('keyup', function (event) {
                    if (event.key === 'Enter') {
                        // Obtén el nuevo valor del input de edición
                        var nuevoValor = inputEdicion.value;

                        // Realizar la actualización en el mismo archivo PHP
                        var publicidadId = this.parentNode.getAttribute('data-publicidad-id');
                        var url = 'cuenta.php?editarPublicidad=' + publicidadId;
                        var data = 'nuevoValor=' + nuevoValor + '&columna=' + columna;

                        // Realizar una solicitud POST utilizando AJAX
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', url, true);
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhr.onreadystatechange = function () {
                            if (xhr.readyState === 4 && xhr.status === 200) {
                                // Restaurar el elemento original con el nuevo valor
                                var parentElement = inputEdicion.parentNode;
                                parentElement.innerText = '';
                                parentElement.textContent = nuevoValor;
                                parentElement.classList.remove('editing');

                            }
                        };
                        xhr.send(data);
                    }
                });

                // Cancelar la edición al presionar Esc en el input de edición
                inputEdicion.addEventListener('keyup', function (event) {
                    if (event.key === 'Escape') {
                        // Restaurar el elemento original sin realizar la actualización
                        var parentElement = inputEdicion.parentNode;
                        parentElement.innerText = valorActual;
                        parentElement.classList.remove('editing');
                    }
                });
            }
        });
    }
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