$(function(){

    var currentDate; // Holds the day clicked when adding a new event
    var currentEvent; // Holds the event object when editing an event

    $('#color').colorpicker(); // Colopicker
    

    //var base_url="http://hrattendance.clientspartners.com/"
    
      var base_url=$('.baseurl').html(); // Here i define the base_url comes from a span in the body...inside tobar.php

    // Fullcalendar
    $('#calendar').fullCalendar({
        /*defaultView:'agendaWeek',*/
        header: {
            left: 'prev, next, today',
            center: 'title',
             right: 'month, basicWeek, basicDay'
        },
        // Get all events stored in database
        eventLimit: true, // allow "more" link when too many events
        events: base_url+'rosta/getEvents',
        selectable: true,
        selectHelper: true,
        editable: true, // Make the event resizable true           
            select: function(start, end) {
                
                $('#start').val(moment(start).format('YYYY-MM-DD HH:mm:ss'));
                $('#end').val(moment(end).format('YYYY-MM-DD HH:mm:ss'));
                 // Open modal to add event
            modal({
                // Available buttons when adding
                buttons: {
                    add: {
                        id: 'add-event', // Buttons id
                        css: 'btn-success', // Buttons class
                        label: 'Add' // Buttons label
                    }
                },
                title: 'Add Schedule' // Modal title
            });
            }, 
           
         eventDrop: function(event, delta, revertFunc,start,end) {  
            
            start = event.start.format('YYYY-MM-DD');
            if(event.end){
                end = event.end.format('YYYY-MM-DD');
            }else{
                end = start;
            }         
                       
               $.post(base_url+'rosta/dragUpdateEvent',{                            
                id:event.id,
                start : start,
                end :end
            }, function(result){
             console.log(start+" "+end);
             
             $('#calendar').fullCalendar("refetchEvents");

            });



          },
          eventResize: function(event,dayDelta,minuteDelta,revertFunc) { 
                    
                start = event.start.format('YYYY-MM-DD');
            if(event.end){
                end = event.end.format('YYYY-MM-DD');
            }else{
                end = start;
            }         
                       
               $.post(base_url+'rosta/dragUpdateEvent',{                            
                id:event.id,
                start : start,
                end :end
            }, function(result){
               console.log(start+" "+end);
               
               $('#calendar').fullCalendar("refetchEvents");

            });
            },
          
        // Event Mouseover
        eventMouseover: function(calEvent, jsEvent, view){

            var tooltip = '<div class="event-tooltip">' + calEvent.duty + '</div>';
            $("body").append(tooltip);

            $(this).mouseover(function(e) {
                $(this).css('z-index', 10000);
                $('.event-tooltip').fadeIn('500');
                $('.event-tooltip').fadeTo('10', 1.9);
            }).mousemove(function(e) {
                $('.event-tooltip').css('top', e.pageY + 10);
                $('.event-tooltip').css('left', e.pageX + 20);
            });
        },
        eventMouseout: function(calEvent, jsEvent) {
            $(this).css('z-index', 8);
            $('.event-tooltip').remove();
        },
        // Handle Existing Event Click
        eventClick: function(calEvent, jsEvent, view) {
            // Set currentEvent variable according to the event clicked in the calendar
            currentEvent = calEvent;

            // Open modal to edit or delete event
            modal({
                // Available buttons when editing
                buttons: {
                    delete: {
                        id: 'delete-event',
                        css: 'btn-danger',
                        label: '<i class="glyphicon glyphicon-trash"> Remove</i>'
                    },
                    update: {
                        id: 'update-event',
                        css: 'btn-success',
                        label: '<i class="glyphicon glyphicon-refresh">  Update</i>'
                    }
                },
                title: 'Edit Schedule for <font color="#000"><b>' + calEvent.title +'</b> on '+(currentEvent.start).format('DD MMMM,YYYY')+'</font>',
                event: calEvent
            });
        }

    });

    // Prepares the modal window according to data passed
    function modal(data) {
        // Set modal title
        $('.modal-title').html(data.title);
        // Clear buttons except Cancel
        $('.modal-footer button:not(".btn-default")').remove();
        // Set input values
        $('#user').val(data.event ? data.event.person_id : '');        
        $('#duty').val(data.event ? data.event.schedule : '');
        $('#color').val(data.event ? data.event.color : '#3a87ad');
        // Create Butttons
        $.each(data.buttons, function(index, button){
            $('.modal-footer').prepend('<button type="button" id="' + button.id  + '" class="btn ' + button.css + '">' + button.label + '</button>')
        })
        //Show Modal
        $('.calendarmodal').modal('show');
    }

    // Handle Click on Add Button
    $('.modal').on('click', '#add-event',  function(e){
        if(validator(['user', 'duty'])) {

          

            $.post(base_url+'rosta/addEvent', {
                hpid: $('#user').val(),
                duty: $('#duty').val(),
                color: $('#color').val(),
                start: $('#start').val(),
                end: $('#end').val()
            }, function(result){
              

                 console.log(result);
                 
                $('.alert').addClass('alert-success').text('Schedule added successfuly');

                //$('.modal').modal('hide');
                $('#calendar').fullCalendar("refetchEvents");
                hide_notify();
            });
        }
    });


    // Handle click on Update Button
    $('.modal').on('click', '#update-event',  function(e){
        if(validator(['user', 'duty'])) {
           
           console.log(currentEvent.id);

            $.post(base_url+'rosta/updateEvent', {
                id: currentEvent.id,
                hpid: $('#user').val(),
                duty: $('#duty').val(),
                color: $('#color').val()
            }, function(result){
                console.log(result);
                $('.alert').addClass('alert-success').text('Schedule updated successfuly');
                $('.calendarmodal').modal('hide');
                $('#calendar').fullCalendar("refetchEvents");
                hide_notify();
                
            });
        }
    });



    // Handle Click on Delete Button
    $('.modal').on('click', '#delete-event',  function(e){
        console.log(currentEvent.id);
       
        $.get(base_url+'rosta/deleteEvent?id=' +currentEvent.id, function(result){

            $('.alert').addClass('alert-success').text('Schedule deleted successfully !');
            $('.calendarmodal').modal('hide');
            $('#calendar').fullCalendar("refetchEvents");
            hide_notify();
        });
    });

    function hide_notify()
    {
        setTimeout(function() {
                    $('.alert').removeClass('alert-success').text('');
                }, 3000);
    }


    // Dead Basic Validation For Inputs
    function validator(elements) {
        var errors = 0;
        $.each(elements, function(index, element){
            if($.trim($('#' + element).val()) == '') errors++;
        });
        if(errors) {
            $('.error').html('<center><b>Health worker and Schedule are required</b></center>');
            return false;
        }
        return true;
    }
});