$(document).ready(function() {
    let minFinal;
    let minInicio;
    let url = "./controller/accessController.php";
    let datosUsuarios;
    let datosHotelera;
    let datosAerolina;
    let target_data;

    ocultar();


    // comprueba si esta logeado
    let data = {
        "service": "usuarioLogeado"
    };

    $.ajax({
        type: "POST",
        url: url,
        data: data,
        dataType: "JSOn",
        success: function(response) {
            if (response.status == "logeado") {
                $("#usuario_logeado").css("display", "");
                $("#usuario_logeado").html(response.usuario);
                $("#login").css("display", "none");
                $("#Sign").css("display", "none");
                $("#logout").css("display", "");
                if (response.tipo == "1") {
                    $("#administrador").css("display", "");
                    mostratabla("#table_Usuario", "usuarios");
                    mostratabla("#table_Aerolinea", "aerolinea");
                    mostratabla("#table_Hotelera", "hotelera");
                }
            }
        }
    });


    //---------------------------MODAL LOGIN-----------------------------------------------------

    $("#login-form").submit(function(e) {
        e.preventDefault();

        let form = $("#login-form").serializeArray();
        form = form.concat({ name: "service", value: "login" }

        );

        $.ajax({
            data: form,
            type: "POST",
            url: url,
            dataType: "JSON",

            success: function(response) {

                if (response.status == "Fail") {
                    $("#mensaje_Status_Fail").modal("show");
                    $("#statusFail").html(response.msg);
                } else {
                    $("#modal_Login").modal("hide");
                    $("#mensaje_Status_Success").modal("show");
                    $("#status").html(response.status + " success");
                    $("#usuario_logeado").css("display", "");
                    $("#usuario_logeado").html(response.usuario);
                    $("#login").css("display", "none");
                    $("#Sign").css("display", "none");
                    $("#logout").css("display", "");
                }

                if (response.login == "administrador") {
                    $("#administrador").css("display", "");
                    mostratabla("#table_Usuario", "usuarios");
                    mostratabla("#table_Aerolinea", "aerolinea");
                    mostratabla("#table_Hotelera", "hotelera");
                }

            },
        });
    });
    //---------------------------MODAL SIGN-----------------------------------------------------

    $("#form-Sign").submit(function(e) {
        e.preventDefault();

        let form = $("#form-Sign").serializeArray();
        form = form.concat({ name: "service", value: "register" });

        $.ajax({
            data: form,
            type: "POST",
            url: url,
            dataType: "JSON",

            success: function(response) {

                $("#modal_Sing").modal("hide");
                if (response.status == "Fail") {
                    $("#mensaje_Status_Fail").modal("show");
                    $("#statusFail").html(response.msg);
                } else {
                    $("#mensaje_Status_Success").modal("show");
                    $("#status").html(response.status + " success");
                }
            },

        });
    });

    //---------------------------MODAL RECOVERY-----------------------------------------------------

    $("#recovery-form").submit(function(e) {
        e.preventDefault();
        let responseTime = new Date;
        minInicio = responseTime.getMinutes();
        minFinal = responseTime.getMinutes() + 2;

        let form = $("#recovery-form").serializeArray();

        form = form.concat({ name: "service", value: "recovery" });

        $.ajax({
            data: form,
            type: "POST",
            url: url,
            dataType: "JSON",

            success: function(response) {

                if (response.status == "Fail") {
                    $("#mensaje_Status_Fail").modal("show");
                    $("#statusFail").html(response.msg);
                } else {
                    $("#mensaje_Status_Success").modal("show");
                    $("#status").html(response.status + " success");
                }
            },
        });
    });

    //---------------------------MODAL RECOVERY CHANGE-----------------------------------------------------
    $("#recovery-change-form").submit(function(e) {
        e.preventDefault();
        let ahora = new Date;

        let form = $("#recovery-change-form").serializeArray();

        form = form.concat({ name: "service", value: "change" });

        if (ahora.getMinutes() <= minFinal && ahora.getMinutes() >= minInicio) {

            $.ajax({
                type: "POST",
                url: url,
                data: form,
                dataType: "JSON",
                success: function(response) {
                    if (response.status == "Fail") {
                        $("#mensaje_Status_Fail").modal("show");
                        $("#statusFail").html(response.msg);
                    } else {
                        $("#mensaje_Status_Success").modal("show");
                        $("#status").html(response.msg + " success");
                    }
                }
            });

        } else {
            $("#mensaje_Status_Fail").modal("show");
            $("#statusFail").html("Se ha Caducado el tiempo");
        }

    });
    //---------------BOTONERA-----------------------------------------------------

    $("#usuario_logeado").click(function(e) {
        location.href = "./view/config.html";
    });
    $("#logout").click(function(e) {
        e.preventDefault();
        let data = {
            "service": "logout"
        };

        $.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType: "JSON",
            success: function(response) {
                if (response.status == "success") {
                    $("#mensaje_Status_Success").modal("show");
                    $("#status").html(response.msg);
                    $("#login").css("display", "");
                    $("#Sign").css("display", "");
                    $("#logout").css("display", "none");
                    $("#usuario_logeado").css("display", "none");
                    $("#usuario_logeado").html("");
                    $("#administrador").css("display", "none");
                }
            }
        });
    });

    $(".modal-close").click(function(e) {
        e.preventDefault();
        $(".modal-backdrop").remove();
    });
    $("#button_success_modal").click(function(e) {
        e.preventDefault();
        $(".modal-backdrop").remove();
    });
    $("#button_fail_modal").click(function(e) {
        e.preventDefault();
        $(".modal-backdrop").remove();
    });


    //---------------------------- Fin del Load --------------------------------------------------------------
});

//--------------------FUNCIONES-----------------------------------
function mostratabla(id, tipo) {

    let data = {
        "service": "mostrarTablas",
        "tipo": tipo
    };


    let datatable = "";

    // muestra la tabla
    $.ajax({
        type: "POST",
        url: "./controller/adminController.php",
        data: data,
        dataType: "JSON",
        success: function(response) {
            switch (tipo) {
                case "usuarios":
                    datosUsuarios = response.data;
                    break;
                case "aerolinea":
                    datosAerolina = response.data;
                    break;
                case "hotelera":
                    datosHotelera = response.data;
                    break;
            }

            // $('#containe_table').empty().append(`<table id="tabla" class="table table-bordered" width="100%"></table>`);
            // (datatable != '' ? datatable.clear().destroy() : '');

            $(id).DataTable({
                data: response.data,
                columns: response.columns,
                columnDefs: response.columnsDefs,
                "scrollX": true,
                "destroy": true,
                responsive: true,
                "searching": true,
                "pageLength": 8,
                dom: 'Bftip',
                /*
                * Etiquetas del DOM
                * Q : Aplica un Filtro
                * B : Los botones
                * f : Search
                * t : pone la paginacion abajo
                * p : pone la paginacion
                * i : mustra la info de los registros totales y cuantos muestra
                * H : cabeceras especiales
                */
                "buttons": [{
                    // Botón para filtrar las columnas
                    extend: 'colvis',
                    className: 'btn btn-secundary me-2 rounded'
                }, {
                    // Botón para Excel
                    extend: 'excel',
                    className: 'btn btn-success me-2 rounded'
                }, {
                    // Botón para Pdf
                    extend: 'pdf',
                    className: 'btn btn-danger'
                }],
                // traduccion
                language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "infoEmpty": "Mostrando 0 a 0 de 0 Registros",
                    "infoFiltered": "(Filtrado de _MAX_ total registros)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Registros",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": {
                        "first": "Primero",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },

            });

        }
    });
}

function editar(id, num) {
    let index;
    let target_data;

    switch (num) {
        case 1:
            index = datosUsuarios.findIndex(datosUsuarios => datosUsuarios.Usuario == id);

            target_data = datosUsuarios[index];
            $("#dni_Usuario_edit").val(target_data.DNI);
            $("#nombre_Usuario_edit").val(target_data.Nombre);
            $("#apellidos_Usuario_edit").val(target_data.Apellidos);
            $("#pais_Usuario_edit").val(target_data.Pais);
            $("#telefono_Usuario_edit").val(target_data.Telefono);
            $("#email_Usuario_edit").val(target_data.Email);
            $("#usuario_Usuario_edit").val(target_data.Usuario);
            $("#password_Usuario_edit").val(target_data.Password);
            $("#puntos_Usuario_edit").val(target_data.Puntos);

            break;

        case 2:
            index = datosAerolina.findIndex(datosAerolina => datosAerolina.Usuario == id);

            target_data = datosAerolina[index];

            $("#nombre_aerolinea_edit").val(target_data.Nombre);
            $("#telefono_edit").val(target_data.Telefono);
            $("#email_aerolinea_edit").val(target_data.Email);
            $("#usuario_aerolinea_edit").val(target_data.Usuario);
            $("#password_aerolinea_edit").val(target_data.Password);
            break;

        case 3:
            index = datosHotelera.findIndex(datosHotelera => datosHotelera.Usuario == id);

            target_data = datosHotelera[index];
            $("#nombre_hotelera_edit").val(target_data.Nombre);
            $("#telefono_hotelera_edit").val(target_data.Telefono);
            $("#email_hotelera_edit").val(target_data.Email);
            $("#usuario_hotelera_edit").val(target_data.Usuario);
            $("#password_hotelera_edit").val(target_data.Password);
            break;

    }


}

function eliminar(id, tipo) {
    url = "./controller/adminController.php";


    let data = { "id": id, "service": "delete", "tipo": tipo };

    $.ajax({
        data: data,
        type: "POST",
        url: url,
        dataType: "JSON",

        success: function(response) {

            if (response.status == "Fail") {
                $("#mensaje_Status_Fail").modal("show");
                $("#statusFail").html(response);
            } else {
                $("#mensaje_Status_Success").modal("show");
                $("#status").html(response);
                location.reload();
            }
        },

    });
}


$("#form_edit_usuario").submit(function(e) {
    e.preventDefault();

    let form = $("#form_edit_usuario").serializeArray();
    form = form.concat({ name: "service", value: "edit" }, { name: "tipo", value: 1 });
    url = "./controller/adminController.php";

    $.ajax({
        data: form,
        type: "POST",
        url: url,
        dataType: "JSON",

        success: function(response) {

            if (response.status == "Fail") {
                $("#mensaje_Status_Fail").modal("show");
                $("#statusFail").html(response.msg);
            } else {
                $("#mensaje_Status_Success").modal("show");
                $("#status").html(response.msg);
                mostratabla("#table_Usuario", "usuarios");
            }
        },

    });
});


$("#form_edit_aerolinea").submit(function(e) {
    e.preventDefault();

    let form = $("#form_edit_aerolinea").serializeArray();
    form = form.concat({ name: "service", value: "edit" }, { name: "tipo", value: 2 });
    url = "./controller/adminController.php";

    $.ajax({
        data: form,
        type: "POST",
        url: url,
        dataType: "JSON",

        success: function(response) {

            if (response.status == "Fail") {
                $("#mensaje_Status_Fail").modal("show");
                $("#statusFail").html(response.msg);
            } else {
                $("#mensaje_Status_Success").modal("show");
                $("#status").html(response.msg);
                mostratabla("#table_Aerolinea", "aerolinea");
            }
        },

    });
});

$("#form_edit_hotelera").submit(function(e) {
    e.preventDefault();

    let form = $("#form_edit_hotelera").serializeArray();
    form = form.concat({ name: "service", value: "edit" }, { name: "tipo", value: 3 });
    url = "./controller/adminController.php";

    $.ajax({
        data: form,
        type: "POST",
        url: url,
        dataType: "JSON",

        success: function(response) {

            if (response.status == "Fail") {
                $("#mensaje_Status_Fail").modal("show");
                $("#statusFail").html(response.msg);
            } else {
                $("#mensaje_Status_Success").modal("show");
                $("#status").html(response.msg);
                mostratabla("#table_Hotelera", "hotelera");
            }
        },

    });

});

$("#form_Sing_Admin").submit(function(e) {
    e.preventDefault();

    let form = $("#form_Sing_Admin").serializeArray();
    form = form.concat({ name: "service", value: "añadir" });
    url = "./controller/adminController.php";

    $.ajax({
        data: form,
        type: "POST",
        url: url,
        dataType: "JSON",

        success: function(response) {

            if (response.status == "Fail") {
                $("#mensaje_Status_Fail").modal("show");
                $("#statusFail").html(response.msg);
            } else {
                $("#mensaje_Status_Success").modal("show");
                $("#status").html(response.msg);
                mostratabla("#table_Usuario", "usuarios");
                mostratabla("#table_Aerolinea", "aerolinea");
                mostratabla("#table_Hotelera", "hotelera");
            }
        },

    });
});

function singAdmin() {
    let tipo = $("#sign_Tipo_Admin").val();

    switch (tipo) {
        case "usuario":
            $("#dni_display").attr("style", "display : block");
            $("#nombre_display").attr("style", "display : block");
            $("#apellidos_display").attr("style", "display : block");
            $("#pais_display").attr("style", "display : block");
            $("#telefono_display").attr("style", "display : block");
            $("#email_display").attr("style", "display : block");
            $("#usuario_display").attr("style", "display : block");
            $("#pass_display").attr("style", "display : block");
            $("#puntos_display").attr("style", "display : block");
            $("#rol_display").attr("style", "display : block");
            break;

        case "aerolinea":
        case "hotelera":
            $("#dni_display").attr("style", "display : none");
            $("#apellidos_display").attr("style", "display : none");
            $("#pais_display").attr("style", "display : none");
            $("#puntos_display").attr("style", "display : none");
            $("#rol_display").attr("style", "display : none");

            $("#nombre_display").attr("style", "display : block");
            $("#telefono_display").attr("style", "display : block");
            $("#email_display").attr("style", "display : block");
            $("#usuario_display").attr("style", "display : block");
            $("#pass_display").attr("style", "display : block");
            break;

    }
}

function ocultar() {
    $("#modal-login").css("display", "none");
    $("#modal-Sign").css("display", "none");
    $("#logout").css("display", "none");
    $("#usuario_logeado").css("display", "none");
    $("#modal-recovery-change").css("display", "none");
    $("#modal-recovery").css("display", "none");
    $("#administrador").css("display", "none");
    $("#dni_display").attr("style", "display : none");
    $("#nombre_display").attr("style", "display : none");
    $("#apellidos_display").attr("style", "display : none");
    $("#pais_display").attr("style", "display : none");
    $("#telefono_display").attr("style", "display : none");
    $("#email_display").attr("style", "display : none");
    $("#usuario_display").attr("style", "display : none");
    $("#pass_display").attr("style", "display : none");
    $("#puntos_display").attr("style", "display : none");
    $("#rol_display").attr("style", "display : none");
}