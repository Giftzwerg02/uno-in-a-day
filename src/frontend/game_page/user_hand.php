<?php

    require "../../backend/cards.php";

    # Add code in to retrieve cards from user after protocol.php is done
    
    $cards = new Cards();
    $card_ids = $cards->getCardIds();
    $user_test_cards_ids = [$card_ids[0], $card_ids[1], $card_ids[2], $card_ids[49], $card_ids[2], $card_ids[2], $card_ids[2]];

    foreach ($user_test_cards_ids as $card_id) {

        echo "<img class='card' src='../img_cards/${card_id}.png'/>";
        
    }

?>