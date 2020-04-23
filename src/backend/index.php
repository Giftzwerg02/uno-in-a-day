<?php 

require "cards.php";
require "protocol_adapter.php";

$server = new Server();
$adapter = new ProtocolAdapter($server);

$server->start();

?>