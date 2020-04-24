"use strict";
$.getScript('./protocol.js', function() {

    var protocol = new Protocol();
    const userHand = document.getElementById("user_hand");
    const tos = document.getElementById("tos");
    let colorButtons = Array.from(document.getElementsByClassName("color"));
    colorButtons.forEach(button => button.addEventListener("click", chooseColor));
    const deckCard = document.getElementById("deck_card");
    deckCard.addEventListener("click", function() { protocol.requestCard()});

    protocol.events.on("game_start", function(players, card) {
        addCardToTos(card);
    });

    protocol.events.on("give_card", function(card) {
        addCardToPlayer(card);
    });

    protocol.events.on("update_tos", function(card) {
        addCardToTos(card);
    });

<<<<<<< HEAD
    protocol.events.on("game_start", function(players, card) {
        addCardToTos(card);
    });

=======
    protocol.events.on("error", function(message) {
        alert("Error: " + message);
    });

    protocol.connect();
>>>>>>> master

    function addCardToPlayer(card) {
        let src = cardToSrc(card);
        let img = new Image();
        img.src = src;
        $(img).addClass("card");
        if(!tos.src.endsWith("card_backside.png") && !cardFitsOnTos(card)) {
            $(img).removeClass("card:hover").addClass("nofit");
        }
        userHand.appendChild(img);
        img.addEventListener("click", removeCardFromPlayer);
    }

    // this gets executed when a player wants to place a card
    function removeCardFromPlayer(event) {
        let cardHtml = event.target;
        let cardId = getCardIdFromImg(cardHtml);

        if(!cardFitsOnTos(cardId)) {
            return;
        }

        for (const child in userHand.children) {
            let childTag = userHand.children.item(child);
            if(childTag === cardHtml) {
                                
                let cardName = cardId.split("/")[1];
                if(cardName === "plus_four" || cardName === "color_change") {
                    generateColorChoice();
                    Array.from(userHand.children).forEach(cardImg => {
                        $(cardImg).addClass("nofit").removeClass("card:hover");
                    });
                    tos.src = cardToSrc(cardId);
                    userHand.removeChild(childTag);
                } else {
                    protocol.pushCard(cardId);
                    userHand.removeChild(childTag);
                    protocol.events.triggerHandler("update_tos", cardId);
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
        protocol.pushCard(cardId);
        protocol.events.triggerHandler("update_tos", cardId);
        colorButtons.forEach(button => {
            button.hidden = true;
        });
    }


    function addCardToTos(card) {
        let src = cardToSrc(card);
        tos.src = src;
        Array.from(userHand.children).forEach(cardImg => {
            if(cardFitsOnTos(getCardIdFromImg(cardImg))) {
                $(cardImg).removeClass("nofit").addClass("card:hover");
            } else {
                $(cardImg).addClass("nofit").removeClass("card:hover");
            }
        });

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
        if(tosComponents.name === "block") {
            let canPlace = cardComponents.name === "block";
            if(canPlace) {
                return true;
            } 
            protocol.endTurn();
            return false;
        }

        if(tosComponents.name === "plus_two") {
            let canPlace = cardComponents.name === "plus_two" || cardComponents.name === "plus_four";
            if(canPlace) {
                return true;
            }
            protocol.requestCard();
            protocol.endTurn();
            return false;
        }

        if(tosComponents.name === "plus_four") {
            let canPlace = cardComponents.name === "plus_four";
            if(canPlace) {
                return true;
            }
            protocol.requestCard();
            protocol.endTurn();
            return false;
        }

        return (cardComponents.color === tosComponents.color || cardComponents.name === tosComponents.name || cardComponents.color === "black") && tosComponents.color !== "black";

    }

    /*protocol.events.triggerHandler("update_tos", ["blue/four"]);
    protocol.events.triggerHandler("give_card", ["blue/three"]);
    protocol.events.triggerHandler("give_card", ["red/three"]);
    protocol.events.triggerHandler("give_card", ["black/plus_four"]);
    protocol.events.triggerHandler("give_card", ["green/nine"]);
    protocol.events.triggerHandler("give_card", ["black/color_change"]);*/

});