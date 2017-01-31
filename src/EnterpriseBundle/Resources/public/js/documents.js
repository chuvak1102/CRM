
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
            url: '/admin/documents/parse/'+id,
            type: 'POST',
            data: window.form,
            processData: false,
            contentType: false,
            success : function(){
                alert("Продукты сохранены в базу");
            },
            error : function(){
                alert('Fucking error!')
            },
            fail : function(){
                alert("Я в рот ебал это программирование");
            }
        });
    } else {alert('Файл не выбран!')}
});

$('.prepare').click(function(e){

    var fieldsArea = $(this).parent().find('.fields');

    if(window.form && window.id){
        $.ajax({
            url: '/admin/documents/excelprepare',
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
                    fieldsArea.append(field);
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

$('.save_setting').click(function(){

    var VendorCode = parseInt($('#VendorCode .field').html());
    var Name = parseInt($('#Name .field').html());
    var Category = parseInt($('#Category .field').html());
    var Price = parseInt($('#Price .field').html());
    var Description = parseInt($('#Description .field').html());
    var shortDescription = parseInt($('#shortDescription .field').html());
    var Image = parseInt($('#Image .field').html());
    var Prop = $('#Properties .field');
    var Properties = [];
    for(var i = 0; i < Prop.length; i++){
        var single = parseInt($(Prop[i]).html());
        Properties.push(single);
}
    $.ajax({
        url: '/admin/documents/save-setting',
        type: 'POST',
        data: {
            "id" : window.id,
            "VendorCode" : VendorCode,
            "Name" : Name,
            "Category" : Category,
            "Price" : Price,
            "Description" : Description,
            "shortDescription" : shortDescription,
            "Image" : Image,
            "Properties" : Properties
        },
        success : function(res) {
            alert('Настройки прайса сохранены');
        },
        error : function(){
            alert('Не удалось сохранить настройки, блядь')
        }
    });
});