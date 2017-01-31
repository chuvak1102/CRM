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

$('#save_static').click(function(e){

    e.preventDefault();
    var template = $('#static_url').val();
    var name = $('#static_name').val();
    var html = $('#static_html').val();
    if(template && name){
        $.ajax({
            url : '/admin/constructor/create-static',
            data : {
                "template" : template,
                "name" : name,
                "html" : html
            },
            type : "POST",
            success : function(response){
                if(!response.created){
                    $('#content')
                        .empty()
                        .html(response);
                }
            },
            error : function(){
                alert("Не удалось создать файл на сервере");
            }
        })
    } else {
        alert('Заполните URL и Название')
    }
});