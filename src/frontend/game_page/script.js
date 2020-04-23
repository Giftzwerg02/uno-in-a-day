"use strict";
$.getScript('./protocol.js', function() {

    const protocol = new Protocol();
    const userHand = document.getElementById("user_hand_sector");
    const tos = document.getElementById("stack");

    protocol.events.on("give_card", function(card) {
        addCardToPlayer(card);
    });

    protocol.events.on("update_tos", function(card) {
        addCardToTos(card);
    })

    function addCardToPlayer(card) {
        let cardHtml = cardToHtml(card);
        userHand.innerHTML += cardHtml;
        addEventListener("click", removeCardFromPlayer);
    }

    function removeCardFromPlayer(event) {
        let cardHtml = event.target;
        for (const child in userHand.children) {
            let childTag = userHand.children.item(child);
            if(childTag === cardHtml) {
                let card = getCardIdFromImg(childTag)
                protocol.pushCard(card);
                userHand.removeChild(childTag);
                break;
            }
        }
    }

    function addCardToTos(card) {
        let cardHtml = cardToHtml(card, "tos");
        tos.innerHTML = cardHtml;
    }

    function cardToHtml(card) {
        return `<img class='card' src='../img_cards/${card}.png'/>`;
    }

    function cardToHtml(card, id) {
        return `<img id="${id}" class='card' src='../img_cards/${card}.png'/>`;
    }

    function getCardIdFromImg(img) {
        return img.src.split("img_cards/")[1].split(".png")[0];
    }

});




/*v

cards.forEach(card => {
    addEventListener("giveCard", refreshDeck())
});*/