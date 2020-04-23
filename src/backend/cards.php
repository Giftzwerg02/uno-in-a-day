<?php

class Cards {

    private $colored_card_colors = ["red", "blue", "green", "yellow"];
    private $colored_card_names = ["zero", "one", "two", "three". "four", "five", "six", "seven", "eight", "nine", "blocked", "switch", "plus_two"];
    private $black_card_color = "black";
    private $black_card_names = ["plus_four", "color_change"];
    private $card_ids = [];

    function __construct() {
        $this->generateCards();
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

}

?>