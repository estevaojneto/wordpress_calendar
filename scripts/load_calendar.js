document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: [ 'dayGrid' ],
        defaultDate: new Date(document.getElementById('year').value, document.getElementById('monthnum').value, document.getElementById('day').value),
        header: {
            left:   'title',
            center: '',
            right:  ''
        }  
    });
    calendar.render();
    for(i = 0; i < parseInt(document.getElementById('qty_events').value); i++){
        startDate = document.getElementById('start_date'+String(i)).value;
        eventName = document.getElementById('event_name'+String(i)).value;
        console.log('start_date'.i);
        calendar.addEvent({
          title: eventName,
          start: new Date(startDate),
          allDay: true
        });
    }
});