$(document).ready(function() {
    let minFinal;
    let minInicio;
    let url = "./controller/accessController.php";

    $("#modal-login").css("display", "none");
    $("#modal-Sign").css("display", "none");
    $("#logout").css("display", "none");
    $("#usuario-logeado").css("display", "none");
    $("#modal-recovery-change").css("display", "none");
    $("#modal-recovery").css("display", "none");


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

                if (response.status == "Fail") {
                    alert(response.msg)
                }else{
                    alert(response.status + " success")
                    $("#modal-login").css("display", "none");   
                    $("#usuario-logeado").css("display", "");
                    $("#usuario-logeado").html(response.usuario);
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

        form = form.concat({ name: "service", value: "register" }
            // { name: "login", value: "palabra" }
        );

        $.ajax({
            data: form,
            type: "POST",
            url: url,
            dataType: "JSON",

            success: function(response) {

                if (response.status == "Fail") {
                    alert(response.msg)
                }else{
                    alert(response.msg)
                    $("#modal-Sign").css("display", "none");
                    $("#modal-login").css("display", "block");

                }
            },

        });
    });

//---------------------------MODAL RECOVERY-----------------------------------------------------

    $("#recovery-form").submit(function(e) {
        e.preventDefault();
        let responseTime = new Date;
        minInicio = responseTime.getMinutes();
        minFinal = responseTime.getMinutes()+2;

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
                    $("#modal-recovery").css("display", "block");
                    $("#modal-recovery-change").css("display", "none");
                    alert("correo incorrecto");
                }
            },
        });
        $("#modal-recovery").css("display", "none");
        $("#modal-recovery-change").css("display", "block");
    });

//---------------------------MODAL RECOVERY CHANGE-----------------------------------------------------
    $("#recovery-change-form").submit(function(e) {
        e.preventDefault();
        console.log("hora inicio: " + minInicio);
        console.log("hora final: " + minFinal);


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
                if (response == "success") {
                    alert(response);
                    $("#modal-recovery-change").css("display", "none");
                }else{
                    alert(response);
                }
            }
        });

        } else {
            alert("Se ha Caducado el tiempo");
            $("#modal-recovery").css("display", "block");
            $("#modal-recovery-change").css("display", "none");

        }

    });
//---------------BOTONERA-----------------------------------------------------

    $("#recovery").click(function(e) {
        e.preventDefault();
        $("#modal-login").css("display", "none");
        $("#modal-recovery").css("display", "block");
    });
    $("#logout").click(function(e) {
        e.preventDefault();
        $("#login").css("display", "");
        $("#Sign").css("display", "");
        $("#logout").css("display", "none");
        $("#usuario-logeado").css("display", "none");
        $("#usuario-logeado").html("");
    });
    $("#Sign").click(function(e) {
        e.preventDefault();
        $("#modal-login").css("display", "none");
        $("#modal-Sign").css("display", "block");
    });
    $("#login").click(function(e) {
        e.preventDefault();
        $("#modal-Sign").css("display", "none");
        $("#modal-login").css("display", "block");
        $("#modal-recovery").css("display", "none");
        $("#modal-recovery-change").css("display", "none");
    });
    $(".modal-close").click(function(e) {
        e.preventDefault();
        $("#modal-login").css("display", "none");
        $("#modal-Sign").css("display", "none");
        $("#modal-recovery").css("display", "none");
        $("#modal-recovery-change").css("display", "none");
    });
});