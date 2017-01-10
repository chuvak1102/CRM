function months(month){
    return ['January', 'February', 'March', 'April',
        'May', 'June', 'July', 'August', 'September',
        'October', 'November', 'December'][month];
}

function prevMonth(someMonth, someYear){
    return new Date(someYear, someMonth);
}

function nextMonth(someMonth, someYear){
    return new Date(someYear, someMonth);
}

function prevLast(date){
    var m = new Date(date.getFullYear(), date.getMonth() - 1, '01');
    return new Date(m.getYear(), m.getMonth() + 1, 0).getDate() + 1;
}

function getDate(date){
    return months(date.getMonth()) + ', ' + date.getFullYear();
}

function weekDay(date){
    var d = new Date(date.getFullYear(), date.getMonth(), '01').getDay();
    return d == 0 ? d = 7 : d;
}

function daysInMonth(date){
    return new Date(date.getYear(), date.getMonth() + 1, 0).getDate();
}

function initCalendar(date){
    $('#month').html(getDate(date));
    var firstDay = weekDay(date);
    var lastDay = daysInMonth(date);
    var prevLastDay = prevLast(date) - firstDay;
    var loop = 0;
    var currDay = 0;
    var justNext = 0;
    var row = '<tr></tr>';
    var cell = '<td></td>';
    
    for(var i = 0; i < 6; i++){
        $('#calendar')
            .append($(row)
            .attr('id', 'week' + i));
        for(var j = 0; j < 7; j++){
            if(++loop < firstDay){
                $('#week' + i)
                    .append($(cell)
                    .html(++prevLastDay));
            } else if(loop >= firstDay){
                if(currDay < lastDay){
                    $('#week' + i)
                        .append($(cell)
                        .html(++currDay)
                        .addClass('active_day'));
                } else {
                    $('#week' + i)
                        .append($(cell)
                        .html(++justNext));
                }
            }
        }
    }
}

$(function(){
    var date = new Date();
    window.currentMonth = date.getMonth();
    window.currentYear = date.getFullYear();
    initCalendar(date);
});

$('#prevMonth').click(function(){
    $('#calendar').empty();
    window.currentMonth--;
    initCalendar(
        prevMonth(
            window.currentMonth,
            window.currentYear
        )
    );
});

$('#nextMonth').click(function(){
    $('#calendar').empty();
    window.currentMonth++;
    initCalendar(
        nextMonth(
            window.currentMonth,
            window.currentYear
        )
    );
});