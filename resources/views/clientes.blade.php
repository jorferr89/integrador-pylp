<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <title>Integrador Rodriguez Jorge</title>

</head>
<body>

<div class="container" style="margin-top: 50px;">

    <h4 class="text-center">Laravel ABM Firebase - Integrador Rodriguez Jorge</h4><br>

    <h5>Agregar Cliente</h5>
    <div class="card card-default">
        <div class="card-body">
            <form id="agregarCliente" class="form-inline" method="POST" action="">
                <div class="form-group mb-2">
                    <label for="name" class="sr-only">Nombre</label>
                    <input id="name" type="text" class="form-control" name="name" placeholder="Nombre"
                           required autofocus>
                </div>
                <div class="form-group mx-sm-3 mb-2">
                    <label for="email" class="sr-only">Correo Electrónico</label>
                    <input id="email" type="email" class="form-control" name="email" placeholder="Correo Electrónico"
                           required autofocus>
                </div>
                <button id="submitCliente" type="button" class="btn btn-primary mb-2">Guardar</button>
            </form>
        </div>
    </div>

    <br>

    <h5>Listado de Clientes</h5>
    <table class="table table-bordered">
        <tr>
            <th>Nombre</th>
            <th>Correo Electrónico</th>
            <th>Acciones</th>
        </tr>
        <tbody id="tbody">

        </tbody>
    </table>
</div>

<!-- Update Model -->
<form action="" method="POST" class="clientes-update-record-model form-horizontal">
    <div id="update-modal" data-backdrop="static" data-keyboard="false" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="width:55%;">
            <div class="modal-content" style="overflow: hidden;">
                <div class="modal-header">
                    <h4 class="modal-title" id="custom-width-modalLabel">Actualizar</h4>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body" id="updateBody">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light"
                            data-dismiss="modal">Cerrar
                    </button>
                    <button type="button" class="btn btn-success updateCliente">Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Delete Model -->
<form action="" method="POST" class="clientes-remove-record-model">
    <div id="remove-modal" data-backdrop="static" data-keyboard="false" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered" style="width:55%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="custom-width-modalLabel">Eliminar</h4>
                    <button type="button" class="close remove-data-from-delete-form" data-dismiss="modal"
                            aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro de eliminar el cliente?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect remove-data-from-delete-form"
                            data-dismiss="modal">Cerrar
                    </button>
                    <button type="button" class="btn btn-danger waves-effect waves-light deleteRecord">Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>


{{-- Configuración de Firebase--}}
<script src="https://code.jquery.com/jquery-3.4.0.min.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.10.1/firebase.js"></script>
<script>
    // Initialize Firebase
    var config = {
        apiKey: "{{ config('services.firebase.api_key') }}",
        authDomain: "{{ config('services.firebase.auth_domain') }}",
        databaseURL: "{{ config('services.firebase.database_url') }}",
        storageBucket: "{{ config('services.firebase.storage_bucket') }}",
    };
    firebase.initializeApp(config);

    var database = firebase.database();

    var lastIndex = 0;

    // Get Data
    firebase.database().ref('clientes/').on('value', function (snapshot) {
        var value = snapshot.val();
        var htmls = [];
        $.each(value, function (index, value) {
            if (value) {
                htmls.push('<tr>\
        		<td>' + value.name + '</td>\
        		<td>' + value.email + '</td>\
        		<td><button data-toggle="modal" data-target="#update-modal" class="btn btn-info updateData" data-id="' + index + '">Actualizar</button>\
        		<button data-toggle="modal" data-target="#remove-modal" class="btn btn-danger removeData" data-id="' + index + '">Eliminar</button></td>\
        	</tr>');
            }
            lastIndex = index;
        });
        $('#tbody').html(htmls);
        $("#submitUser").removeClass('desabled');
    });

    // agregar cliente
    $('#submitCliente').on('click', function () {
        var values = $("#agregarCliente").serializeArray();
        var name = values[0].value;
        var email = values[1].value;
        var userID = lastIndex + 1;
        if (name == "") {
            alert ("El campo nombre es obligatorio");
            return 0;
        }

        if (email == "") {
            alert ("El campo correo electrónico es obligatorio");
            return 0;
        }
        console.log(values);

        firebase.database().ref('clientes/' + userID).set({
            name: name,
            email: email,
        });

        lastIndex = userID;
        $("#agregarCliente input").val("");
    });

    // actualizar
    var updateID = 0;
    $('body').on('click', '.updateData', function () {
        updateID = $(this).attr('data-id');
        firebase.database().ref('clientes/' + updateID).on('value', function (snapshot) {
            var values = snapshot.val();
            var updateData = '<div class="form-group">\
		        <label for="first_name" class="col-md-12 col-form-label">Name</label>\
		        <div class="col-md-12">\
		            <input id="first_name" type="text" class="form-control" name="name" value="' + values.name + '" required autofocus>\
		        </div>\
		    </div>\
		    <div class="form-group">\
		        <label for="last_name" class="col-md-12 col-form-label">Email</label>\
		        <div class="col-md-12">\
		            <input id="last_name" type="text" class="form-control" name="email" value="' + values.email + '" required autofocus>\
		        </div>\
		    </div>';

            $('#updateBody').html(updateData);
        });
    });

    $('.updateCliente').on('click', function () {
        var values = $(".clientes-update-record-model").serializeArray();
        var postData = {
            name: values[0].value,
            email: values[1].value,
        };

        if (values[0].value == "") {
            alert ("El campo nombre es obligatorio");
            return 0;
        }

        if (values[1].value == "") {
            alert ("El campo correo electrónico es obligatorio");
            return 0;
        }

        var updates = {};
        updates['/clientes/' + updateID] = postData;

        firebase.database().ref().update(updates);

        $("#update-modal").modal('hide');
    });

    // Remove Data
    $("body").on('click', '.removeData', function () {
        var id = $(this).attr('data-id');
        $('body').find('.clientes-remove-record-model').append('<input name="id" type="hidden" value="' + id + '">');
    });

    $('.deleteRecord').on('click', function () {
        var values = $(".clientes-remove-record-model").serializeArray();
        var id = values[0].value;
        firebase.database().ref('clientes/' + id).remove();
        $('body').find('.clientes-remove-record-model').find("input").remove();
        $("#remove-modal").modal('hide');
    });
    $('.remove-data-from-delete-form').click(function () {
        $('body').find('.clientes-remove-record-model').find("input").remove();
    });
</script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>
</html>