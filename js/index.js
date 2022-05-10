$(document).ready(function() {
    let minFinal;
    let minInicio;
    let url = "./controller/accessController.php";

    $("#modal-login").css("display", "none");
    $("#modal-Sign").css("display", "none");
    $("#logout").css("display", "none");
    $("#usuario_logeado").css("display", "none");
    $("#modal-recovery-change").css("display", "none");
    $("#modal-recovery").css("display", "none");

    // comprueba si esta logeado
    data = {
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
                    $("#mensaje_Status_Success").modal("show");
                    $("#status").html(response.msg);
                    $("#login").css("display", "");
                    $("#Sign").css("display", "");
                    $("#logout").css("display", "none");
                    $("#usuario_logeado").css("display", "none");
                    $("#usuario_logeado").html("");
                }
            }
        });
    });
});