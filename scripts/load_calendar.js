document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: [ 'dayGrid' ],
        height: "parent",
        defaultDate: new Date(document.getElementById('year').value, 
                    document.getElementById('monthnum').value, 
                    document.getElementById('day').value),
        header: {
            left:   'title',
            center: '',
            right:  ''
        }  
    });
    calendar.render();
    
    for(i = 0; i < parseInt(document.getElementById('qty_simple_events').value); i++){
        startDate = new Date(document.getElementById('single_start_date'+String(i)).value);
        startDate.setDate(startDate.getDate()+1);
        eventName = document.getElementById('single_event_name'+String(i)).value;
        calendar.addEvent({
          title: eventName,
          start: startDate,
          allDay: true
        });
    }
    
    
    for(i = 0; i < parseInt(document.getElementById('qty_recurrent_events').value); i++){
        startDates = JSON.parse(document.getElementById('recurrent_start_dates'+String(i)+"[]").value);
        for(var date in startDates){
            eventName = document.getElementById('recurrent_event_name'+String(i)).value;
            dateAdjusted = new Date(startDates[date]);
            dateAdjusted.setDate(dateAdjusted.getDate()+1);
            calendar.addEvent({
              title: eventName,
              start: dateAdjusted,
              allDay: true
            });
        }
    }
});
