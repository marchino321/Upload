<?php

declare(strict_types=1);

namespace MarcoUpload;

/**
 * Gestione dell'upload dei file.
 */
class MarcoUpload
{
    /**
     * Percorso di base in cui salvare i file caricati.
     */
    private string $path;

    /**
     * Elenco degli errori occorsi durante l'upload.
     *
     * @var array<string,string>
     */
    private array $errors = [];

    /**
     * Imposta la cartella di destinazione per i file caricati.
     */
    public function __construct(string $path)
    {
        $this->path = rtrim($path, '/');
    }

    /**
     * Esegue l'upload di un file.
     *
     * @param array $file    Informazioni sul file provenienti da $_FILES.
     * @param array $options Opzioni di configurazione: move, size, type.
     *
     * @return string|false Percorso del file caricato oppure FALSE in caso di errore.
     */
    public function upload(array $file, array $options): string|false
    {
        if (empty($file['name'])) {
            $this->errors['file'] = 'Il file è obbligatorio';
            return false;
        }

        $pathInfo = pathinfo($file['name']);

        if (!in_array(strtolower($pathInfo['extension']), $options['type'], true)) {
            $this->errors['type'] = 'Il formato non rispetta i parametri. (formati supportati: ' . implode(', ', $options['type']) . ')';
            return false;
        }

        if ($file['size'] > $options['size']) {
            $this->errors['size'] = 'Il file è più grande della grandezza settata. (size: ' . round($options['size'] / (1000 * 1000), 2) . 'MB)';
            return false;
        }

        $this->makeDirectory($options['move']);
        $fileRename = $this->fileRename($pathInfo['extension']);
        $pathFile = $this->path . $options['move'];

        return $this->moveFile($file, $pathFile . '/' . $fileRename);
    }

    /**
     * Sposta il file nella directory specificata.
     *
     * @param array  $file        Dati del file da spostare.
     * @param string $destination Percorso finale del file.
     */
    private function moveFile(array $file, string $destination): string|false
    {
        return move_uploaded_file($file['tmp_name'], $destination) ? $destination : false;
    }

    /**
     * Crea la directory di destinazione se non esiste.
     */
    private function makeDirectory(string $dir): void
    {
        $fullPath = $this->path . $dir;

        if (!file_exists($this->path)) {
            mkdir($this->path, 0777, true);
        }

        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0777, true);
        }
    }

    /**
     * Genera un nome casuale per il file mantenendo l'estensione.
     */
    private function fileRename(string $extension): string
    {
        return substr(md5((string) time()), 0, 12) . '@' . strtotime('now') . '.' . $extension;
    }

    /**
     * Restituisce l'elenco degli errori verificati.
     *
     * @return array<string,string>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}

