/**
 * Created by DAN on 03.12.2016.
 */


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
    var id = window.id;
    if(window.form){
        $.ajax({
            url: '/documents/parse/'+id,
            type: 'POST',
            data: window.form,
            processData: false,
            contentType: false,
            success : function(response){
                alert("Загружено!");
            },
            fail : function(response){
                alert("Fucking fail!");
                //$('#content').empty().html(response);
            }
        });
    } else {alert('Файл не выбран!')}
});