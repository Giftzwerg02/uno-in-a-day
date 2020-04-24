<?php

class Cards {

    private $colored_card_colors = ["red", "blue", "green", "yellow"];
    private $colored_card_names = ["zero", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine", "block", "switch", "plus_two"];
    private $black_card_color = "black";
    private $black_card_names = ["plus_four", "color_change"];
    private $card_ids = [];
	
	private static $instance = null;

    function __construct() {
        $this->generateCards();
    }
	
	public static function getInstance(){
		if (self::$instance == null) {
			self::$instance = new Cards();
		}
 
		return self::$instance;
	}

    private function generateCards() {

        // ADDING COLOR-CARDS
        for ($color=0; $color < count($this->colored_card_colors); $color++) { 
                
            for ($name=0; $name < count($this->colored_card_names); $name++) { 
                
                $this->card_ids[] = $this->colored_card_colors[$color] . "/" . $this->colored_card_names[$name];

            }

        }

        // ADDING BLACK CARDS
        for ($name=0; $name < count($this->black_card_names); $name++) { 
            $this->card_ids[] = $this->black_card_color . "/" . $this->black_card_names[$name];
        }

    }
    
    public function getCardColor($card_id) {
        return explode("/", $card_id)[0];
    }

    public function getCardName($card_id) {
        return explode("/", $card_id)[1];
    }

    public function getCardFile($card_id) {
        return $card_id . ".png";
    }

    public function getCardIds() {
        return $this->card_ids;
    }

	public function getRandomCard(){
		return $this->randomElem($this->card_ids);
	}

	public function getRandomStartingCard(){
        $arr = $this->card_ids;
        for ($i = 0; $i < count($arr); $i++){
            if(
                $this->endsWith($arr[$i], 'block') ||
                $this->endsWith($arr[$i], 'switch') ||
                $this->endsWith($arr[$i], 'plus_two') ||
                $this->endsWith($arr[$i], 'plus_four') ||
                $this->endsWith($arr[$i], 'color_change')
            ){
                unset($arr[$i]);
            }
        }

        return $this->randomElem($arr);
    }


    // https://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php
    private function endsWith($haystack, $needle) {
        return substr_compare($haystack, $needle, -strlen($needle)) === 0;
    }

    public function randomElem($arr){
        return $arr[array_rand($arr)];
    }
}

?>