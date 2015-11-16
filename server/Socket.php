<?php
date_default_timezone_set('Asia/Saigon');
require_once('PHPWebSocket.php');
require_once('Json.php');
set_time_limit(0);

// when a client sends data to the server
function wsOnMessage($clientID, $message, $messageLength, $binary) {
	global $Server;
	// $message = json_decode($message);
	$ip = long2ip( $Server->wsClients[$clientID][6]);

	$message = json_decode($message);

	if ($message->type == 'join') {
		actionJoinGame($clientID, $message);
	} else if ($message->type == 'ready') {
		actionReadyGame($clientID, $message);
	}

	// check if message length is 0
	if ($messageLength == 0) {
		$Server->wsClose($clientID);
		return;
	}
	$Server->log('Send message: '.json_encode($message));

	foreach ($Server->wsClients as $id => $client) {
		$Server->wsSend($id, json_encode($message));
	}
}

// when a client connects
function wsOnOpen($clientID) {
	global $Server;
// $Server->log(dirname(dirname(__FILE__).'/jsons/online.json'));
	$ip = long2ip( $Server->wsClients[$clientID][6] );

	actionUserOnline($clientID);

	$Server->log( "$ip ($clientID) has connected." );

	//Send a join notice to everyone but the person who joined
	foreach ( $Server->wsClients as $id => $client)
		if ($id == $clientID)
			$Server->wsSend($id, json_encode(['type'=>'connect', 'data'=>[
				'client_id' => $clientID,
				'listOnline' => getListUserOnline(true)
			]]));
		else 
			$Server->wsSend($id, json_encode(['type'=>'connect', 'data'=>[
				'client_id' => $clientID,
				'listOnline' => null
			]]));
}

// when a client closes or lost connection
function wsOnClose($clientID, $status) {
	global $Server;
	$ip = long2ip( $Server->wsClients[$clientID][6] );

	actionUserOffline($clientID);
	actionQuitTable($clientID);

	$Server->log( "$ip ($clientID) has disconnected.");

	//Send a user left notice to everyone in the room
	foreach ($Server->wsClients as $id => $client)
		$Server->wsSend($id, json_encode(['type'=>'disconnect', 'data'=>['client_id'=>$clientID]]));
}

// start the server
$Server = new PHPWebSocket();
$Server->bind('message', 'wsOnMessage');
$Server->bind('open', 'wsOnOpen');
$Server->bind('close', 'wsOnClose');
$Server->wsStartServer('192.168.1.102', 9300);

/************************************************
*                                               *
*                 RELATED ACTIONS               *
*                                               *
*************************************************/

/**
* @author Thanh Dao
* Save file information of new onlined user
*/
function actionUserOnline ($client_id) {
	$js = new Json(dirname(__FILE__).'/jsons/online.json');

	$data = $js->getFileContent();
	$data->$client_id = $client_id;
	$js->setFileContent($data);
}

/**
* @author Thanh Dao
* Remove offlined user from file and save
*/
function actionUserOffline ($client_id) {
	$js = new Json(dirname(__FILE__).'/jsons/online.json');
	$data = $js->getFileContent();
	$data->$client_id = null;
	$js->setFileContent($data);
}

/**
* Joining player to table
*/
function actionJoinGame ($client_id, $message) {
	global $Server;
	$js = new Json(dirname(__FILE__).'/jsons/table.json');
	$data = $js->getFileContent();
	$table = $message->data->table;
	$pos = $message->data->pos;

	if (empty($data->$table)) {
		$data->$table = new stdClass();
	}

	$data->$table->$pos = $client_id;
	$js->setFileContent($data);
}

/**
* Ready to playing
*/
function actionReadyGame ($client_id, $message) {

}

/**
* Remove user from table
*/
function actionQuitTable ($client_id) {
	global $Server;
	$js = new Json(dirname(__FILE__).'/jsons/table.json');
	$data = $js->getFileContent();

	foreach ($data as $key => $value) {
		foreach ($value as $key2 => $value2) {
			if ($value2 == $client_id) {
				$Server->log('Found value');
				$value2 = null;
			}
			$value->$key2 = $value2;
			$Server->log(json_encode($value));
		}
		$data->$key = $value;
	}

	$js->setFileContent($data);
}

function getListUserOnline ($isReturn = false) {
	$js = new Json(dirname(__FILE__).'/jsons/online.json');
	$data = $js->getFileContent();
	$online = [];
	foreach ($data as $key => $value) {
		if ($value != null) $online[] = $value;
	}
	if ($isReturn) return $online;
	else echo json_encode($online);
}
?>