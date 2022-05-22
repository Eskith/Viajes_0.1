<?php
require_once "connection.php";

function convert_utf8($datos)
{
   if (is_string($datos)) {
      return utf8_encode($datos);
   } elseif (is_array($datos)) {
      $ret = [];
      foreach ($datos as $i => $d) $ret[$i] = convert_utf8($d);

      return $ret;
   } elseif (is_object($datos)) {
      foreach ($datos as $i => $d) $datos->$i = convert_utf8($d);

      return $datos;
   } else {
      return $datos;
   }
}

function procesar_Datatable($datos)
{
   $columns = array_keys($datos[0]);

   $i = 0;

   foreach ($columns as $key => $value) {

      $columns[$key] = array('data' => $value);
      $columnsDefs[] = array('title' => $value, 'targets' => $i, 'visible' => true, 'searchable' => true);
      $i++;
   }

   //$datos = convert_utf8($datos); solo en caso de que la bbdd falle el utf
   $datos = array(
      'data' => $datos,
      'columns' => $columns,
      'columnsDefs' => $columnsDefs,
   );

   return $datos;
}

function verificacion($usu, $email)
{
   $datos = verificaEmail($email, "usuario");
   if ($datos["status"] == "404" ) {
      $datos = verificaEmail($email, "hotelera");
         if ($datos["status"] == "404" ) {
            $datos = verificaEmail($email, "aerolinea");
            if ($datos["status"] == "404" ){
               $datos = "OK";//no existe ese email
            }else {
               return $datos;//fallo email esta en aerolinia
            }
         }else {
            return $datos;//fallo email esta hostelera
         }
   }else {
      return $datos;//fallo email esta usuario
   }

   $datos = verificaUsuario($usu, "usuario");
   if ($datos["status"] == "404" ) {
      $datos = verificaUsuario($usu, "hotelera");
         if ($datos["status"] == "404" ) {
            $datos = verificaUsuario($usu, "aerolinea");
            if ($datos["status"] == "404" ){
               $datos = "OK";//no existe ese email
            }else {
               return $datos;//fallo email esta en aerolinia
            }
         }else {
            return $datos;//fallo email esta hostelera
         }
   }else {
      return $datos;//fallo email esta usuario
   }

   return $datos;

}

function verificaEmail($email, $tabla)
{
   $coneccion = new Connection();
   $mysqli = $coneccion->coneccion_Mysqli();


   $sql = "SELECT
        * 
        FROM
        $tabla 
        WHERE
        email = '{$email}'";

        $result = $mysqli->query($sql);

        while ($row = $result->fetch_assoc()) {
            $datos[] = $row;
        }

        if (!isset($datos)) {
         $response = array(
            "status" => "404",
            "msg" => "Email no encontrado en " . $tabla
        );
        } else {
         $response = array(
            "status" => "success",
            "msg" => "Email encontrado en " . $tabla,
            "datos" => $datos
        );
        }

        return $response;
}

function verificaUsuario($usuario, $tabla)
{
   $coneccion = new Connection();
   $mysqli = $coneccion->coneccion_Mysqli();


   $sql = "SELECT
        * 
        FROM
        $tabla 
        WHERE
        usuario = '{$usuario}'";

        $result = $mysqli->query($sql);

        while ($row = $result->fetch_assoc()) {
            $datos[] = $row;
        }

        if (!isset($datos)) {
         $response = array(
            "status" => "404",
            "msg" => "Usuario no encontrado en " . $tabla
        );
        } else {
         $response = array(
            "status" => "success",
            "msg" => "Usuario encontrado en " . $tabla,
            "datos" => $datos
        );
        }

        return $response;
}

function verificaNombre($usuario, $tabla)
{
   $coneccion = new Connection();
   $mysqli = $coneccion->coneccion_Mysqli();


   $sql = "SELECT
        * 
        FROM
        $tabla 
        WHERE
        nombre = '{$usuario}'";

        $result = $mysqli->query($sql);

        while ($row = $result->fetch_assoc()) {
            $datos[] = $row;
        }

        if (!isset($datos)) {
         $response = array(
            "status" => "404",
            "msg" => "Nombre no encontrado en " . $tabla
        );
        } else {
         $response = array(
            "status" => "success",
            "msg" => "Nombre encontrado en " . $tabla,
            "datos" => $datos
        );
        }

        return $response;
}

