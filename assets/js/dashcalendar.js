$(function(){

    
      var base_url=$('.baseurl').html(); // Here i define the base_url comes from a span in the body...inside tobar.php

    // Fullcalendar
    $('#calendar').fullCalendar({
        header: {
            left: 'prev, next',
            center: 'title',
             right: 'basicWeek, basicDay'
        },
        
        defaultView:'basicDay',
        // Get all events stored in database
        eventLimit: true, // allow "more" link when too many events
        events: base_url+'rosta/getEvents',
        selectable: true,
        selectHelper: true,
        editable: true// Make the event resizable true           
        
});

});
