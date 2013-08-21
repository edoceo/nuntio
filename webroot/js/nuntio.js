
var nuntio = {
    auto_reconnect:true,
    error_count:0,
    line_id:0,
    room:null,
    send_queue:[],
    tick:null,      // Timer Handle
    uri:null,
    ws:null
};

nuntio.do_size = function() {

    var w = $(window).width();
    var h = $(window).height() - $('#head').height();

    $('#chat').css({
        top:0,
        left:0,
        width:w - 200,
        height:h
    });
    
    $('#chat-list').css({
        height:(h - $('#chat-head').height() - $('#chat-foot').height() - 4)
    });

    $('#tool').css({
        top:0,
        left:(w - 200),
        width:200,
        height:h
    });
};

nuntio.stat = function(kind,text) {
    switch (kind) {
    case 'good':
        break;
    case 'info':
        $('.chat-stat').css({color:'inherit'});
        $('.chat-stat').html('Connected');
        $('#chat-foot input').attr('disabled',false);
        break;
    case 'warn':
        break;
    case 'fail':
        $('.chat-stat').html('Error:' + text);
        $('.chat-stat').css({color:'#f00'});
        $('#chat-foot input').attr('disabled',true);
        break;
    }
};

// Interface
nuntio.join = function() { };
nuntio.open = function() { };
nuntio.send = function() { };
nuntio.recv = function() { };
