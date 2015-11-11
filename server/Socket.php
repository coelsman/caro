<?php
require_once('PHPWebSocket.php');
set_time_limit(0);

// when a client sends data to the server
function wsOnMessage($clientID, $message, $messageLength, $binary) {

	if ($clientID == 1 || $clientID == 2) {
		global $Server;
		$ip = long2ip( $Server->wsClients[$clientID][6] );

		// check if message length is 0
		if ($messageLength == 0) {
			$Server->wsClose($clientID);
			return;
		}
		$Server->log('Send message: '.$message);

		foreach ($Server->wsClients as $id => $client) {
			if ($id != $clientID) {
				$Server->wsSend($id, $message);
			}
		}
	}
}

// when a client connects
function wsOnOpen($clientID) {
	global $Server;
	$ip = long2ip( $Server->wsClients[$clientID][6] );

	$Server->log( "$ip ($clientID) has connected." );

	//Send a join notice to everyone but the person who joined
	foreach ( $Server->wsClients as $id => $client )
		if ( $id != $clientID )
			$Server->wsSend($id, "Visitor $clientID ($ip) has joined the room.");
}

// when a client closes or lost connection
function wsOnClose($clientID, $status) {
	global $Server;
	$ip = long2ip( $Server->wsClients[$clientID][6] );

	$Server->log( "$ip ($clientID) has disconnected." );

	//Send a user left notice to everyone in the room
	foreach ( $Server->wsClients as $id => $client )
		$Server->wsSend($id, "Visitor $clientID ($ip) has left the room.");
}

// start the server
$Server = new PHPWebSocket();
$Server->bind('message', 'wsOnMessage');
$Server->bind('open', 'wsOnOpen');
$Server->bind('close', 'wsOnClose');
$Server->wsStartServer('localhost', 9300);

?>