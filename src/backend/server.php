<?php

use HemiFrame\Lib\WebSocket\WebSocket;

require_once('..\vendor\HemiFrame\Lib\WebSockets\WebSocket.php'); // Not sure if needed
require_once('..\vendor\HemiFrame\Lib\WebSockets\Client.php');
require_once('client.php');

class Server {
	
	public $server;
	private $clients;
	private $handlers;
	private $tos;
	private $direction;
	private $card_accum;
	
	public function __construct(){
        try {
            $this->server = new WebSocket("0.0.0.0", 1337);
        } catch (Exception $e) {
            error_log("Could not create websocket: " . $e->getMessage());
        }

        $this->server->on("receive", function($socket, $data) {
			$this->onReceive($socket, $data);
		});
		
		$this->server->on("disconnect", function($socket, $statusCode, $reason) { 
			$this->onDisconnect($socket, $statusCode, $reason);
		});
		
		$this->clients = [];
		$this->direction = 'right';
		$this->resetCardAccum();
	}
	
	public function start(){
		$this->server->startServer();
	}

    public function sendErrorDirect($socket, $error){
        $this->sendDirect($socket, "error", ["message" => $error]);
    }
	
	public function sendError($client, $error){
		$this->sendErrorDirect($client->getSocket(), $error);
	}
	
	public function startGame($first_card){
		$names = [];
		
		foreach($this->clients as $client){
			$names[] = $client->getName();
		}
		
		foreach($this->clients as $client){

		    $arr = $this->rotateArray($names, $client->getName());
		    $index = array_search($client->getName(), $arr);
		    unset($arr[$index]);
		    $arr = array_values($arr);

			$this->send($client, "game_start", ["names" => $arr, "first_card" => $first_card]);
		}
	}
	
	public function endGame($winner){
		foreach($this->clients as $client){
			$this->send($client, "game_end", ["winner" => $winner]);
		}
	}
	
	public function giveCard($client, $card){
		$this->send($client, "give_card", ["card" => $card]);
	}
	
	public function updateUser($target_client){
		$payload = ["name" => $target_client->getName(), "cards" => count($target_client->getCards())];
		
		foreach($this->clients as $client){
			if($client != $target_client){
				$this->send($client, "update_user", $payload);
			}
		}
	}
	
	public function updateCurrentUser($target_client){
	    $this->clients = $this->rotateArray($this->clients, $target_client);

		foreach($this->clients as $client){
			$this->send($client, "update_current_user", ["name" => $target_client->getName()]);
		}
	}

	public function updateToNextUser(){
	    $rotation = 1;
        if($this->direction == 'left'){
            $rotation = -1;
        }

        $this->clients = $this->rotateArrayBy($this->clients, $rotation);
        $this->updateCurrentUser($this->clients[0]);
    }
	
	public function disconnectUser($target_client){
		// Remove the client from the array
		$index = array_search($target_client, $this->clients, true);
		unset($this->clients[$index]);
		
		foreach($this->clients as $client){
			$this->send($client, "disconnect_user", ["name" => $client->getName()]);
		}
	}

    public function disconnectSocket($socket){
        $this->server->disconnectClient($socket);
    }

	public function updateTOS(){
		foreach($this->clients as $client){
			$this->send($client, "update_tos", ["card" => $this->tos]);
		}
	}
	
	public function onSessionInit($callback){
		$this->handlers['session_init'] = function($socket, $data) use ($callback){
			$callback($socket, $data['name']);
		};
	}
	
	public function onRequestCard($callback){
		$this->handlers['request_card'] = function($client, $data) use ($callback){
			$callback($client);
		};
	}
	
	public function onPushCard($callback){
        $this->handlers['push_card'] = function($client, $data) use ($callback){
			$callback($client, $data['card']);
		};
	}
	
	public function onEndTurn($callback){
		$this->handlers['end_turn'] = function($client, $data) use ($callback){
			$callback($client);
		};
	}
	
	public function onUno($callback){
		$this->handlers['uno'] = function($client, $data) use ($callback){
			$callback($client);
		};
	}
	
	public function onNoU($callback){
		$this->handlers['no_u'] = function($client, $data) use ($callback){
			$callback($client);
		};
	}
	
	public function getClients(){
		return $this->clients;
	}

	public function addClient($socket, $name){
        $this->clients[] = new Client($socket, $name);
    }
	
	public function getClientByName($searched){
		foreach($this->clients as $client){
			if($client->getName() == $searched){
				return $client;
			}
		}
		return null;
	}

	public function setTOS($tos){
	    $this->tos = $tos;
    }

    public function switchDirection(){
	    if($this->direction == 'right'){
	        $this->direction = 'left';
        }
	    else {
	        $this->direction = 'right';
        }
    }

    public function getCardAccum(){
	    return $this->card_accum;
    }

    public function resetCardAccum(){
	    $this->card_accum = 0;
    }

    public function increaseCardAccum($amount){
        $this->card_accum += $amount;
    }
	
	private function getClientBySocket($searched){
		foreach($this->clients as $client){
			if($client->getSocket() == $searched){
				return $client;
			}
		}
		return null;
	}
	
	private function send($client, $type, $props){
		// We assume you know what you're doing
		$socket = $client->getSocket();
		$this->sendDirect($socket, $type, $props);
	}
	
	private function sendDirect($socket, $type, $props){
		$props['type'] = $type;
		$payload = json_encode($props);
		$this->server->sendData($socket, $payload);
	}
	
	private function onReceive($socket, $data){
		
		$arr = json_decode($data, true);
		$client = $this->getClientBySocket($socket);
		
		// Small ugly hack for init_session
		if($arr['type'] == 'session_init'){
			$client = $socket;
		}
		else if($client == null){
		    $this->sendErrorDirect($socket, "internal_error_client_not_found");
		    $this->disconnectSocket($socket);
        }
		
		$type = $arr['type'];
		$handler = $this->handlers[$type];
		$handler($client, $arr);
	}
	
	private function onDisconnect($socket, $statusCode, $reason){
		$client = $this->getClientBySocket($socket);
		if($client != null){
			$this->disconnectUser($client);
		}
	}

	private function rotateArray($arr, $first_elem){
	    $index = array_search($first_elem, $arr);
	    return $this->rotateArrayBy($arr, $index);
    }

    private function rotateArrayBy($arr, $by){
	    if($by < 0){
	        $by = count($arr) + $by;
        }

        for($i = 0; $i < $by; $i++){
            array_push($arr, array_shift($arr));
        }
        return $arr;
    }
}
?>