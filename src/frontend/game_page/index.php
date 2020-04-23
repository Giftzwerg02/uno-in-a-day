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
    <script src="script.js" defer></script>
    <link rel="stylesheet" href="style.css">
    <title>Uno with your Friends</title>
</head>
<body>
<?php
$users = 9;
?>
<div class="container" id="main">
    <div class="sector" id="sector_left">

            <?php
                for ($i=0; $i < $users; $i++){
                    echo "<div class='sub_sector sub_sector_user' id='user_sector'>";
                    echo "<p class='username'>Benjamin</p>";
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
        </div>
    </div>
    <div class="sector" id="sector_right">
        <div class="cards" id="deck">
            Deck
        </div>

    </div>
</div>
</body>
