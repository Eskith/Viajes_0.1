$(document).ready(function() {
    let url = "../controller/accessController.php";
    let usuario;
    var tablaVuelos = "";
    var tablaVuelosAerolinea = "";
    let numPlazas = "";
    let numPlazasvips = "";

    ocultarDislay();

    // comprueba se esta conectado
    let data = {
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
                        // usuario echo
                        $("#usuario_logeado").css("display", "");
                        $("#usuario_logeado").html(response.usuario);
                        usuario = response.usuario;
                        $("#login").css("display", "none");
                        $("#Sign").css("display", "none");
                        $("#logout").css("display", "");
                        mostratabla("vuelos_Reservar", "#tabla_vuelos");
                        $("#usuario_display").css("display", "");
                        mostratabla("reservas", "#tabla_reserva");
                        break;

                    case "1":
                        //----
                        //administrador
                        $("#usuario_logeado").css("display", "");
                        $("#usuario_logeado").html(response.usuario);
                        usuario = response.usuario;
                        $("#login").css("display", "none");
                        $("#Sign").css("display", "none");
                        $("#logout").css("display", "");

                        $("#admin_display").css("display", "");
                        mostratabla("vuelos", "#tabla_vuelos");
                        mostratabla("vuelos_Admin", "#tabla_vuelos_Admin");
                        mostratabla("reservas_Admin", "#tabla_Reservas_Admin");

                        break;

                    case "aerolinea":
                        $("#usuario_logeado").css("display", "");
                        $("#usuario_logeado").html(response.usuario);
                        usuario = response.usuario;
                        $("#login").css("display", "none");
                        $("#Sign").css("display", "none");
                        $("#logout").css("display", "");
                        $("#aerolinea_display").css("display", "");
                        mostratabla("vuelos", "#tabla_vuelos");
                        mostratabla("vuelos_Aerolinea", "#tabla_vuelos_aerolinea");
                        mostratabla("reservas_Aerolinea", "#table_aerolinea_reservas");
                        break;
                }
            } else {
                mostratabla("vuelos", "#tabla_vuelos");

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
                            usuario = response.usuario;
                            $("#login").css("display", "none");
                            $("#Sign").css("display", "none");
                            $("#logout").css("display", "");
                            mostratabla("vuelos_Reservar", "#tabla_vuelos");
                            $("#usuario_display").css("display", "");
                            mostratabla("reservas", "#tabla_reserva");
                            location.reload();
                            break;
                        case "1":
                            $("#usuario_logeado").css("display", "");
                            $("#usuario_logeado").html(response.usuario);
                            $("#cabecera_Comentario").html("Administrador");
                            $("#modal_Login").modal("show");
                            usuario = response.usuario;
                            $("#login").css("display", "none");
                            $("#Sign").css("display", "none");
                            $("#logout").css("display", "");
                            $("#admin_display").css("display", "");
                            mostratabla("vuelos", "#tabla_vuelos");
                            mostratabla("vuelos_Admin", "#tabla_vuelos_Admin");
                            mostratabla("reservas_Admin", "#tabla_Reservas_Admin");
                            break;

                        case "2":
                            $("#usuario_logeado").css("display", "");
                            $("#usuario_logeado").html(response.usuario);
                            usuario = response.usuario;
                            $("#login").css("display", "none");
                            $("#Sign").css("display", "none");
                            $("#logout").css("display", "");
                            $("#aerolinea_display").css("display", "");
                            mostratabla("vuelos", "#tabla_vuelos");
                            mostratabla("vuelos_Aerolinea", "#tabla_vuelos_aerolinea");
                            location.reload();
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
    //--------------------------- Logout--------------------------------------------------------------------------------
    $("#logout").click(function(e) {
        e.preventDefault();
        $("#formulario_comentario").css("display", "none");

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
    $("#button_success_modal").click(function(e) {
        e.preventDefault();
        $(".modal-backdrop").remove();
    });
    $("#button_fail_modal").click(function(e) {
        e.preventDefault();
        $(".modal-backdrop").remove();
    });
    $(".modal-close").click(function(e) {
        e.preventDefault();
        $(".modal-backdrop").remove();
    });
});

//-------------- FIN DEL LOAD ---------------------------------------------------------

function mostratabla(tipo, ubicacion) {

    let data = {
        "service": "mostrartabla",
        "tipo": tipo
    };

    // muestra la tabla
    $.ajax({
        type: "POST",
        url: "../controller/vuelosController.php",
        data: data,
        dataType: "JSON",
        success: function(response) {
            switch (tipo) {
                case "vuelos_Reservar":
                    tablaVuelos = response.data;
                    break;

                case "vuelos_Aerolinea":
                    tablaVuelosAerolinea = response.data;
                    break;

                case "vuelos_Admin":
                    tablaVuelosAerolinea = response.data;
                    break;
            }

            $(ubicacion).DataTable({
                data: response.data,
                columns: response.columns,
                columnDefs: response.columnsDefs,
                "scrollX": true,
                "destroy": true,
                responsive: true,
                "searching": true,
                "pageLength": 14,
                // dom: 'Bfrtip',
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

function reservar(id) {

    let index = tablaVuelos.findIndex(tablaVuelos => tablaVuelos.Vuelo == id);

    let target_data = tablaVuelos[index];
    $("#aerolinea_reserva").val(target_data.Aerolinea);
    $("#matricula_reserva").val(target_data.Vuelo);
    $("#salida_pais_reserva").val(target_data["Salida Pais"]);
    $("#salida_ciudad_reserva").val(target_data["Salida Ciudad"]);
    $("#llegada_pais_reserva").val(target_data["Destino Pais"]);
    $("#llegada_ciudad_reserva").val(target_data["Destino Ciudad"]);
    $("#salida_reserva").val(target_data.Salida);
    $("#llegada_reserva").val(target_data.LLegada);
    $("#precio_reserva").val(target_data.Precio);
    $("#precio2_reserva").val(target_data["Primera Clase Precio"]);
    $("#precio3_reserva").val(target_data["Precio Maleta (Kg)"]);
    $("#kg_max_reserva").val(target_data["Kg Maximo"]);
    numPlazas = target_data["Plazas"];
    numPlazasvips = target_data["Primera Clase"];


}

function editar(id, tipo) {
    let array;
    let matricula;
    let fecha;
    let target_data;
    switch (tipo) {
        
        
        case 0:
            array = id.split("+");
            matricula = array[0];
            fecha = array[1];

            index = tablaVuelosAerolinea.findIndex(tablaVuelosAerolinea => tablaVuelosAerolinea.Matricula == matricula && tablaVuelosAerolinea.Salida == fecha);
            target_data = tablaVuelosAerolinea[index];

            $("#matricula_aerolineaOld").val(target_data.Aerolinea);
            $("#aerolinea_aerolinea").val(target_data.Aerolinea);
            $("#matricula_aerolinea").val(target_data.Matricula);
            $("#salida_pais_aerolinea").val(target_data["Salida Pais"]);
            $("#salida_ciudad_aerolinea").val(target_data["Salida Ciudad"]);
            $("#llegada_pais_aerolinea").val(target_data["Destino Pais"]);
            $("#llegada_ciudad_aerolinea").val(target_data["Destino Ciudad"]);
            $("#salida_aerolinea").val(target_data.Salida);
            $("#salida_aerolineaold").val(target_data.Salida);
            $("#llegada_aerolineaold").val(target_data.LLegada);
            $("#precio_aerolinea").val(target_data.Precio);
            $("#precio2_aerolinea").val(target_data["Primera Clase Precio"]);
            $("#precio3_aerolinea").val(target_data["Precio Maleta (Kg)"]);
            $("#kg_max_aerolinea").val(target_data["Peso Maximo"]);
            $("#billetes_aerolinea").val(target_data.Plazas);
            $("#billetes1_aerolinea").val(target_data["Primera Clase"]);

            break;
        case 1:
            array = id.split("+");
            matricula = array[0];
            fecha = array[1];

            index = tablaVuelosAerolinea.findIndex(tablaVuelosAerolinea => tablaVuelosAerolinea.Vuelo == matricula && tablaVuelosAerolinea.Salida == fecha);
            target_data = tablaVuelosAerolinea[index];

            $("#aerolinea_aerolinea").val(target_data.Aerolinea);
            $("#matricula_aerolinea").val(target_data.Vuelo);
            $("#salida_pais_aerolinea").val(target_data["Salida Pais"]);
            $("#salida_ciudad_aerolinea").val(target_data["Salida Ciudad"]);
            $("#llegada_pais_aerolinea").val(target_data["Destino Pais"]);
            $("#llegada_ciudad_aerolinea").val(target_data["Destino Ciudad"]);
            $("#salida_aerolinea").val(target_data.Salida);
            $("#salida_aerolineaold").val(target_data.Salida);
            $("#llegada_aerolineaold").val(target_data.LLegada);
            $("#precio_aerolinea").val(target_data.Precio);
            $("#precio2_aerolinea").val(target_data["Primera Clase"]);
            $("#precio3_aerolinea").val(target_data["Precio Maleta (Kg)"]);
            $("#kg_max_aerolinea").val(target_data["Peso Max"]);
            $("#billetes_aerolinea").val(target_data.Plazas);
            $("#billetes1_aerolinea").val(target_data["Plazas 1º Clase"]);
            


            break;
    
        default:
            break;
    }

    



}

$("#form_reserva").submit(function(e) {
    e.preventDefault();

    let form = $("#form_reserva").serializeArray();
    form = form.concat({ name: "service", value: "reservar" }, { name: "plazas", value: numPlazas }, { name: "plazasvips", value: numPlazasvips });
    url = "../controller/vuelosController.php";

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
                mostratabla("reservas", "#tabla_reserva");
            }
        },

    });
});

$("#form_edit").submit(function (e) { 
    e.preventDefault();
    let form = $("#form_edit").serializeArray();
    form = form.concat({ name: "service", value: "editar" }, { name: "tipo", value: "0" });
    url = "../controller/vuelosController.php";

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
                location.reload();
            }
        },

    });
});

$("#form_add").submit(function (e) { 
    e.preventDefault();
    let form = $("#form_add").serializeArray();
    form = form.concat({ name: "service", value: "añadir" });
    url = "../controller/vuelosController.php";

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
                location.reload();
            }
        },

    });
});

function eliminar(id, tipo) {
    url = "../controller/vuelosController.php";


    let data = { "id": id,"tipo": tipo , "service": "delete" };

    $.ajax({
        data: data,
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
                mostratabla("vuelos", "#tabla_vuelos");
                mostratabla("vuelos_Aerolinea", "#tabla_vuelos_aerolinea");
                location.reload();
            }
        },

    });
}

function pagar(id) { 
    url = "../controller/vuelosController.php";


    let data = { "id": id, "service": "pay" };

    $.ajax({
        data: data,
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
                mostratabla("vuelos", "#tabla_vuelos");
                mostratabla("vuelos_Aerolinea", "#tabla_vuelos_aerolinea");
                location.reload();
            }
        },

    });
 }

function config() {
    location.href = "./config.html";
}

function ocultarDislay() {
    $("#logout").css("display", "none");
    $("#usuario_logeado").css("display", "none");
    $("#formulario_comentario").css("display", "none");
    $("#admin_display").css("display", "none");
    $("#usuario_display").css("display", "none");
    $("#aerolinea_display").css("display", "none");
}