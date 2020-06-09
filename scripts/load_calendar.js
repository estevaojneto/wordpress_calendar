function getRandomColor() {
  var letters = '56789A';
  var color = '#';
  for (var i = 0; i < 6; i++) {
    color += letters[Math.floor(Math.random() * 6)];
  }
  return color;
}



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
    
    
    for(i = 0; i < parseInt(document.getElementById('qty_events').value); i++){
        startDates = JSON.parse(document.getElementById('start_dates'+String(i)+"[]").value);
		eventUrl = document.getElementById('event_url'+String(i)).value;
		eventName = document.getElementById('event_name'+String(i)).value;
		colorEvent = getRandomColor();
        for(var date in startDates){
            dateAdjusted = new Date(startDates[date]);
            dateAdjusted.setDate(dateAdjusted.getDate()+1);
            calendar.addEvent({
              title: eventName,
              start: dateAdjusted,
			  url: eventUrl,
			  color: colorEvent,
              allDay: true
            });
        }
    }
});
