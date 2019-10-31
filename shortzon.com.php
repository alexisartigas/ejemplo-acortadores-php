<?php
/**
 * Acortar un enlace usando la API de shortzon.com
 * @author parzibyte
 * @see https://parzibyte.me/blog
 * @param string $enlace
 * @return string
 * @throws Exception
 */
function acortarConShortzon($enlace)
{
    #Coloca aquí tu token, si no tienes uno, consíguelo en https://shortzon.com/ref/parzibyte
    $apiToken = "tu_clave"; # Tu api Token
    $url = sprintf("https://shortzon.com/api?api=%s&url=%s", $apiToken, urlencode($enlace));
    $respuesta = @file_get_contents($url);
    if (!$respuesta) {
        throw new Exception("Error acortando $enlace al comunicar con la API");
    }
    $datos = json_decode($respuesta);
    if (!property_exists($datos, "shortenedUrl") || empty($datos->shortenedUrl)) {
        throw new Exception("No se devolvió un enlace válido: $respuesta");
    }
    $acortado = $datos->shortenedUrl;
    if (preg_match('/^http[s]:\/\/shrtz\.me\/\w+$/', $acortado) !== 1) {
        throw new Exception("Enlace inesperado al acortar con shortzon.com el enlace $enlace: " . $acortado);
    }
    return $acortado;
}

# Modo de uso
try {
    $enlace = "https://parzibyte.me/blog";
    $acortado = acortarConShortzon($enlace);
    echo "El enlace acortado con shortzon.com es: $acortado";
} catch (Exception $e) {
    echo "Error acortando: " . $e->getMessage();
}
