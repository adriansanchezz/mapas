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





function enviarDatosRegistro() {
    var xhr = new XMLHttpRequest();
    var url = "registro.php?registrar";
    var formData = new FormData(document.getElementById("formularioRegistro"));

    xhr.open("POST", url, true);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Aquí puedes hacer algo con la respuesta del servidor
            console.log(xhr.responseText);
        }
    };

    xhr.send(formData);
}
function validarFormulario() {
    var username = document.getElementById('username').value;
    var correo = document.getElementById('correo').value;
    var telefono = document.getElementById('telefono').value;
    var password = document.getElementById('password').value;
    var password2 = document.getElementById('password2').value;

    // Limpia los mensajes de error
    document.getElementById('errorUsername').textContent = '';
    document.getElementById('errorCorreo').textContent = '';
    document.getElementById('errorTelefono').textContent = '';
    document.getElementById('errorPassword').textContent = '';
    document.getElementById('errorPassword2').textContent = '';

    // Variables para verificar si hay errores
    var hayErrores = false;

    // Valida el nombre de usuario (debe tener al menos 3 caracteres)
    if (username.length < 3) {
        document.getElementById('errorUsername').textContent = 'El nombre de usuario debe tener al menos 3 caracteres';
        hayErrores = true;
    }

    // Valida el correo electrónico
    var correoRegex = /^\S+@\S+\.\S+$/;
    if (!correoRegex.test(correo)) {
        document.getElementById('errorCorreo').textContent = 'El correo electrónico no es válido';
        hayErrores = true;
    }

    // Valida el número de teléfono (debe tener 10 dígitos)
    var telefonoRegex = /^\d{9}$/;
    if (!telefonoRegex.test(telefono)) {
        document.getElementById('errorTelefono').textContent = 'El número de teléfono debe tener 9 dígitos';
        hayErrores = true;
    }

    // Valida la contraseña (debe tener al menos 6 caracteres)
    if (password.length < 6) {
        document.getElementById('errorPassword').textContent = 'La contraseña debe tener al menos 6 caracteres';
        hayErrores = true;
    }

    // Valida que las contraseñas coincidan
    if (password !== password2) {
        document.getElementById('errorPassword2').textContent = 'Las contraseñas no coinciden';
        hayErrores = true;
    }

    if (hayErrores) {
        return false;
    } else {
        enviarDatosRegistro();
        return true; // También puedes cambiar esto a true si deseas que el formulario se envíe de forma tradicional
    }
}