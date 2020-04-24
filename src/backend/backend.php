<?php 

require_once("cards.php");
require_once("protocol_adapter.php");
require_once('server.php');
require_once('..\vendor\HemiFrame\Lib\WebSockets\WebSocket.php');
require_once('..\vendor\HemiFrame\Lib\WebSockets\Client.php');
require_once('client.php');

$server = new Server();
$adapter = new ProtocolAdapter($server);

$server->start();

?>