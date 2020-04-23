"use strict";
$.getScript('./protocol.js', function() {

    const protocol = new Protocol();
    const userHand = document.getElementById("user_hand");
    const tos = document.getElementById("tos");

    protocol.events.on("give_card", function(card) {
        addCardToPlayer(card);
    });

    protocol.events.on("update_tos", function(card) {
        addCardToTos(card);
    })

    function addCardToPlayer(card) {
        let src = cardToSrc(card);
        let img = new Image();
        img.src = src;
        $(img).addClass("card");
        if(!cardFitsOnTos(card)) {
            $(img).removeClass("card:hover").addClass("nofit");
        }
        userHand.appendChild(img);
        img.addEventListener("click", removeCardFromPlayer);
    }

    function removeCardFromPlayer(event) {
        let cardHtml = event.target;
        let cardId = getCardIdFromImg(cardHtml);

        if(!cardFitsOnTos(cardId)) {
            return;
        }

        for (const child in userHand.children) {
            let childTag = userHand.children.item(child);
            if(childTag === cardHtml) {
                protocol.pushCard(cardId);
                userHand.removeChild(childTag);
                protocol.events.triggerHandler("update_tos", cardId);
                break;
            }
        }
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
        
        return cardComponents.color === tosComponents.color || cardComponents.name === tosComponents.name || cardComponents.color === "black";

    }

    protocol.events.triggerHandler("update_tos", "blue/four");

    protocol.events.triggerHandler("give_card", "blue/three");
    protocol.events.triggerHandler("give_card", "red/three");
    protocol.events.triggerHandler("give_card", "black/plus_four");
    protocol.events.triggerHandler("give_card", "green/nine");

});




/*v

cards.forEach(card => {
    addEventListener("giveCard", refreshDeck())
});*/