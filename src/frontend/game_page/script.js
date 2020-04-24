"use strict";
/**
 * For further explanaition: https://www.youtube.com/watch?v=V1bFr2SWP1I
 */
$.getScript('./protocol.js', function() {

    var protocol = new Protocol();
    var current_player;
    var hasChosenCard = false;
    var hasTakenCard = false;
    var hasBeenConsumed;
    const userHand = document.getElementById("user_hand");
    const tos = document.getElementById("tos");
    let colorButtons = Array.from(document.getElementsByClassName("color"));
    colorButtons.forEach(button => button.addEventListener("click", chooseColor));
    const deckCard = document.getElementById("deck_card");
    deckCard.addEventListener("click", function() {
        if(!hasTakenCard && current_player == protocol.own_player_name){
            hasTakenCard = true;
            protocol.requestCard();
            updateBlur();
        }
    });
    const endTurnBtn = document.getElementById("end");
    endTurnBtn.addEventListener("click", function () {
        if(current_player == protocol.own_player_name) { // only pressable if it is your turn!
            hasChosenCard = false;
            hasTakenCard = false;
            updateBlur();
            protocol.endTurn();
        }
    });

    protocol.events.on("game_start", function(players, card) {
        addCardToTos(card);
    });

    protocol.events.on("update_current_user", function(player) {
        current_player = player;

        let own_turn = player == protocol.own_player_name;
        $("#end").prop("disabled", !own_turn);

        if(own_turn){
            $("#deck_card").addClass("card:hover").removeClass("nofit");
            $("#end").addClass("card:hover").removeClass("nofit");
        }
        else {
            $("#deck_card").removeClass("card:hover").addClass("nofit");
            $("#end").removeClass("card:hover").addClass("nofit");
        }

        updateBlur();
    });

    protocol.events.on("give_card", function(card) {
        addCardToPlayer(card);
    });

    protocol.events.on("update_tos", function(card, consumed) {
        addCardToTos(card);
        hasBeenConsumed = consumed;
    });

    protocol.events.on("error", function(message) {
        alert("Error: " + message);
    });

    protocol.connect();

    function addCardToPlayer(card) {
        let src = cardToSrc(card);
        let img = new Image();
        img.src = src;
        $(img).addClass("card");
        if(!tos.src.endsWith("card_backside.png") && !cardFitsOnTos(card)) {
            $(img).removeClass("card:hover").addClass("nofit");
        }
        userHand.appendChild(img);
        img.addEventListener("click", function(event) {
            console.log("hasChosenCard: " + hasChosenCard)
            if(!hasChosenCard && current_player == protocol.own_player_name){
                removeCardFromPlayer(event);
            }
        });
    }

    // this gets executed when a player wants to place a card
    function removeCardFromPlayer(event) {
        let cardHtml = event.target;
        let cardId = getCardIdFromImg(cardHtml);

        if(!cardFitsOnTos(cardId)) {
            return;
        }
        hasChosenCard = true;
        for (const child in userHand.children) {
            let childTag = userHand.children.item(child);
            if(childTag === cardHtml) {
                let cardName = cardId.split("/")[1];
                if(cardName === "plus_four" || cardName === "color_change") {
                    generateColorChoice()
                    addCardToTos(cardId);
                    Array.from(userHand.children).forEach(cardImg => {
                        $(cardImg).addClass("nofit").removeClass("card:hover");
                    });
                    userHand.removeChild(childTag);
                } else {
                    protocol.pushCard(cardId);
                    addCardToTos(cardId);
                    userHand.removeChild(childTag);
                    break;
                }
            }
        }
    }

    function generateColorChoice() {
        colorButtons.forEach(button => {
            button.hidden = false;
        });
    }

    function chooseColor(event) {
        let color = event.target.id;
        let chosenColor = color;
        let cardId = getCardIdFromImg(tos);
        cardId = chosenColor + "/" + cardId.split("/")[1];
        addCardToTos(cardId);
        protocol.pushCard(cardId);
        colorButtons.forEach(button => {
            button.hidden = true;
        });
    }


    function addCardToTos(card) {
        let src = cardToSrc(card);
        tos.src = src;
        updateBlur();
    }

    function cardToSrc(card) {
        return `../img_cards/${card}.png`;
    }

    function getCardIdFromImg(img) {
        return img.src.split("img_cards/")[1].split(".png")[0];
    }

    function cardFitsOnTos(card) {

        const cardComponents = { "color": card.split("/")[0], "name": card.split("/")[1] };
        const tosCard = getCardIdFromImg(tos);
        const tosComponents = { "color": tosCard.split("/")[0], "name": tosCard.split("/")[1] };
        if(!hasBeenConsumed) {
            if(tosComponents.name === "block") {
                return cardComponents.name === "block";
            }

            if(tosComponents.name === "plus_two") {
                return cardComponents.name === "plus_two" || cardComponents.name === "plus_four";
            }

            if(tosComponents.name === "plus_four") {
                return cardComponents.name === "plus_four";
            }
        }

        return (cardComponents.color === tosComponents.color || cardComponents.name === tosComponents.name || cardComponents.color === "black") && tosComponents.color !== "black";

    }

    function updateBlur(){
        let own_turn = current_player == protocol.own_player_name;
        Array.from(userHand.children).forEach(cardImg => {
            if(cardFitsOnTos(getCardIdFromImg(cardImg)) && own_turn && !hasChosenCard) {
                $(cardImg).removeClass("nofit").addClass("card:hover");
            } else {
                $(cardImg).addClass("nofit").removeClass("card:hover");
            }
        });

        if(hasTakenCard || !own_turn) {
            $(deckCard).addClass("nofit").removeClass("card:hover");
        } else {
            $(deckCard).removeClass("nofit").addClass("card:hover");
        }
    }
});