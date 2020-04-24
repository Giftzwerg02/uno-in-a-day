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
        $arr = [];
        $excluded = ['block', 'switch', 'plus_two', 'plus_four', 'color_change'];

        for ($i = 0; $i < count($this->card_ids); $i++){
            $name = $this->getCardName($this->card_ids[$i]);

            if(array_search($name, $excluded) === false){
                $arr[] = $name;
            }
        }

        return $this->randomElem($arr);
    }

    public function randomElem($arr){
        return $arr[array_rand($arr)];
    }
}

?>