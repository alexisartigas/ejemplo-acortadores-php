<?php
function ouo_io($claveApi, $enlace){
	//Petición GET
	$acortado = @file_get_contents(
	            "http://ouo.io/api/"
	            . urlencode($claveApi)
	            . "?s="
	            . urlencode($enlace));
	// Comprobar si lo que obtuvimo
	// es un enlace válido utilizando una
	// expresión regular
    if (preg_match('/^(http|https):\/\/ouo\.io\/\w+$/', $acortado) !== 1)
        throw new Exception("Enlace inesperado al acortar con ouo.io: " . $acortado);
    return $acortado;
}
// Ejemplo de uso
$claveApi = "TU_CLAVE_API_AQUÍ";
$enlace = "https://parzibyte.me/blog";
echo ouo_io($claveApi, $enlace); //Salida: algo como http://ouo.io/D5UZXp
?>
