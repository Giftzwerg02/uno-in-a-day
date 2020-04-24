<?php
require_once('server.php');

class ProtocolAdapter {
	const MINIMUM_PLAYER_THRESHOLD = 2;
	const INITIAL_CARD_COUNT = 7;
	
	public function __construct($server){
		$server->onSessionInit(function ($socket, $name) use ($server){
		    if($server->getClientByName($name) != null){
		        $server->sendErrorDirect($socket, "username_taken");
                $server->disconnectSocket($socket);
		        return;
            }

            $server->addClient($socket, $name);
            $clients = $server->getClients();

			if(count($clients) >= self::MINIMUM_PLAYER_THRESHOLD){

				// Start the game with a random card on stack
				$card = Cards::getInstance()->getRandomCard();
				$server->startGame($card);
				
				// Give everybody some cards
				foreach($clients as $user){
					$this->giveRandomCards($server, $user, self::INITIAL_CARD_COUNT);
				}
				
				// Choose the starting player
				$rand_client = $clients[array_rand($clients)];
				$server->updateCurrentUser($rand_client);
			}
		});
		
		$server->onRequestCard(function ($client) use ($server){
			$this->giveRandomCards($server, $client);
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
	
	private function giveRandomCards($server, $client, $amount = 1){
		for($i = 0; $i < $amount; $i++){
			$card = Cards::getInstance()->getRandomCard();
			$server->giveCard($client, $card);
		}
	}
}
?>