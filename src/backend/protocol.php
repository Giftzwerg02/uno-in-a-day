<?php

require_once('vendor/HemiFrame/Lib/WebSockets.php');
require_once('user.php');

class Server {
	
	private $server;
	private $clients;
	
	public function _construct(){
		$this->server = new \HemiFrame\Lib\WebSocket\WebSocket("localhost", 1337);
	}
	
	public function sendError($user, $error){
		
	}
	
	public function startGame(){
		
	}
	
	public function endGame(){
		
	}
	
	public function giveCard($user, $card){
		
	}
	
	public function updateUser($user){
		
	}
	
	public function updateCurrentUser($user){
		
	}
	
	public function disconnectUser($user){
		
	}

	public function updateTOS($card){
			
	}
	
	public function onSessionInit($callback){
		
	}
	
	public function onRequestCard($callback){
		
	}
	
	public function onPushCard($callback){
		
	}
	
	public function onEndTurn($callback){
		
	}
	
	public function onUno($callback){
		
	}
	
	public function onNoU($callback){
		
	}
	
	public function getClients(){
	    //wenn ein Array geliefert wird: ok sonst in frontend/index.php ändern oder Patrick bescheidgeben
		return $clients;
	}
	
	public function getClientByName($searched){
		foreach($clients as $client){
			if($client->getName() == $searched){
				return $client;
			}
		}
		return null;
	}
	
	private function send($name, $type, $field_name, $field_content){		
		// We assume you know what you're doing
		$socket = getClientByName($name)->getSocket();
		$payload = json_encode(["type" => $type, $field_content => $field_name]);
		$server->sendData($socket, $payload);
	}
}
?>