<?php
require_once('server.php');

class ProtocolAdapter {
	public function __construct($server){
		$server->onSessionInit(function ($socket, $name) use ($server){
			return new Client($socket, $name);
		});
		
		$server->onRequestCard(function ($client) use ($server){
			$card = Cards::getInstance()->getRandomCard();
			$server->giveCard($client, $card);
		});
		
		$server->onPushCard(function ($client, $card) use ($server){
			
		});
		
		$server->onEndTurn(function ($client) use ($server){
			
		});
		
		$server->onUno(function ($client) use ($server){
			
		});
		
		$server->onNoU(function ($client) use ($server){
			
		});
	}
}
?>