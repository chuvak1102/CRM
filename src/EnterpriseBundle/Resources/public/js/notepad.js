var colors = [
    'rgb(227, 230, 235)',
    'rgba(179, 255, 141, 1)',
    'rgba(255, 253, 142, 1)',
    'rgba(255, 189, 146, 1)'
];

$(document).ready(function(){
    for(var i = 0; i < colors.length; i++){
        if(i == 0){
            var className = 'active'
        } else {
            className = '';
        }
        var div = $('<div class="color"></div>')
            .addClass(className)
            .attr('id', i)
            .css({
                "background-color" : colors[i]
            });
        $('#colors').append(div);
    }
    fillColors();
});

$('.colors').on('click', 'div.color', function(e){
    var color = e.target.id;
    var closest = $(e.target)
        .closest('.colors')
        .find('.color');
    closest.removeClass('active');
    $('#'+color).addClass('active');
});

// save new
$('#notepad_save').click(function(){
    var header = $('#notepad_input').val();
    var text = $('#notepad_textarea').val();
    var color = $('#add_form').find(' div.active').attr('id');
    if(header && text && color){
        $.ajax({
            url : '/notepad/add',
            data : {
                'header' : header,
                'text' : text,
                'color' : color
            },
            success : function(response){
                $('#content').empty().html(response);
            }
        })
    }
});

// create new
$( "#notepad_add" ).click(function(){
    var form = $( "#add_form" );
    if(form.is(':visible')){
        $( "#add_form" ).slideUp(300);
        $("#round").html('+');
        $("#caption").html('Add note')
    } else {
        $( "#add_form" ).slideDown(300);
        $("#round").html('-');
        $("#caption").html('Close')

    }
});





function fillColors(){
    var ids = getIds();
    var col = getColors();
    var notesId = ids.split(':');
    var color = col.split(':');

    for(var i = 0; i < notesId.length; i++){
        if(notesId[i]){
            $('#'+notesId[i]+'show').css({
                "background-color" : colors[color[i]]
            });
        }

        for(var j = 0; j < colors.length; j++){
            if(notesId[i] && color[i]){
                if(color[i] == j){
                    var className = 'active'
                } else {
                    className = '';
                }
                var div = $('<div class="color"></div>')
                    .addClass(className)
                    .attr('id', notesId[i]+'_'+j)
                    .css({
                        "background-color" : colors[j]
                    });
                $('#'+notesId[i]+'colors').append(div);
            }
        }
    }
}

$('.show').click(function(e){
    var a = $(e.target)
        .closest('.show')
        .attr('id');
    var id = parseInt(a);
    var form = $('#'+id+'add_form');
    if(form.is(':visible')){
        $('#'+id+'add_form').slideUp(300);
    } else {
        $('#'+id+'add_form').slideDown(300);
    }
});

$('.round').click(function(e){
    var id = parseInt(e.target.id);
    var header = $('#'+id+'notepad_input').val();
    var text = $('#'+id+'notepad_textarea').val();
    var color = $('#'+id+'colors')
        .find('.active')
        .attr('id');
    if(header && text && color){
        $.ajax({
            url : '/notepad/edit/'+id,
            data : {
                'header' : header,
                'text' : text,
                'color' : color
            },
            success : function(response){
                $('#content').empty().html(response);
            }
        })
    }

});

$('.close').click(function(e){
    e.preventDefault();
    var id = parseInt($(e.target).attr('id'));
    $('#'+id+'notepad_single').remove();
    $.ajax({
        url : '/notepad/close/'+id,
        success : function(response){
        }
    });
});