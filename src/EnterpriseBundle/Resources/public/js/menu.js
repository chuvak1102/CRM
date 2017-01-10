$('#menu div').click(function(e){
    $('#menu div, a').removeClass('active');
    $('#'+ e.target.id).addClass('active');
});

function expand(response){
    $('#content')
        .animate({
            "opacity" : 0
        }, 300, function() {
            $(this).empty();
            $(this).html(response);
            $(this).animate({
                "opacity" : 1
            }, 500, function() {

            });
        });
}

$('#calendar_page').click(function(e){
    $.ajax({
        url: "/calendar",
        data: {
            'ok' : 'ok'
        },
        success: function(response){
            expand(response);
        }
    });
});

$('#card_page').click(function(e){
    $.ajax({
        url: "/card",
        data: {
            'ok' : 'ok'
        },
        success: function(response){
            expand(response);
        }
    });
});

$('#delivery_page').click(function(e){
    $.ajax({
        url: "/delivery",
        data: {
            'ok' : 'ok'
        },
        success: function(response){
            expand(response);
        }
    });
});

$('#documents_page').click(function(e){
    $.ajax({
        url: "/documents",
        data: {
            'ok' : 'ok'
        },
        success: function(response){
            expand(response);
        }
    });
});

$('#groups_page').click(function(e){
    $.ajax({
        url: "/groups",
        data: {
            'ok' : 'ok'
        },
        success: function(response){
            expand(response);
        }
    });
});

$('#important_page').click(function(e){
    $.ajax({
        url: "/important",
        data: {
            'ok' : 'ok'
        },
        success: function(response){
            expand(response.time);
        }
    });
});

$('#messages_page').click(function(e){
    $.ajax({
        url: "/messages",
        data: {
            'ok' : 'ok'
        },
        success: function(response){
            expand(response);
        }
    });
});

$('#recycle_page').click(function(e){
    $.ajax({
        url: "/recycle",
        data: {
            'ok' : 'ok'
        },
        success: function(response){
            expand(response);
        }
    });
});

$('#search_page').click(function(e){
    $.ajax({
        url: "/search",
        data: {
            'ok' : 'ok'
        },
        success: function(response){
            expand(response);
        }
    });
});

$('#settings_page').click(function(e){
    $.ajax({
        url: "/settings",
        data: {
            'ok' : 'ok'
        },
        success: function(response){
            expand(response);
        }
    });
});

$('#workers_page').click(function(e){
    $.ajax({
        url: "/workers",
        data: {
            'ok' : 'ok'
        },
        success: function(response){
            expand(response);
        }
    });
});


