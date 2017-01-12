// open last dialog
$(document).ready(function(){
    window.lastDialog = getLastDialog();
    update();
});

function fillUsersInDialog(userArray){
    var users = userArray.users;
    var ids = userArray.ids;
    for(var i = 0; i < users.length; i++){
        var u = $('<div></div>')
            .addClass('peoples_single')
            .attr('id', ids[i]+'remove_user');
        var img = $('<img>')
            .attr('src', 'images/default_avatar.png');
        var name = $('<div></div>')
            .addClass('name').html(users[i]);
        u.append(img);
        u.append(name);
        $('#peoples').append(u);
    }
}


// auto update messages
//var timer = setInterval(update, 10000);
//$.session.set('interval', timer);

// search users
$('#search_people').on('input', function(){
    var name = $(this).val();
    var availUsers = $('#avail_users');
    var div;
    availUsers.empty();
    if(name.length > 2 || name.length == 0){
        $.ajax({
            url : '/messages/search',
            data : {
                name : name
            },
            success : function(response){
                for(var i = 0; i < response.id.length; i++){
                    var exist = $('#prepare_dialog')
                        .find('#'+response.id[i]+'prepared');
                    if(exist.length == 0){
                        div = $('<div></div>')
                            .addClass('result')
                            .attr('id', response.id[i]+'prepare')
                            .html(response.name[i]);
                        availUsers.append(div);
                    }
                }
                availUsers.slideDown(300);
            }
        })
    }
});

//new conversation
$('#avail_users').on('click', 'div.result', function(e){
    var id = parseInt(e.target.id)+'prepared';
    var someone = $('#'+ e.target.id);
    var users = $('#prepare_dialog');

    if(someone.hasClass('active')){
        someone.removeClass('active');
        $('#'+id).remove();
    } else {
        someone.addClass('active');
        var div = $('<div></div>')
            .addClass('prepared_user')
            .attr('id', id)
            .attr('title', 'Удалить')
            .html(someone.html());
        users.append(div);
    }
});

// remove user from selected area
$('#prepare_dialog').click(function(e){
    var elem = parseInt(e.target.id);
    $('#avail_users')
        .find('#'+elem+'prepare')
        .removeClass('active');
    $('#' + elem + 'prepared')
        .remove();
});

// cancel to find users
$('#cancel').click(function(){
    $('#find_users').hide();
});

// create new or open existing dialog
$('#confirm').click(function(){
    var dialogs = $('.prepare_dialog')
        .find('.prepared_user');
    var dialog_name = $('#dialog_name').val();
    var users = [];
    var chatWith = $('.href_container');
    var div;

    for(var i = 0; i < dialogs.length; i++){
        users.push(parseInt($(dialogs[i]).attr('id')));
    }
    if(users.length > 0){
        $.ajax({
            url : '/messages/newchat',
            data : {
                users : users,
                dialog_name : dialog_name
            },
            success : function(response){
                if(response.new){
                    window.lastDialog = response.new.id;
                    div = $('<div></div>')
                        .addClass('href active')
                        .attr('id', response.new.id)
                        .html(response.new.name);
                    chatWith.append(div);

                } else if (response.exist) {
                    window.lastDialog = response.exist.id;
                    var isHidden = $('#'+response.exist.id);
                    if(isHidden.length < 1){
                        div = $('<div></div>')
                            .addClass('href active')
                            .attr('id', response.exist.id)
                            .html(response.exist.name + '<span>&nbsp;X</span>');
                        chatWith.append(div);
                    }

                } else {
                    error('Неизвестная ошибка, пожалуйста, обратитесь к администратору');
                }
                update();
                $('#prepare_dialog').empty();
                $('#find_users').hide();
            }
        })
    } else {
        error('Пользователи не выбраны');
    }
});

// send message
function sendMessage(){
    var dialog = window.lastDialog;
    $.ajax({
        url : '/messages/newmessage/'+dialog,
        data : {
            message : $('textarea').val()
        },
        success : function(response){
            $('textarea').val('');
            //update();
        }
    })
}

// send message by enter
$('#enter_sent').click(function(){
    $(this).toggleClass('active');
});

$('#sent_message').click(function(){
    sendMessage();
});

//new message
$('textarea').keyup(function(e){

    var dialog = window.lastDialog;
    var length = $(this).val().length;
    var enterSent = $('#enter_sent').hasClass('active');

    if(e.which == 13 && enterSent && length > 1){
        $.ajax({
            url : '/messages/newmessage/'+dialog,
            data : {
                message : $(this).val()
            },
            success : function(response){
                $('textarea').val('');
                //update();
            }
        })
    }
});

//switch dialog
$('.href_container').on('click', 'div.href', function(e){
    var target = $(e.target);
    if(target.hasClass('href')){
        window.lastDialog = target.attr('id');
        $('#dialog_controls').css({
            "padding-top" : "40px"
        });
        update();
    }
});

//auto update
function update(){
    var dialog = window.lastDialog;
    $('.href_container .href').removeClass('active');
    $('#'+dialog).addClass('active');
    if(dialog){
        $.ajax({
            url : '/messages/update/'+dialog,
            data : {
                dialog : dialog
            },
            beforeSend: function() {
            },
            complete: function() {
            },
            success : function(response){
                if(!response.fail){
                    $.ajax({
                        url: '/messages/lastdialog',
                        success: function(response){
                            if(!response.empty){
                                $('#'+dialog).addClass('active');
                                $('#peoples').empty();
                                window.lastDialog = dialog;
                                fillUsersInDialog(response.data);
                            }
                        }
                    });

                    $('#all_messages')
                        .html(response);

                }
            }
        });

    }
}

// search input btn
$('#search_btn').click(function(){
    var mess = $('#find_users');
    var availUsers = $('#avail_users');
    var div;

    mess.toggle();
    availUsers.empty();
    if(mess.is(':visible')){
        $.ajax({
            url : '/messages/allusers',
            success : function(response){
                for(var i = 0; i < response.id.length; i++){
                    var exist = $('#prepare_dialog')
                        .find('#'+response.id[i]+'prepared');
                    //console.log(exist);
                    if(exist.length > 0){

                    } else if (exist.length == 0) {
                        div = $('<div></div>')
                            .addClass('result')
                            .attr('id', response.id[i]+'prepare')
                            .html(response.name[i]);
                        availUsers.append(div);
                    }

                }
                availUsers.slideDown(300);
            }
        })
    }
});

// select messages
$('#all_messages').click(function(e){
    $(e.target)
        .closest('.message')
        .toggleClass('active');
    checkControls();
});

function checkControls(){
    var checkedMessages = $('#all_messages .active');
    if(checkedMessages.length > 0){
        $('#dialog_controls').css({
            "padding-top" : 0
        })
    } else {
        $('#dialog_controls').css({
            "padding-top" : "40px"
        })
    }
}

// close dialog
$('.href span').click(function(e){
    var elem = $(e.target).closest('.href');
    var id = elem.attr('id');
    window.lastDialog = null;
    $.ajax({
        url : '/messages/hide/'+id,
        success : function(response){
            if(response.hidden == true){
                elem.remove();
                $('#all_messages').empty();
                $('#peoples').empty();
            }
        }
    })
});

//get selected messages
function getSelectedMessages(){
    var messages = $('#all_messages').find('.active');
    if(messages.length > 0){
        var ids = [];
        for(var i = 0; i < messages.length; i++){
            var id = (parseInt($(messages[i]).attr('id')));
            ids.push(id);
        }
        return ids;
    } else {
        return null;
    }
}

// remove messages from dialog
$('#remove').click(function(){
    var ids = getSelectedMessages();
    $.ajax({
        url : '/messages/removemessages',
        data : {
            "messages" : ids
        },
        success : function(response){
            if(response.removed == true){
                $('#dialog_controls').css({
                    "padding-top" : "40px"
                });
                update();
            }
        }
    });
});

// mark messages as important
$('#important').click(function(){
    var ids = getSelectedMessages();
    $.ajax({
        url : '/messages/important',
        data : {
            "messages" : ids
        },
        success : function(response){
            if(response.marked == true){
                $('#dialog_controls').css({
                    "padding-top" : "40px"
                });
                update();
            }
        }
    });
});

// remove user from dialog
$('#peoples').on('click', '.peoples_single', function(e){
    var user = parseInt($(e.target)
        .closest('.peoples_single')
        .attr('id'));
    var dialog = window.lastDialog;
    if(user && dialog){
        $.ajax({
            url : '/messages/removeuser/'+user+'/'+dialog,
            success : function(response){
                if(!response.error){
                    update();
                } else {
                    error(response.error);
                }
            }
        })
    }
});

// add user to dialog
$('#invite_user').click(function(){
    $(this).css({
        "height" : "300px",
        "border-radius" : "20px 0 0 0"
    });
    var peoples = $('#peoples');
    $.ajax({
        url : '/messages/addtodialog/'+window.lastDialog,
        success : function(response){

            alert(response.ok);

            //for(var i = 0; i < response.id.length; i++){
            //    var exist = $('#prepare_dialog')
            //        .find('#'+response.id[i]+'remove_user');
            //    if(!exist){
            //        var div = $('<div></div>')
            //            .addClass('result')
            //            .attr('id', response.id[i]+'remove_user')
            //            .html(response.name[i]);
            //        peoples.append(div);
            //    }
            //}
        }
    })
});

// error
function error(string){
    var error = $('<div></div>')
        .html(string);
    $('#errors')
        .append(error)
        .animate({
            "opacity" : 1
        }, 500, function() {
            $(error).animate({
                "opacity" : 0
            }, 5000, function() {
                error.remove();
            });
        });
}


//$('#all_messages').jScrollPane();
//$(function()
//{
//    $('#all_messages').jScrollPane();
//});

//var websocket = WS.connect("ws://cq9.ru:8888");
//websocket.on("socket/connect", function(session){
//    console.log('connected');
//    session.subscribe("topic/channel", function(uri, payload){
//        console.log("Received message", payload.msg);
//    });
//    session.publish("topic/channel", {msg: "This is a message!"});
//});
//websocket.on("socket/disconnect", function(error){
//    console.log("Disconnected for " + error.reason + " with code " + error.code);
//});











