```php
<?php
require 'vendor/autoload.php';
// Creazione di istanze e definizione della posizione della cartella di caricamento del file
$file = new MarcoUpload\MarcoUpload(__DIR__ );

$file->upload($_FILES['archivio'], [
	'move' => '/uploaded/',                 // Cartella di sestinazione
	'size' => 2000000,                      // Grandezza file esempio 2MB
	'type' => ['jpg', 'png']                // Estensioni accettate.
]);

if(!$file->getErros()){
	echo 'Upload avvenuto con successo!';
} else {
	var_export($file->getErros());
}
```

