# MarcoUpload

Libreria di upload di file compatibile con PHP 8.3.

```php
<?php
require 'vendor/autoload.php';
// Creazione di istanze e definizione della posizione della cartella di caricamento del file
$file = new MarcoUpload\MarcoUpload(__DIR__);

$percorso = $file->upload($_FILES['archivio'], [
    'move' => '/uploaded/', // Cartella di destinazione
    'size' => 2000000,      // Grandezza file esempio 2MB
    'type' => ['jpg', 'png'] // Estensioni accettate.
]);

// Controllo errori
if (!$file->getErrors()) {
    // Percorso del file nel server rinominato
    echo $percorso;
} else {
    var_export($file->getErrors());
}
```

