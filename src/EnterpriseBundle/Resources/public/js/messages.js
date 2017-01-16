//// open last dialog
$(document).ready(function(){
    window.lastDialog = getLastDialog();
});

//// search users
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

// create new dialog
$('#confirm').click(function(){
    var dialogs = $('.prepare_dialog')
        .find('.prepared_user');
    var dialog_name = $('#dialog_name').val();
    var users = [];

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
                $('#content').empty().html(response);
            },
            error : function(response){
                error('Неизвестная ошибка!');
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
            $('#all_messages').html(response);
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
                'message' : $(this).val()
            },
            success : function(response){
                $('textarea').val('');
                $('#all_messages').html(response);
                if(window.form){
                    $.ajax({
                        url: '/messages/fileupload',
                        type: 'POST',
                        data: window.form,
                        processData: false,
                        contentType: false
                    });
                    window.form = '';
                }
            }
        })
    }
});

$('input[type="file"]').on("change", function (){
    window.form = '';
    var type = $(this).data('type');
    var file = this.files[0];
    var formdata = new FormData();
    var reader = new FileReader();

    reader.readAsDataURL(file);
    formdata.append("file", file);
    window.form = formdata;
    //$.ajax({
    //    url: '/messages/fileupload',
    //    type: 'POST',
    //    data: formdata,
    //    processData: false,
    //    contentType: false
    //});
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
                $('#content').empty().html(response);
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
    showControls();
});

function showControls(){
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

// remove dialog
$('.href span').click(function(e){
    var elem = $(e.target).closest('.href');
    var id = elem.attr('id');
    window.lastDialog = null;
    $.ajax({
        url : '/messages/hide/'+id,
        success : function(response){
            $('#content').html(response);
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
            $('#content').html(response);
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
            $('#content').html(response);
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
                $('#content').html(response);
            }
        })
    }
});

// add user to dialog
$('#invite_to_dialog').on('input', function(){

    var name = $(this).val();
    var availUsers = $('#invited_users');
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
                    div = $('<div></div>')
                        .attr('id', response.id[i]+'invited')
                        .html(response.name[i]);
                    availUsers.append(div);
                }
            }
        })
    }
});

$('#invite_user').click(function(){
    $('#new_user').show();
});

$('#invite_user').on('click', 'div.results', function(e){
    var dialog = window.lastDialog;
    var user = parseInt(e.target.id);
    if(dialog && user){
        $.ajax({
            url : '/messages/invite/'+user+'/'+dialog,
            success : function(response){
                $('#content').html(response);
            }
        })
    }
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



////var websocket = WS.connect("ws://cq9.ru:8888");
////websocket.on("socket/connect", function(session){
////    console.log('connected');
////    session.subscribe("topic/channel", function(uri, payload){
////        console.log("Received message", payload.msg);
////    });
////    session.publish("topic/channel", {msg: "This is a message!"});
////});
////websocket.on("socket/disconnect", function(error){
////    console.log("Disconnected for " + error.reason + " with code " + error.code);
////});

