<?php
    #require "../../backend/protocol.php";
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
    <script src="../../../recourses/jquery-3.5.0.min.js" defer></script>
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
                    #echo "<p class='username'>$userArray[$i]->getName()</p>";
                    echo "<p class='card_count'>Karten: 2</p>";
                    echo "</div>";
                }
            ?>

    </div>

    <div class="sector" id="sector_mid">
        <div class="sub_sector" id="stack_sector">
            <div class="cards" id="stack">
                Hier Ablagestapel einfügen
            </div>

        </div>
        <div class="sub_sector" id="user_hand_sector">
            Spielerhand mit Karten
            <?php require "user_hand.php"?>
        </div>
    </div>
    <div class="sector" id="sector_right">
        <div class="cards" id="deck">
            Deck
        </div>

    </div>
</div>
</body>
