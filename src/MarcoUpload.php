<?php

namespace MarcoUpload;


class MarcoUpload {


	/**
	 * @var type | $path
	 */


	private $path  = NULL;


	/**
	 * @var type | erros
	 */


	private $erros = FALSE;


	/**
	 * -------------------------------------------------------------------------
	 *  Cartella dove archiviare i file
	 * -------------------------------------------------------------------------
	 *
	 * @param type $path
	 */


	public function __construct($path){
		$this->path = $path;
	}


	/**
	 * -------------------------------------------------------------------------
	 *  Upload file singoli e multipli
	 * -------------------------------------------------------------------------
	 *
	 * @param type $file
	 * @param array $options
	 */


	public function Upload($file, array $options){

		if(!empty($file['name'])) {
			$pathInfo  = pathinfo($file['name']);

			if(in_array(strtolower($pathInfo['extension']), $options['type'])){

				if($file['size'] <= $options['size']){
					$this->mkDir($options['move']); // Creo la cartella di destinazione

					$fileRename = $this->fileRename($pathInfo['extension']); // Genero un nome random per il file per non sovrascrivere
					$pathFile   = $this->path . $options['move']; // Definisco il percorso di destinazione

					return $this->moveFile($file,  $pathFile . '/' . $fileRename); // Copio il file da tmp alla sua cartella
				} else {
					$this->erros['size'] = "Il file e piu grande della grandezza settata. (size: " . round($options['size'] / (1000 * 1000), 2) . "MB)";
				}

			} else {
				$this->erros['type'] = "Il formato non rispetta i parametri. (formati supportati: " . implode(', ', $options['type']) . ")";
			}

		} else {
			$this->erros['file'] = "Il file è obbligatorio ";
		}
	}


	/**
	 * -------------------------------------------------------------------------
	 * Spostamento del file nella directory creata nell'applicazione
	 * -------------------------------------------------------------------------
	 *
	 * @param type $file
	 * @param type $destination
	 * @return boolean
	 */


	private function moveFile($file, $destination){
		if(move_uploaded_file($file['tmp_name'], $destination)){
			return $destination;
		} else {
			return FALSE;
		}
	}


	/**
	 * -------------------------------------------------------------------------
	 * Crea una directory all'interno della cartella definita in __contruct
	 * -------------------------------------------------------------------------
	 *
	 * @param type $dir
	 */


	private function mkDir($dir) {
		if(!file_exists($this->path . $dir)){
			mkdir($this->path . $dir, 0777);
		}

		if(!file_exists($this->path )){
			mkdir($this->path . $dir, 0777);
		}
	}


	/**
	 * -------------------------------------------------------------------------
	 * Rinomina il nome del file da caricare con la crittografia.
	 * -------------------------------------------------------------------------
	 *
	 * @param type $extension
	 * @return type
	 */


	private function fileRename($extension) {
		return substr(md5(time()), 0, 12) . '@' . strtotime('now') . '.' . $extension;
	}


	/**
	 * -------------------------------------------------------------------------
	 * Restituisce tutti i possibili errori.
	 * -------------------------------------------------------------------------
	 *
	 * @return type
	 */


	public function getErros() {
		return $this->erros;
	}

}
