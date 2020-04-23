class Protocol {
    domain;
    connection;

    //---------------------------
    // Methods
    //---------------------------
    setUsername(username) {
        var message = { type: username, name: username };
        this.connection.send(message);
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
        this.connection = new WebSocket(`ws://${this.domain}/backend/protocol.php`, []);

        this.connection.onerror = function (error) {
            this.sendEvent('error');
        };
    }
}