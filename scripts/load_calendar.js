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
});