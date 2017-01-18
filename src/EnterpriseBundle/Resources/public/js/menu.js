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

$('#messages_page').click(function(e){
    $.ajax({
        url: "/messages",
        success: function(response){
            expand(response);
        }
    });
});

$('#calendar_page').click(function(e){
    $.ajax({
        url: "/calendar",
        success: function(response){
            expand(response);
        }
    });
});

$('#notepad_page').click(function(e){
    $.ajax({
        url: "/notepad",
        success: function(response){
            expand(response);
        }
    });
});

$('#card_page').click(function(e){
    $.ajax({
        url: "/card",
        success: function(response){
            expand(response);
        }
    });
});

$('#delivery_page').click(function(e){
    $.ajax({
        url: "/delivery",
        success: function(response){
            expand(response);
        }
    });
});

$('#documents_page').click(function(e){
    $.ajax({
        url: "/documents",
        success: function(response){
            expand(response);
        }
    });
});

$('#groups_page').click(function(e){
    $.ajax({
        url: "/groups",
        success: function(response){
            expand(response);
        }
    });
});

$('#important_page').click(function(e){
    $.ajax({
        success: function(response){
            expand(response.time);
        }
    });
});



$('#recycle_page').click(function(e){
    $.ajax({
        url: "/recycle",
        success: function(response){
            expand(response);
        }
    });
});

$('#search_page').click(function(e){
    $.ajax({
        url: "/search",
        success: function(response){
            expand(response);
        }
    });
});

$('#settings_page').click(function(e){
    $.ajax({
        url: "/settings",
        success: function(response){
            expand(response);
        }
    });
});

$('#workers_page').click(function(e){
    $.ajax({
        url: "/workers",
        success: function(response){
            expand(response);
        }
    });
});


