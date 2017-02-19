
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
            url: '/admin/documents/parsecsv/'+id,
            type: 'POST',
            data: window.form,
            processData: false,
            contentType: false,
            success : function(response){
                window.form = '';
                $('#content').empty().html(response);
                alert("Продукты сохранены в базу");
            },
            error : function(response){
                alert(response.created);
            },
            fail : function(){
                alert("Не удалось распарсить файл");
            }
        });
    } else {alert('Файл не выбран')}
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
            error : function(response){
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
    var CategoryName = parseInt($('#CategoryName .field').html());
    var Price = parseInt($('#Price .field').html());
    var Description = parseInt($('#Description .field').html());
    var ShortDescription = parseInt($('#ShortDescription .field').html());
    var Image = parseInt($('#Image .field').html());
    var Prop = $('#Properties .field');
    var Properties = [];
    for(var i = 0; i < Prop.length; i++){
        var single = parseInt($(Prop[i]).html());
        Properties.push(single);
}
    $.ajax({
        url: '/admin/documents/save-settings',
        type: 'POST',
        data: {
            "id" : window.id,
            "VendorCode" : VendorCode,
            "Name" : Name,
            "Category" : Category,
            "CategoryName" : CategoryName,
            "Price" : Price,
            "Description" : Description,
            "ShortDescription" : ShortDescription,
            "Image" : Image,
            "Properties" : Properties
        },
        success : function(response) {
            alert('Настройки прайса сохранены');
            $('#content').empty().html(response);
        },
        error : function(){
            alert('Не удалось сохранить настройки, блядь')
        }
    });
});


$('#to_site').click(function(e){

    if(window.form){
        $.ajax({
            url: '/admin/documents/addtoshop',
            type: 'POST',
            data: window.form,
            processData: false,
            contentType: false,
            success : function(response){
                alert("Продукты сохранены в базу");
                $('#content').empty().html(response);
            },
            error : function(response){
                alert("Ошибка, ошибка!!");
            },
            fail : function(){
                alert("Не удалось распарсить файл");
            }
        });
    } else {alert('Файл не выбран')}
});

// for site catalog
$('#prepare').click(function(e){

    var fieldsArea = $(this).parent().find('.fields');

    if(window.form){
        $.ajax({
            url: '/admin/documents/catalogprepare',
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
            error : function(response){
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

// save site price

$('#save').click(function(){

    if(window.form){
        var VendorCode = parseInt($('#pVendorCode .field').html());
        var Name = parseInt($('#pName .field').html());
        var Category = parseInt($('#pCategory .field').html());
        var CategoryName = parseInt($('#pCategoryName .field').html());
        var Price = parseInt($('#pPrice .field').html());
        var Description = parseInt($('#pDescription .field').html());
        var ShortDescription = parseInt($('#pShortDescription .field').html());
        var Image = parseInt($('#pImage .field').html());
        var Prop = $('#pProperties .field');
        var Properties = [];
        for(var i = 0; i < Prop.length; i++){
            var single = parseInt($(Prop[i]).html());
            Properties.push(single);
        }
        $.ajax({
            url: '/admin/documents/save-settings',
            type: 'POST',
            data: {
                "id" : 1000,
                "VendorCode" : VendorCode,
                "Name" : Name,
                "Category" : Category,
                "CategoryName" : CategoryName,
                "Price" : Price,
                "Description" : Description,
                "ShortDescription" : ShortDescription,
                "Image" : Image,
                "Properties" : Properties
            },
            success : function(response) {
                $('#content').empty().html(response);
                alert('Настройки прайса сохранены');
            },
            error : function(){
                alert('Не удалось сохранить настройки, блядь')
            }
        });
    } else {
        alert('Файл не выбран!')
    }


});







