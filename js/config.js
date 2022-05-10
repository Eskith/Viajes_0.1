$(document).ready(function() {
    $("#logout").css("display", "none");
    $("#usuario_logeado").css("display", "none");

    mostrar_Datos();

    $("#form_Config").submit(function(e) {
        e.preventDefault();
        let form = $("#form_Config").serializeArray();
        form = form.concat({ name: "service", value: "Edit" });

        $.ajax({
            data: form,
            type: "POST",
            url: "../controller/configController.php",
            dataType: "JSON",

            success: function(response) {

                if (response.status == "Fail") {
                    $("#mensaje_Status_Fail").modal("show");
                    $("#statusFail").html(response.msg);
                } else {
                    $("#mensaje_Status_Success").modal("show");
                    $("#status").html(response.msg);
                }
                mostrar_Datos();
            },

        })

    });

    $("#logout").click(function(e) {
        e.preventDefault();
        url = "../controller/accessController.php";

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
                    location.href = "../index.html";
                }
            }
        });
    });
});

function mostrar_Datos() {
    // comprueba se esta conectado
    data = {
        "service": "mostrar_Datos"
    };

    let url = "../controller/accessController.php";
    $.ajax({
        type: "POST",
        url: url,
        data: data,
        dataType: "JSON",
        success: function(response) {
            if (response.status == "logeado") {
                $("#usuario_logeado").css("display", "");
                $("#usuario_logeado").html(response.usuario);
                $("#login").css("display", "none");
                $("#Sign").css("display", "none");
                $("#logout").css("display", "");
                $("#dni_Usuario").val(response.dni);
                $("#nombre_Usuario").val(response.nombre);
                $("#apellidos_Usuario").val(response.apellidos);
                $("#pais_Usuario").val(response.pais);
                $("#telefono_Usuario").val(response.telefono);
                $("#email_Usuario").val(response.email);
                $("#usuario_Usuario").val(response.usuario);
                $("#password_Usuario").val(response.password);


            } else {
                location.href = "../index.html";
            }
        }
    });
}