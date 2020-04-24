<?php
    session_start();

if (!isset($_SESSION['visited'])) {
    # echo "Du hast diese Seite noch nicht besucht";
    $_SESSION['visited'] = true;
} else {
    #echo "Du hast diese Seite zuvor schon aufgerufen";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../../jquery-3.5.0.min.js" defer></script>
    <script src="script.js" defer></script>
    <link rel="stylesheet" href="style.css">
    <title>Uno with your Friends</title>
</head>
<body>
<?php

?>
<div class="container" id="main">
    <div class="sector" id="sector_left">

            <?php
            $userArray = ['Johannes', 'Nick', 'Nikita', 'Hans', 'Benjamin', 'Patrick', 'Gustav'];
            #$userArray = getClients();
                for ($i=0; $i < count($userArray); $i++){
                    echo "<div class='sub_sector sub_sector_user' id='user_sector'>";
                    echo "<p class='username'>$userArray[$i]</p>";
                    #echo "<p class='username'>$userArray[$i].getName()</p>";
                    echo "<p class='card_count'>Karten: 2</p>";
                    echo "</div>";
                }
            ?>

    </div>

    <div class="sector" id="sector_mid">
        <div class="sub_sector" id="stack_sector">
            <div class="cards" id="stack">
                Hier Ablagestapel einfügen
                <img id="tos" class="card" src="../img_cards/card_backside.png"/>
            </div>
        </div>
        <div class="sub_sector" id="user_hand_sector">
            Spielerhand mit Karten
            <div id="user_hand">

            </div>
        </div>

        <div class="sub_sector" id="color_choice">
            
            <div id="red" class="color" hidden></div>
            <div id="green" class="color" hidden></div>
            <div id="blue" class="color" hidden></div>
            <div id="yellow" class="color" hidden></div>

        </div>
        
    </div>
    <div class="sector" id="sector_right">
        <div class="cards" id="deck">
            
            <img id="deck_card" src="../img_cards/card_backside.png" alt="Deck">
            
        </div>

    </div>
</div>
</body>
