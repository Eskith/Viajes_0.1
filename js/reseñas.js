$(document).ready(function() {
    let minFinal;
    let minInicio;
    let url = "../controller/accessController.php";
    let usuario;
    let respuesta = "";

    $("#logout").css("display", "none");
    $("#usuario_logeado").css("display", "none");
    $("#formulario_comentario").css("display", "none");


    // comprueba se esta conectado
    data = {
        "service": "usuarioLogeado"
    };

    $.ajax({
        type: "POST",
        url: url,
        data: data,
        dataType: "JSON",
        success: function(response) {
            if (response.status == "logeado") {
                switch (response.tipo) {
                    case "0":
                        $("#usuario_logeado").css("display", "");
                        $("#usuario_logeado").html(response.usuario);
                        $("#usuario_comentario").html(response.usuario);
                        usuario = response.usuario;
                        $("#formulario_comentario").css("display", "");
                        $("#login").css("display", "none");
                        $("#Sign").css("display", "none");
                        $("#logout").css("display", "");
                        mostratabla("usu");
                        break;
                    case "1":
                        $("#usuario_logeado").css("display", "");
                        $("#usuario_logeado").html(response.usuario);
                        $("#cabecera_Reseña").html("Administrador");
                        usuario = response.usuario;
                        $("#login").css("display", "none");
                        $("#Sign").css("display", "none");
                        $("#logout").css("display", "");
                        mostratabla("admin");
                        break;
                    default:
                        $("#usuario_logeado").css("display", "");
                        $("#usuario_logeado").html(response.usuario);
                        $("#login").css("display", "none");
                        $("#Sign").css("display", "none");
                        $("#logout").css("display", "");
                        mostratabla("usu");
                        break;
                }
            } else {
                mostratabla("usu");
            }
        }
    });

    //---------------------------MODAL LOGIN-----------------------------------------------------
    $("#login-form").submit(function(e) {
        e.preventDefault();

        let form = $("#login-form").serializeArray();

        form = form.concat({ name: "service", value: "login" }
            // { name: "service", value: "palabra" }
        );

        $.ajax({
            data: form,
            type: "POST",
            url: url,
            dataType: "JSON",

            success: function(response) {
                $("#modal_Login").modal("hide");

                if (response.status == "Fail") {
                    $("#mensaje_Status_Fail").modal("show");
                    $("#statusFail").html(response.msg);
                } else {
                    $("#mensaje_Status_Success").modal("show");
                    $("#status").html(response.status + " success");

                    switch (response.tipo) {
                        case "0":
                            $("#usuario_logeado").css("display", "");
                            $("#usuario_logeado").html(response.usuario);
                            $("#usuario_comentario").html(response.usuario);
                            usuario = response.usuario;
                            $("#formulario_comentario").css("display", "");
                            $("#login").css("display", "none");
                            $("#Sign").css("display", "none");
                            $("#logout").css("display", "");
                            mostratabla("usu");
                            break;
                        case "1":
                            $("#usuario_logeado").css("display", "");
                            $("#usuario_logeado").html(response.usuario);
                            $("#cabecera_Reseña").html("Administrador");
                            $("#modal_Login").modal("show");
                            usuario = response.usuario;
                            $("#login").css("display", "none");
                            $("#Sign").css("display", "none");
                            $("#logout").css("display", "");
                            mostratabla("admin");
                            break;
                        default:
                            $("#usuario_logeado").css("display", "");
                            $("#usuario_logeado").html(response.usuario);
                            $("#login").css("display", "none");
                            $("#Sign").css("display", "none");
                            $("#logout").css("display", "");
                            mostratabla("usu");
                            break;
                    }
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
                    $("#status").html(response.msg);
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

        form = form.concat({ name: "service", value: "recovery" }
            // { name: "service", value: "palabra" }
        );

        $.ajax({
            data: form,
            type: "POST",
            url: url,
            dataType: "JSON",

            success: function(response) {
                if (response == "Email o pass incorrecta") {
                    $("#mensaje_Status_Fail").modal("show");
                    $("#statusFail").html("correo incorrecto");
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
                    if (response.status == "success") {
                        $("#mensaje_Status_Success").modal("show");
                        $("#status").html(response.msg);
                    } else {
                        $("#mensaje_Status_Fail").modal("show");
                        $("#statusFail").html(response.msg);
                    }
                }
            });

        } else {
            $("#mensaje_Status_Fail").modal("show");
            $("#statusFail").html("Se ha cacudaco el tiempo");
        }

    });
    //--------------------------- Comnetario -------------------------------------------------------
    $("#cometario_form").submit(function(e) {
        e.preventDefault();

        let form = $("#cometario_form").serializeArray();
        form = form.concat({ name: "service", value: "comentar" }, { name: "usuario", value: usuario });
        url = "../controller/comentarioController.php";

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
                    $("#status").html(response.msg);
                }
                mostratabla("usu");
            }
        });

    });
    $("#logout").click(function(e) {
        e.preventDefault();
        $("#formulario_comentario").css("display", "none");

        data = {
            "service": "logout"
        };

        $.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType: "JSON",
            success: function(response) {
                if (response.status == "success") {
                    $("#status").html(response.msg);
                    $("#login").css("display", "");
                    $("#Sign").css("display", "");
                    $("#logout").css("display", "none");
                    $("#usuario_logeado").css("display", "none");
                    $("#usuario_logeado").html("");
                    location.reload();
                }
            }
        });
    });
});

//-------------- FIN DEL LOAD ---------------------------------------------------------

function mostratabla($tipo) {


    data = {
        "service": "tablaReseñas",
        "tipo": $tipo
    };


    let datatable = "";

    // muestra la tabla
    $.ajax({
        type: "POST",
        url: "../controller/comentarioController.php",
        data: data,
        dataType: "JSON",
        success: function(response) {
            respuesta = response.data;

            $('#containe_table').empty().append(`<table id="tabla" class="table table-bordered" width="100%"></table>`);
            (datatable != '' ? datatable.clear().destroy() : '');

            $('#tabla').DataTable({
                data: response.data,
                columns: response.columns,
                columnDefs: response.columnsDefs,
                "scrollX": true,
                "destroy": true,
                responsive: true,
                "searching": true,
                "pageLength": 14,
                // dom: 'Bfrtip',

            });

        }
    });
}

//------------

function editarComentario(id) {

    alert(id);
    let target_data = respuesta[id - 1];
    console.log(target_data);
    $("#referencia").html(id);
    $("#referencia_Edit").val(target_data.Referencia);
    $("#puntuacion_Edit").val(target_data.Puntuacion);
    $("#destino_Edit").val(target_data.Destino);
    $("#comentario_Edit").val(target_data.Comentario);
}

$("#cometario_Edit_form").submit(function(e) {
    e.preventDefault();

    let form = $("#cometario_Edit_form").serializeArray();
    form = form.concat({ name: "service", value: "edit" });
    url = "../controller/comentarioController.php";

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
                mostratabla("admin");
            }
        },

    });
});

function eliminarRegistro(id) {
    url = "../controller/comentarioController.php";

    data = { "id": id, "service": "delete" };

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
                mostratabla("admin");
            }
        },

    });
}

function config() {
    location.href = "./config.html";
}