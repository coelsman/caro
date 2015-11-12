<?php
class Json {
	public $file;

	public function __construct ($file = '') {
		if ($file != '') {
			$this->file = $file;
		}
	}

	public function getFileContent () {
		return json_decode(file_get_contents($this->file));
	}

	public function setFileContent ($content) {
		file_put_contents($this->file, json_encode($content));
	}
}