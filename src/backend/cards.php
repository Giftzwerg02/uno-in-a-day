<?php


$colored_card_colors = ["red", "blue", "green", "yellow"];
$colored_card_names = ["zero", "one", "two", "three". "four", "five", "six", "seven", "eight", "nine", "blocked", "switch", "plus_two"];
$black_card_color = "black";
$black_card_names = ["plus_four", "color_change"];
$card_ids = [];

// ADDING COLOR-CARDS
for ($color=0; $color < count($colored_card_colors); $color++) { 
    
    for ($name=0; $name < count($colored_card_names); $name++) { 
        
        $card_ids[] = $colored_card_colors[$color] . "/" . $colored_card_names[$name];

    }

}

// ADDING BLACK CARDS
for ($name=0; $name < count($black_card_names); $name++) { 
    $card_ids[] = $black_card_color . "/" . $black_card_names[$name];
}

?>