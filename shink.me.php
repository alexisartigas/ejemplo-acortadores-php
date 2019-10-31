<?php
/**
 *   Acortar con shink.me
 *   @author parzibyte.me/blog
*/
function shinkme($enlace)
{
    $clave = "TU_CLAVE_AQUÍ";
    $id = "TU_ID_AQUÍ";
    $raw = file_get_contents(
        "https://shon.xyz/api/0/id/"
        . urlencode($id)
        . "/auth_token/"
        . urlencode($clave)
        . "?s=" . urlencode($enlace));
    if (false === $raw) {
        throw new \Exception("Error obteniendo JSON de shink.me");
    }
    $respuesta = json_decode($raw);
    # Comprobar si no hay errores
    if (isset($respuesta->error) && $respuesta->error === 0) {
        # Ahora este es el acortado, pero vamos a ver si coincide con una expresión regular
        $acortado = "http://shink.me/" . $respuesta->hash;
        if (preg_match('/^http:\/\/shink\.me\/\w+$/', $acortado) !== 1)# En caso de que no coincida
            throw new Exception("Enlace inesperado al acortar con shink.me: " . $acortado);
        return $acortado; #En caso de que sí regresamos el acortado
    } else {
        throw new Exception("Error al acortar $enlace con shink.me. #Error: " . $respuesta->error);
    }
}

$enlaceAcortado = shinkme("https://parzibyte.me/blog");
echo $enlaceAcortado;
?>