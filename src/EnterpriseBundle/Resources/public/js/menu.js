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
        url: "/admin/messages",
        success: function(response){
            expand(response);
        }
    });
});

$('#calendar_page').click(function(e){
    $.ajax({
        url: "/admin/calendar",
        success: function(response){
            expand(response);
        }
    });
});

$('#notepad_page').click(function(e){
    $.ajax({
        url: "/admin/notepad",
        success: function(response){
            expand(response);
        }
    });
});

$('#documents_page').click(function(e){
    $.ajax({
        url: "/admin/documents",
        success: function(response){
            expand(response);
        }
    });
});

$('#constructor_page').click(function(e){
    $.ajax({
        url: "/admin/constructor",
        success: function(response){
            expand(response);
        }
    });
});

$('#delivery_page').click(function(e){
    $.ajax({
        url: "/admin/delivery",
        success: function(response){
            expand(response);
        }
    });
});

$('#settings_page').click(function(e){
    $.ajax({
        url: "/admin/settings",
        success: function(response){
            expand(response);
        }
    });
});




//$('#card_page').click(function(e){
//    $.ajax({
//        url: "/admin/card",
//        success: function(response){
//            expand(response);
//        }
//    });
//});
//

//
//
//
//$('#groups_page').click(function(e){
//    $.ajax({
//        url: "/admin/groups",
//        success: function(response){
//            expand(response);
//        }
//    });
//});
//
//$('#important_page').click(function(e){
//    $.ajax({
//        success: function(response){
//            expand(response.time);
//        }
//    });
//});
//
//
//
//$('#recycle_page').click(function(e){
//    $.ajax({
//        url: "/admin/recycle",
//        success: function(response){
//            expand(response);
//        }
//    });
//});
//
//$('#search_page').click(function(e){
//    $.ajax({
//        url: "/admin/search",
//        success: function(response){
//            expand(response);
//        }
//    });
//});
//
//$('#workers_page').click(function(e){
//    $.ajax({
//        url: "/admin/workers",
//        success: function(response){
//            expand(response);
//        }
//    });
//});


