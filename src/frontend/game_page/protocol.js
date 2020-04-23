class Protocol {
    domain;
    connection;

    //---------------------------
    // Methods
    //---------------------------
    sessionInit(username) {
        var message = { type: "session_init", name: username };
        this.connection.send(JSON.stringify(message));
    }

    error(errorMessage) {
        var message = { type: "error", message: errorMessage };
        this.connection.send(JSON.stringify(message));
    }

    gameStart(names, firstCard) {
        var message = { type: "game_start", names: names, first_card: firstCard };
        this.connection.send(JSON.stringify(message));
    }

    gameEnd(winner) {
        var message = { type: "game_end", winner: winner };
        this.connection.send(JSON.stringify(message));
    }

    requestCard() {
        var message = { type: "request_card" };
        this.connection.send(JSON.stringify(message));
    }

    giveCard(card) {
        var message = { type: "give_card", card: card };
        this.connection.send(JSON.stringify(message));
    }

    pushCard(card) {
        var message = {type: "push_card", card: card}
        this.connection.send(JSON.stringify(message));
    }

    updateUser(name, cards) {
        var message = { type: "update_user", name: name, cards: cards };
        this.connection.send(JSON.stringify(message));
    }

    updateCurrentUser(name) {
        var message = { type: "update_current_user", name: name };
        this.connection.send(JSON.stringify(message));
    }

    disconnectUser(name) {
        var message = { type: "disconnect_user", name: name };
        this.connection.send(JSON.stringify(message));
    }

    endTurn() {
        var message = { type: "end_turn" };
        this.connection.send(JSON.stringify(message));
    }
    
    updateTos(card) {
        var message = { type: "update_tos", card: card };
        this.connection.send(JSON.stringify(message));
    }
    
    uno() {
        var message = { type: "uno" };
        this.connection.send(JSON.stringify(message));
    }

    noU() {
        var message = { type: "no_u" };
        this.connection.send(JSON.stringify(message));
    }
    
    echo(message) {
        console.log("echo echo echo " + message);
    }

    //---------------------------
    // Events
    // Example:
    // protocolObject.events.on('error', yourFunction);
    // Events: error
    //---------------------------
    events = new function() {
        var _triggers = {};
      
        this.on = function(event,callback) {
            if(!_triggers[event])
                _triggers[event] = [];
            _triggers[event].push( callback );
          }
      
        this.triggerHandler = function(event,params) {
            if( _triggers[event] ) {
                var i;
                for( i in _triggers[event] )
                    _triggers[event][i](params);
            }
        }
    };

    sendEvent(eventText) {
        this.events.triggerHandler(eventText);
    }


    constructor() {
        this.domain = window.location.hostname;
        this.connection = new WebSocket(`ws://${this.domain}:1337/backend/protocol.php`, []);

        const protocol = this;
        this.connection.onerror = function (error) {
            protocol.sendEvent('error');
        };
    }
}