<?php
require_once "connection.php";
require_once "../lib/PHPMailer/src/PHPMailer.php";
require_once "../lib/PHPMailer/src/SMTP.php";

session_start();


/**
 * @author SalvadorAsquit
 */

class Access
{
    public $email;
    public $pass;



    /**
     * constructor
     * @param ip $String es la ip de la base de datos a la que nos conectaremos
     * @param usu $String es el usuario de la base de datos
     * @param pass $String es la contraseña para acceder a la base de datos
     * @param bd $String la base de datos para conectar
     */
    public function __construct($ip, $usu, $pass, $bd)
    {


        //coneccion para la base de datos
        $coneccion = new Connection($ip, $usu, $pass, $bd);
        $this->mysqli = $coneccion->coneccion_Mysqli();
    }

    //-------------------------------------------------------------------------------------Terminar 
    function Login($email, $pass)
    {
        $datos = self::filtraUsuario($email);
        $response = array(
            "usuario" => " ",
            "status" => "Fail",
            "msg" => "Email o Pass incorrecta"
        );

        if (is_string($datos)) {
            return  $response;
        } else {
            if (strtolower($datos[0]["email"]) == strtolower($email) && $datos[0]["password"] == $pass) {
                switch ($datos[0]["administrador"]) {
                    case '0':
                        $_SESSION["usuario"] = $datos[0];
                        $response = array(
                            "usuario" => $datos[0]["usuario"],
                            "status" => "Login",
                            "login" => "usuario"
                        );
                        return $response;
                        break;

                    case '1':
                        return "Login admin";
                        break;

                    case '2':
                        return "Login Aerolinea";
                        break;

                    case '3':
                        return "Login Hotelera";
                        break;

                    default:
                        $response = array(
                            "usuario" => " ",
                            "status" => "Fail",
                            "msg" => "Fallo en la base de datos pongase en contacto con un administrador"
                        );
                        return $response;
                        break;
                }
            } else {
                return  $response;
            }
        }
    }

    function signUp($email, $usuario, $pass, $dni, $nombre, $apellido, $pais, $phone, $tipo)
    {
        $datos = self::buscarConcidencias($email, $dni, $usuario);

        if ($datos["status"] == "Fail") {
            return $datos;
        }else{
            $sql = "INSERT INTO `usuario` 
            VALUES ('{$dni}', '{$nombre}', '{$apellido}', '{$pais}', {$phone}, '{$email}', '{$usuario}', '{$pass}', 0, 0);";

        $result = $this->mysqli->query($sql);

        if ($result) {
            $response = array(
                "status" => "success",
                "msg" => "Registrado con exito"
            );
            return $response;
        } else {
            $response = array(
                "status" => "Fail",
                "msg" => "Fallo en la base de datos"
            );
            return $response;
        }
        }
    }


    function recovery($email)
    {
        $datos = self::filtraUsuario($email);

        if ($datos == "usuario no en contrado") {
            return "Email o pass incorrecta";
        } else {
            $_SESSION["code"] = random_int(100, 999);
            $_SESSION["email_Recovery"] = $email;
            self::mail($email);
            return "";
        }
    }

    function changePass($pass)
    {
        $email = $_SESSION["email_Recovery"];

        $sql = "UPDATE usuario 
        SET `password` = '{$pass}' 
        WHERE
            email = '{$email}'";

        $result = $this->mysqli->query($sql);

        if ($result) {
            return "success";
        } else {
            return $result;
        }
    }

    function mail($email)
    {
        $mail = new PHPMailer();
        $mail->CharSet = 'UTF-8';

        $body = $_SESSION["code"];

        $mail->IsSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;
        $mail->SMTPDebug  = 1;
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ViajesSoter@gmail.com';
        $mail->Password   = 'ViajesSoter1234';
        $mail->SetFrom('ViajesSoter@gmail.com', "ViajesSoter");
        $mail->AddReplyTo('no-reply@mycomp.com', 'no-reply');
        $mail->Subject    = 'Codigo de recuperacion';
        $mail->MsgHTML($body);

        $mail->AddAddress($email, 'Gianni');
        $mail->send();
    }


    function buscarConcidencias($email, $dni, $usu)
    {
        $sql = "SELECT
        * 
        FROM
            usuario 
        WHERE
            email = '{$email}'";

        $result = $this->mysqli->query($sql);

        while ($row = $result->fetch_assoc()) {
            $datos[] = $row;
        }

        if (!isset($datos)) {
            $sql = "SELECT
            * 
            FROM
                usuario 
                WHERE
            dni = '{$dni}'";

            $result = $this->mysqli->query($sql);

            while ($row = $result->fetch_assoc()) {
                $datos[] = $row;
            }
            if (!isset($datos)) {
                $sql = "SELECT
                * 
                FROM
                    usuario 
                    WHERE
                usuario = '{$usu}'";

                $result = $this->mysqli->query($sql);

                while ($row = $result->fetch_assoc()) {
                    $datos[] = $row;
                }
                if (!isset($datos)) {
                    $response = array(
                        "status" => "success",
                        "msg" => "No Existe el Usuario"
                    );
                    return $response;
                } else {
                    $response = array(
                        "status" => "Fail",
                        "msg" => "Fallo ese usuario ya existe"
                    );
                    return $response;
                }
            } else {
                $response = array(
                    "status" => "Fail",
                    "msg" => "Fallo ese dni ya existe"
                );
                return $response;
            }
        } else {
            $response = array(
                "status" => "Fail",
                "msg" => "Fallo ese Email ya existe"
            );
            return $response;
        }
    }


    function filtraUsuario($email)
    {
        $sql = "SELECT
        * 
    FROM
        usuario 
    WHERE
        email = '{$email}'";

        $result = $this->mysqli->query($sql);

        while ($row = $result->fetch_assoc()) {
            $datos[] = $row;
        }

        if (!isset($datos)) {
            return "usuario no en contrado";
        } else {
            return $datos;
        }
    }











    //-------------------------------------------------------------------------------------










    /**
     * Funcion de control de Csv si verificar datos nos devuelve en la array datos, el indice fail significa que hay fallos y no continuaremos, 
     * sino insertaremos los datos y veremos unos detalles
     */
    function Csv()
    {
        $datos = self::procesarCsv();
        if (isset($datos["fail"])) {
            return $datos;
        } else {
            self::insertar($datos, $this->headersbd);
            $resultado = self::ObtenerDetalle();
            return $resultado;
        }
    }

    /**
     * Funcion de control de Txt si verificar datos nos devuelve en la array datos, el indice fail significa que hay fallos y no continuaremos, 
     * sino insertaremos los datos y veremos unos detalles
     */
    function Txt()
    {
        $datos = self::procesarTxt();
        if (isset($datos["fail"])) {
            return $datos;
        } else {
            self::insertar($datos, $this->headersbd);
            $resultado = self::ObtenerDetalle();
            return $resultado;
        }
    }

    /**
     * Procesamos los datos : haciendo una copia de seguridad, recorriendo el archivo, combinando los datos y las cabeceras, contando las filas y comprobando si las cabeceras coinciden
     */
    function procesarTxt()
    {
        // hacemos la copia de seguridad
        $file = self::copiarArchivoSeg();

        // lee el archivo
        $txt_file = fopen($file, 'r');

        // sacamos los datos
        while (($datos = fgetcsv($txt_file, 0, "{$this->delimitador}")) !== FALSE) {
            $array[] = $datos;
        }
        fclose($txt_file);

        // sacamos las cabeceras
        $cabeceras = array_shift($array);

        // combinamos los datos y las cabeceras
        foreach ($array as $fila => $campos) {
            $data[$fila] = array_combine($cabeceras, $campos);
        }

        // contamos las filas
        $this->data = $data;
        $this->totaldefilas = count($data);

        // comparamos las cabeceras
        $resultado = self::compararCabeceras($cabeceras);

        if ($resultado == "ok") {
            return $data;
        } else {
            return $resultado;
        }
    }

    /**
     * Procesamos los datos : haciendo una copia de seguridad, recorriendo el archivo, combinando los datos y las cabeceras, contando las filas y comprobando si las cabeceras coinciden
     */
    function procesarCsv()
    {
        // hacemos la copia de seguridad
        $file = self::copiarArchivoSeg();

        // lee el archivo
        $csv_file = fopen($file, 'r');

        // sacamos los datos 
        while (($datos = fgetcsv($csv_file, 0, "{$this->delimitador}")) !== FALSE) {
            $array[] = $datos;
        }
        fclose($csv_file);

        // sacamos las cabeceras
        $cabeceras = array_shift($array);

        // combinamos las cabeceras y los datos
        foreach ($array as $fila => $campos) {
            $data[$fila] = array_combine($cabeceras, $campos);
        }

        // contamos las filas
        $this->data = $data;
        $this->totaldefilas = count($data);

        // comprobamos las cabeceras
        $resultado = self::compararCabeceras($cabeceras);

        if ($resultado == "ok") {
            return $data;
        } else {
            return $resultado;
        }
    }

    /**
     * Procesamos los datos : haciendo una copia de seguridad, recorriendo el archivo, combinando los datos y las cabeceras, contando las filas y comprobando si las cabeceras coinciden
     */
    function procesarExcel()
    {
        // hacemos la copia de seguridad
        $file = self::copiarArchivoSeg();

        $reader = new PHPExcel_Reader_Excel2007();
        $reader->setReadDataOnly(true);
        $excel = $reader->load($file);

        // sacamos los datos y las cabeceras
        $worksheet = $excel->getActiveSheet()->toArray(null, false, false, false);
        $headers = array_shift($worksheet);

        $data = array();

        // Cruce de cabeceras y valores
        foreach ($worksheet as $fila => $campos) {
            $data[$fila] = array_combine($headers, $campos);
        }

        // contamos las filas
        $this->data = $data;
        $this->totaldefilas = count($data);

        // comparamos las cabeceras
        $resultado = self::compararCabeceras($headers);

        if ($resultado == "ok") {
            return $data;
        } else {
            return $resultado;
        }
    }

    /**
     * Creamos una copia de seguridad del archivo y es la que manipulamos con el resto de codigo
     */
    function copiarArchivoSeg()
    {
        $path = str_replace("model", "files", __DIR__);
        $archivoName = $_FILES["fichero"]["name"];
        $archivotemp = $_FILES["fichero"]["tmp_name"];
        $date = date("ymdHi");
        $nombre = "upload_{$archivoName}_{$date}.xlsx";

        copy($archivotemp, "$path/$nombre");
        $file = "$path/$nombre";

        return $file;
    }

    /** 
     * filtros generales trim , // y caracteres raros
     */
    function limpiaDatos($dato)
    {
        $dato = addslashes($dato);
        $dato = str_replace(",", ".", $dato);
        $dato = str_replace("&", "", $dato);
        $dato = str_replace("^", "", $dato);
        $dato = str_replace("Ç", "", $dato);
        $dato = str_replace(";", "", $dato);
        $dato = trim($dato);

        return $dato;
    }

    /**
     * Comprovamos la extension del archivo
     */
    public function comprobarExtension()
    {
        $csv = "text/csv";
        $txt = "text/plain";
        $xlsx = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
        $formatos_Validos = [$txt, $xlsx, $csv];
        $respuesta = "";

        if ((in_array($this->extension, $formatos_Validos))) {
            switch ($this->extension) {
                case $txt:
                    $respuesta = "txt";
                    break;

                case $xlsx:
                    $respuesta = "excel";
                    break;

                case $csv:
                    $respuesta = "csv";
                    break;
            }
        } else {
            $respuesta = "fail";
        }

        return $respuesta;
    }

    /**
     * comparamos que las cabeceras sean iguales sino es el caso no continuaremos y devolveremos una array con las cabeceras faltantes
     */
    function compararCabeceras($cabecerasArchivo)
    {

        $sql = "SHOW COLUMNS FROM {$this->tablaDeLaBaseDeDatos}";

        $result = $this->mysqli->query($sql);

        while ($row = $result->fetch_assoc()) {
            $cabecerasbd[] = $row["Field"];
        }
        $this->headersbd = $cabecerasbd;

        // comparacion de las cabeceras
        $fail = "";
        $error = "";

        if (count($cabecerasbd) > count($cabecerasArchivo)) {
            $fail .=  "<ul class='list-group list-group-flush'>";
            $fail .= "<li class='list-group-item d-flex justify-content-between align-items-center'><span class='badge bg-danger rounded-pill'>La Base de Datos tiene  mas Columnas que el Archivo</span></li></ul>";
            $error = "<ul class='list-group list-group-flush'>";
            $error .= "<li class='list-group-item d-flex justify-content-between align-items-center'>Fallo en las columnas<span class='badge bg-danger rounded-pill'> X </span></li></ul>";
            $fallos = array("resultado" => $error, "fail" => $fail);
        } else {
            if (count($cabecerasbd) < count($cabecerasArchivo)) {
                $fail .=  "<ul class='list-group list-group-flush'>";
                $fail .= "<li class='list-group-item d-flex justify-content-between align-items-center'><span class='badge bg-danger rounded-pill'>La Base de Datos tiene  menos Columnas que el Archivo</span></li></ul>";
                $error = "<ul class='list-group list-group-flush'>";
                $error .= "<li class='list-group-item d-flex justify-content-between align-items-center'>Fallo en las columnas<span class='badge bg-danger rounded-pill'> X </span></li></ul>";
                $fallos = array("resultado" => $error, "fail" => $fail);
            }
        }


        if (isset($fallos)) {
            return $fallos;
        }


        foreach ($cabecerasbd as $key => $value) {
            $esta = "no esta";

            foreach ($cabecerasArchivo as $key2 => $contenido) {

                if (strtolower($contenido) == strtolower($value)) {
                    $esta = "esta";
                }
            }

            if ($esta == "no esta") {

                $fail .=  "<ul class='list-group list-group-flush'>";
                $fail .= "<li class='list-group-item d-flex justify-content-between align-items-center'><span class='badge bg-danger rounded-pill'>Fail : la cabecera {$value} no esta en las cabeceras de la base de datos</span></li></ul>";
                $error = "<ul class='list-group list-group-flush'>";
                $error .= "<li class='list-group-item d-flex justify-content-between align-items-center'><span class='badge bg-danger rounded-pill'>Cabeceras no coinciden<span><span class='badge bg-danger rounded-pill'> X </span></li></ul>";
            }
        }
        $fallos = array("resultado" => $error, "fail" => $fail);

        // hay un error y salimos
        if (!isset($fallos)) {
            return $fallos;
        } else {
            $this->fail = 0;
            return "ok";
        }
    }

    /**
     * Insert en la base de datos y los filtramos
     */
    function insertar($data, $cabecerasbd)
    {
        $stringColumn = $this->mysqli->real_escape_string(implode(",", $cabecerasbd));

        $total = 0;
        $fila = 0;
        foreach ($data as  $arrayData) {

            foreach ($arrayData as $key => $value) {
                $aux[$key] = $this->mysqli->real_escape_string($value);
                $aux[$key] = trim($aux[$key]);
            }

            $stringData = "'" . implode("','", $aux) . "'";

            $sql =  "REPLACE INTO {$this->baseDeDatos}.{$this->tablaDeLaBaseDeDatos} ({$stringColumn}) VALUES ({$stringData});";
            $this->mysqli->query($sql);


            if ($this->mysqli->error) {

                $this->fallosInsert[] = "<strong>FILA {$fila} : </strong> " . strtoupper($this->mysqli->error);
            } else {
                $total = $total + 1;
            }
        }

        $this->insertados = $total;
    }

    /**
     * mostramos unos detalles generales de lo sucedido
     */
    public function ObtenerDetalle()
    {
        $erroneos = (isset($this->fallosInsert)) ? count($this->fallosInsert) : 0;
        $insertados = $this->insertados;

        $listResult = "<ul class='list-group list-group-flush'>";
        $listResult .= "<li class='list-group-item d-flex justify-content-between align-items-center'>Número de filas del fichero<span class='badge bg-primary rounded-pill'>$this->totaldefilas</span></li>";
        $listResult .= "<li class='list-group-item d-flex justify-content-between align-items-center'>Número de filas insertadas correctamente<span class='badge bg-success rounded-pill'>$insertados</span></li>";
        $listResult .= "<li class='list-group-item d-flex justify-content-between align-items-center'>Número de de filas con errores (no insertadas)<span class='badge bg-danger rounded-pill'>$erroneos</span></li></ul>";

        $listError = "<ul class='list-group list-group-flush text-start'>";
        if (isset($this->incoherenceData)) {

            foreach ($this->fallosInsert as $key => $value) {
                foreach ($value as $key => $value) {
                    $listError .= "<li class='list-group-item'>{$value}</li>";
                }
            }
        }
        $listError .= "</ul>";

        $detalle = array(
            "resultado" => $listResult,
            "fail" => $listError
        );

        return $detalle;
    }
}
