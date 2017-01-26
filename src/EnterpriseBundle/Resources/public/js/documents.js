
// write file from form to window
$('input[type="file"]').on("change", function (e){
    window.form = '';
    window.id = e.target.id;
    var type = $(this).data('type');
    var file = this.files[0];
    var formdata = new FormData();
    var reader = new FileReader();
    reader.readAsDataURL(file);
    formdata.append("file", file);
    window.form = formdata;
});

$('.parse').click(function(e){

    if(window.form && window.id){
        $.ajax({
            url: '/documents/parse/'+id,
            type: 'POST',
            data: window.form,
            processData: false,
            contentType: false,
            success : function(){
                alert("Fucking success!");
            },
            error : function(){
                alert('Fucking error!')
            },
            fail : function(){
                alert("Fucking fail!");
            }
        });
    } else {alert('Файл не выбран!')}
});

$('#prepare').click(function(e){

    if(window.form && window.id){
        $.ajax({
            url: '/documents/excelprepare',
            type: 'POST',
            data: window.form,
            processData: false,
            contentType: false,
            success : function(res){
                var fields = res.fields;
                console.log(fields);

                for(var i = 0; i < fields.length; i++){
                    var field = $('<div class="field"></div>')
                        .html(fields[i])
                        .draggable();
                    $('#fields')
                        .append(field);
                }

            },
            error : function(){
                alert('Fucking error!')
            },
            fail : function(){
                alert("Fucking fail!");
            }
        });
    } else {
        alert('Файл не выбран!')
    }
});

$( ".droppable" ).droppable({
    drop:function(event, ui) {
        ui.draggable
            .css({
                "position" : "static"
            })
            .appendTo($(this));
    }
});