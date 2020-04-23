<?php

class Client {
	private $socket;
	private $name;
	private $cards;
	
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
}

?>