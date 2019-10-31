<?php
/**
 * Acortar un enlace utilizando la API de shorte.st
 * @author parzibyte
 * @see https://parzibyte.me/blog
 * @param string $enlace
 * @return string|null
 * @throws Exception
 */
function acortarConShorteSt($enlace)
{
    $claveApi = "tu_clave";
    $datos = [
        'urlToShorten' => $enlace,
    ];
    $opciones = array(
        'http' => array(
            'header' => [
                "Content-type: application/x-www-form-urlencoded",
                "public-api-token: " . $claveApi,
            ],
            'method' => 'PUT',
            'content' => http_build_query($datos),
        ),
    );
    $contexto = stream_context_create($opciones);
    $resultado = @file_get_contents(
        'https://api.shorte.st/v1/data/url',
        false,
        $contexto);
    if ($resultado === false) {
        throw new Exception("Error al realizar la conexión para acortar $enlace con shorte.st!");
    }

    $respuestaDecodificada = json_decode($resultado);
    if ($respuestaDecodificada->status === "ok") {
        $acortado = $respuestaDecodificada->shortenedUrl;
        if (preg_match('/^http:\/\/\w+\.com\/\w+$/', $acortado) !== 1) {
            throw new Exception("Enlace inesperado al acortar con shorte.st: " . $acortado);
        }

        return $acortado;
    }
    throw new Exception(
        "Error en la respuesta del servidor al acortar $enlace con shorte.st!"
    );
}

# Modo de uso
$enlace = "https://parzibyte.me/blog";
try {
    $acortado = acortarConShorteSt($enlace);
    echo "El enlace acortado es: $acortado";
} catch (Exception $e) {
    echo "Error acortando: " . $e->message;
}
