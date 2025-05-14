<?php
/**
 * Escapa una cadena para prevenir inyección de HTML o inyección de scripts.
 *
 * @param string $string La cadena a escapar.
 * @return string La cadena escapada.
 */
function escape($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Redirige a la URL especificada y termina la ejecución del script.
 *
 * @param string $location La URL a la que redirigir.
 */
function redirectTo($location) {
    header('Location: ' . $location);
    exit;
}

/**
 * Genera un "slug" amigable para URLs a partir de una cadena.
 *
 * Por ejemplo, "Hola Mundo en PHP" se convertirá en "hola-mundo-en-php".
 *
 * @param string $text La cadena original.
 * @return string El slug generado.
 */
function slugify($text) {
    // Reemplaza caracteres no alfanuméricos por guiones.
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);

    // Translitera a caracteres ASCII.
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // Elimina cualquier carácter que no sea un guion o alfanumérico.
    $text = preg_replace('~[^-\w]+~', '', $text);

    // Elimina guiones al principio y al final.
    $text = trim($text, '-');

    // Reemplaza múltiples guiones consecutivos por uno solo.
    $text = preg_replace('~-+~', '-', $text);

    // Convierte a minúsculas.
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}
?>