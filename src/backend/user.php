<?php

class User {
	private $client;
	private $name;
	private $cards;
	
	public function _construct($client, $name){
		$this->client = $client;
		$this->name = $name;
		$this->cards = [];
	}
	
	public function getCards(){
		return $cards;
	}
	
	public function getName(){
		return $name;
	}
	
	public function getSocket(){
		return $client;
	}
}

?>