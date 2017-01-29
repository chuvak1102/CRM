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
    var url = $('#static_url').val();
    var html = $('#static_html').val();
    console.log(html);
    if(url && html){
        $.ajax({
            url : '/admin/constructor/create-static',
            data : {
                "name" : url,
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
                alert("Хуй");
            }
        })
    }
});