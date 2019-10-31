<?php
# Ayudante para saber si cadena comienza con otra cadena
function comienzaCon($pajar, $aguja)
{
    return $aguja === ''
    || strrpos($pajar, $aguja, -strlen($pajar)) !== false;
}

/**
 * Acortar enlaces usando API de adf.ly
 * @author parzibyte
 * @see https://parzibyte.me/blog
 * @param string $enlace
 * @return bool|string
 * @throws Exception
 */
function acortarAdfLy($enlace)
{
    $key = "tu_clave"; # Llave pública de API
    $uid = "tu_id"; # Id de usuario
    $datos = [
        'key' => $key,
        "uid" => $uid,
        'advert_type' => 'interstitial',
        'domain' => 'q.gs',
        "url" => $enlace,
    ];
    $resultado = @file_get_contents('http://api.adf.ly/api.php?' . http_build_query($datos));
    if ($resultado === false) {
        throw new Exception(
            "Error al realizar la conexión para acortar $enlace con adf.ly!"
        );
    }

    /*
     * Si no respondieron con un JSON entonces
     * respondieron con la URL, y todas las cosas
     * están bien
     * */
    $respuestaDecodificada = json_decode($resultado);
    if ($respuestaDecodificada === null) {
        if (comienzaCon($resultado, "ERROR:")) {
            throw new Exception("El enlace $enlace no es válido: $resultado");
        }
        $acortado = $resultado;
        if (preg_match('/^http:\/\/q\.gs\/\w+$/', $acortado) !== 1) {
            throw new Exception("Enlace inesperado al acortar con adf.ly: " . $acortado);
        }

        return $acortado;
    } else {
        throw new Exception(
            "Error en la respuesta del servidor al acortar con adf.ly"
            . json_encode($respuestaDecodificada->errors, true)
            . "!"
        );
    }

}
# Modo de uso
$enlace = "https://parzibyte.me/blog";
try {
    $acortado = acortarAdfLy($enlace);
    if ($acortado != false) {
        echo "El enlace acortado es $acortado";
    }
} catch (Exception $e) {
    echo "Error acortando: " . $e->getMessage();
}
