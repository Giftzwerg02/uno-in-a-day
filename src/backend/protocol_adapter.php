<?php

class ProtocolAdapter {
	const MINIMUM_PLAYER_THRESHOLD = 2;
	const INITIAL_CARD_COUNT = 7;
    const UNO_PUNISH_CARDS = 2;
	
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
				$card = Cards::getInstance()->getRandomStartingCard();
				$server->setTOS($card);
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
		    $amount = $server->getCardAccum();
		    if($amount == 0){ $amount = 1;}

			$this->giveRandomCards($server, $client, $amount);

		    $server->resetCardAccum();
		});
		
		$server->onPushCard(function ($client, $card) use ($server){
			$client->removeCard($card);
			$server->setTOS($card);

			$cards = Cards::getInstance();
			switch($cards->getCardName($card)){
                case "switch":
			        $server->switchDirection();
			        break;
                case "block":
                    $server->setTOSConsumed(false);
                    break;
                case "plus_two":
                    $server->increaseCardAccum(2);
                    $server->setTOSConsumed(false);
                    break;
                case "plus_four":
                    $server->increaseCardAccum(4);
                    $server->setTOSConsumed(false);
                    break;
            }
		});
		
		$server->onEndTurn(function ($client) use ($server){
		    $server->updateUser($client);
		    $server->updateTOS();
		    $server->updateToNextUser();
		    $server->setTOSConsumed(true);
		});
		
		$server->onUno(function ($client) use ($server){
            $client->setUno();
		});
		
		$server->onNoU(function ($client) use ($server){
		    foreach ($server->clients as $user){
		        if($user->isUnoPunishable()){
		            $this->giveRandomCards($server, $user, self::UNO_PUNISH_CARDS);
                }
            }
		});
	}
	
	private function giveRandomCards($server, $client, $amount = 1){
		for($i = 0; $i < $amount; $i++){
			$card = Cards::getInstance()->getRandomCard();
			$server->giveCard($client, $card);
			$client->giveCard($card);
		}
	}
}
?>