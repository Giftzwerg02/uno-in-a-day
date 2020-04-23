<?php

require_once('..\vendor\HemiFrame\Lib\WebSockets\WebSocket.php');
require_once('client.php');

class Server {
	
	private $server;
	private $clients;
	private $handlers;
	
	public function __construct(){
		$this->server = new \HemiFrame\Lib\WebSocket\WebSocket("localhost", 1337);
		
		$this->server->on("receive", onReceive);
		$this->server->on("disconnect", onDisconnect);
	}
	
	public function sendError($user, $error){
		$this->send($user, "error", ["message" => $error]);
	}
	
	public function startGame($first_card){
		$names = [];
		
		foreach($this->clients as $client){
			$names[] = $client->getName();
		}
		
		foreach($this->clients as $client){
			$this->sendDirect($client, "game_start", ["names" => $names, "first_card" => $first_card]);
		}
	}
	
	public function endGame($winner){
		foreach($this->clients as $client){
			$this->sendDirect($client, "game_end", ["winner" => $winner]);
		}
	}
	
	public function giveCard($user, $card){
		$this->send($user, "give_card", ["card" => $card]);
	}
	
	public function updateUser($user){
		$target_client = getClientByName($user);
		$payload = ["name" => $user, "cards" => count($target_client->getCards())];
		
		foreach($this->clients as $client){
			if($client != $target_client){
				$this->sendDirect($client, "update_user", $payload);
			}
		}
	}
	
	public function updateCurrentUser($user){
		foreach($this->clients as $client){
			$this->sendDirect($client, "update_current_user", ["name" => $user]);
		}
	}
	
	public function disconnectUser($user){
		// Remove the client from the array
		$target_client = $this->getClientByName($user);
		$index = array_search($target_client, $this->clients, true);
		unset($this->clients[$index]);
		
		foreach($this->clients as $client){
			$this->sendDirect($client, "disconnect_user", ["name" => $user]);
		}
	}

	public function updateTOS($card){
		foreach($this->clients as $client){
			$this->sendDirect($client, "update_tos", ["card" => $card]);
		}
	}
	
	public function onSessionInit($callback){
		$handlers['session_init'] = function($client, $data) use ($callback){
			$callback($client, $data['name']);
		};
	}
	
	public function onRequestCard($callback){
		$handlers['request_card'] = function($client, $data) use ($callback){
			$callback($client, $data['card']);
		};
	}
	
	public function onPushCard($callback){
		$handlers['push_card'] = function($client, $data) use ($callback){
			$callback($client);
		};
	}
	
	public function onEndTurn($callback){
		$handlers['end_turn'] = function($client, $data) use ($callback){
			$callback($client);
		};
	}
	
	public function onUno($callback){
		$handlers['uno'] = function($client, $data) use ($callback){
			$callback($client);
		};
	}
	
	public function onNoU($callback){
		$handlers['no_u'] = function($client, $data) use ($callback){
			$callback($client);
		};
	}
	
	public function getClient(){
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
	
	private function getClientBySocket($searched){
		foreach($clients as $client){
			if($client->getSocket() == $searched){
				return $client;
			}
		}
		return null;
	}
	
	private function send($name, $type, $props){		
		// We assume you know what you're doing
		$client = getClientByName($name);
		sendDirect($client, $type, $props);
	}
	
	private function sendDirect($client, $type, $props){
		$socket = $client->getSocket();
		$props['type'] = $type;
		$payload = json_encode($props);
		$server->sendData($socket, $payload);
	}
	
	private function onReceive($socket, $data){
		$arr = json_decode($data);
		$client = $this->getClientBySocket($socket);
		
		$handler = $handlers[$arr['type']];
		$handler($client, $data);
	}
	
	private function onDisconnect($socket, $statusCode, $reason){
		$client = $this->getClientBySocket($socket);
		disconnectUser($client);
	}
}
?>