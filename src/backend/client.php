<?php

class Client {
	private $socket;
	private $name;
	private $cards;
	private $saidUno;
	
	public function __construct($socket, $name){
		$this->socket = $socket;
		$this->name = $name;
		$this->cards = [];
	}
	
	public function getCards(){
		return $this->cards;
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function getSocket(){
		return $this->socket;
	}

	public function setUno(){
	    $this->saidUno = true;
    }

    public function isUnoPunishable(){
	    return (!$this->saidUno) && (count($this->cards) == 1);
    }

	public function giveCard($card){
	    $this->cards[] = $card;
	    $this->saidUno = false;
    }

	public function removeCard($card){
	    $index = array_search($card, $this->cards);
	    if($index != false){
	        unset($this->cards[$index]);
        }
    }
}

?>