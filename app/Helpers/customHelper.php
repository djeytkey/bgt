<?php
function slugify($text) {
    // Replace non-alphanumeric characters with dashes
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);

    // Transliterate characters to ASCII
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // Remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // Trim dashes from the beginning and end of the slug
    $text = trim($text, '-');

    // Convert to lowercase
    $text = strtolower($text);

    // If the slug is empty, generate a random one
    // if (empty($text)) {
    //     $text = bin2hex(random_bytes(4));
    // }

    return $text;
}