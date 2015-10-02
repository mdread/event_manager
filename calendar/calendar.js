jQuery(document).ready(function($) {
  $('#calendar').fullCalendar({
    lang : $('#calendar').attr('data-lang'),
    events: {
        url: EventManager.url,
        type: 'POST',
        data: {
            action: EventManager.action
        },
        error: function() {
            alert('si è verificato un errore nel reperire gli eventi!');
        },
        color: '#D33',   // a non-ajax option
        textColor: 'white' // a non-ajax option
    }
  })
});
