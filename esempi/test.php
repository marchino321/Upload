<?php
require 'vendor/autoload.php';

// Creazione dell'istanza e definizione della posizione della cartella di caricamento del file
$file = new MarcoUpload\MarcoUpload(__DIR__ . '/public_html');

$file->upload($_FILES['archivio'], [
    'move' => '/uploaded/', // Cartella di destinazione
    'size' => 2000000,      // Grandezza file esempio 2MB
    'type' => ['jpg', 'png'] // Estensioni accettate.
]);

if (!$file->getErrors()) {
    echo 'Upload avvenuto con successo!';
} else {
    var_export($file->getErrors());
}
