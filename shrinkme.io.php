<?php
/**
 * Acortar un enlace usando la API de shrinkme.io
 * @author parzibyte
 * @see https://parzibyte.me/blog
 * @param string $enlace
 * @return string
 * @throws Exception
 */
function acortarConShrinkMeIo($enlace)
{
    $apiToken = "tu_clave"; # Tu api Token
    $url = sprintf("https://shrinkme.io/api?api=%s&url=%s", $apiToken, urlencode($enlace));
    $respuesta = @file_get_contents($url);
    if (!$respuesta) {
        throw new Exception("Error acortando $enlace al comunicar con la API");
    }
    $datos = json_decode($respuesta);
    if (!property_exists($datos, "shortenedUrl") || empty($datos->shortenedUrl)) {
        throw new Exception("No se devolvió un enlace válido: $respuesta");
    }
    $acortado = $datos->shortenedUrl;
    if (preg_match('/^http[s]:\/\/shrinkme\.io\/\w+$/', $acortado) !== 1) {
        throw new Exception("Enlace inesperado al acortar con shrinkme.io el enlace $enlace: " . $acortado);
    }
    return $acortado;
}

# Modo de uso
try {
    $enlace = "https://parzibyte.me/blog";
    $acortado = acortarConShrinkMeIo($enlace);
    echo "El enlace acortado con shrinkme.io es: $acortado";
} catch (Exception $e) {
    echo "Error acortando: " . $e->getMessage();
}
