$('td.order_single').click(function(e){
    $(e.target)
        .parent()
        .parent()
        .parent()
        .parent()
        .find('.info')
        .slideToggle(300);
});

$('td.hover').click(function(e){
    $(e.target)
        .parent()
        .parent()
        .parent()
        .find('td.hover')
        .removeClass('selected');
    $(e.target)
        .addClass('selected');
});

$('.save_order').click(function(e){
    var orderBlock = $(e.target).parent().parent();
    var items = orderBlock.find('.analog td.selected');
    var orderId = orderBlock.find('.order_single').html();
    var productCount = orderBlock.find('.product_single');

    var order = [];
    for(var i = 0; i < items.length; i++){
        var single = [
        $(items[i]).parent().find('td.product_count').html(),
        $(items[i]).parent().find('td.vendor').html()
        ];
        order.push(single);
    }

    if(productCount.length == order.length){
        $.ajax({
            url : '/admin/order/create-order',
            data : {
                "orderId" : orderId,
                "order" : order
            },
            success : function(response){
                alert('Заказ успешно отправлен');
                $('#content').empty().html(response);
            },
            error : function(response){
                alert('Ошибка, ошибка!!!');
            }
        })
    } else {
        alert('не отмечены позиции по каждому товару!');
    }

    console.log(productCount);
});