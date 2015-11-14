<?php
require_once('../Json.php');

$js = new Json(dirname(__FILE__).'/../jsons/table.json');
$data = $js->getFileContent();
if (isset($_GET['table'])) {
	echo json_encode($data->$_GET['table']);
} else {
	echo json_encode($data);
}