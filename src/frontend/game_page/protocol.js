class Protocol {
    domain;
    connection;
    own_player_name;

    //---------------------------
    // Event Preprocessors
    //---------------------------
    preprocessors = {
        "game_start": (data) => {
            return [data['names'], data['first_card']];
        },

        "game_end": (data) => {
            return [data['winner']];
        },

        "give_card": (data) => {
            return [data['card']];
        },

        "update_user": (data) => {
            return [data['name'], data['cards']];
        },

        "update_current_user": (data) => {
            return [data['name']];
        },

        "disconnect_user": (data) => {
            return [data['name']];
        },

        "update_tos": (data) => {
            return [data['card'], data['consumed']];
        },

        "error": (data) => {
            return [data['message']];
        },
    };

    //---------------------------
    // Methods
    //---------------------------
    sessionInit(username) {
        var message = { type: "session_init", name: username };
        this.connection.send(JSON.stringify(message));
    }

    requestCard() {
        var message = { type: "request_card" };
        this.connection.send(JSON.stringify(message));
    }

    pushCard(card) {
        var message = {type: "push_card", card: card}
        this.connection.send(JSON.stringify(message));
    }

    endTurn() {
        var message = { type: "end_turn" };
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
                for( i in _triggers[event] ) {
                    if(params instanceof String) {
                        _triggers[event][i](params)
                    } else {
                        _triggers[event][i](...params);
                    }
                }
            }
        }
    };

    connect() {
        this.domain = window.location.hostname;
        this.connection = new WebSocket(`ws://${this.domain}:1337`, []);

        const protocol = this;
        
        this.connection.onerror = function (error) {
            protocol.events.triggerHandler('error', [error.message]);
        };
        
        this.connection.onmessage = function (event) {
            let json = JSON.parse(event.data);
            let type = json['type'];

            let args = protocol.preprocessors[type](json);

            protocol.events.triggerHandler(type, args);
        }
        
        this.connection.onopen = function () {
            let name = prompt("What's your name?");
            protocol.sessionInit(name);
            protocol.own_player_name = name;
        }
    }
}