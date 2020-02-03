@extends('layouts.adminLTE.master')

@section('header')
<!-- iCheck -->
<link href="{!! asset('gentella/vendors/iCheck/skins/flat/green.css') !!}" rel="stylesheet">
@stop

@section('content')
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- fullCalendar 2.2.5-->
  <link rel="stylesheet" href="../plugins/fullcalendar/fullcalendar.min.css">
  <link rel="stylesheet" href="../plugins/fullcalendar/fullcalendar.print.css" media="print">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link rel="stylesheet" href="../../bower_components/select2/dist/css/select2.min.css">
<div class="box">
	<div class="box-body">
		<section>
			<div class="col-md-6 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						Earn
					</div>
					<div class="panel-body">
						<div class="form-group">
							<label>Cause</label>
							<input type="text" class="form-control" placeholder="input 1">
						</div>
            <div class="form-group">
                <label>From :</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="date" class="form-control pull-right" id="datepicker">
                </div>
                <!-- /.input group -->
              </div>
            <div class="form-group">
                <label>To :</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="date" class="form-control pull-right" id="datepicker">
                </div>
                <!-- /.input group -->
              </div>
						<div class="form-group">
							<button class="btn btn-success pull-right">Send</button>
						</div>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-heading">
						Deduction
					</div>
					<div class="panel-body">
						<div class="form-group">
							<label>Cause</label>
							<input type="text" class="form-control" placeholder="input 1">
						</div>
						
            <div class="form-group">
                <label>Date:</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="date" class="form-control pull-right" id="datepicker">
                </div>
                <!-- /.input group -->
              </div>

						<div class="form-group">
                <label>Select</label>
                <select class="form-control select2 select2-hidden-accessible" data-placeholder="Select a State" style="width: 100%;" tabindex="-1" aria-hidden="true">
                  <option>Alabama</option>
                  <option>Alaska</option>
                  <option>California</option>
                  <option>Delaware</option>
                  <option>Tennessee</option>
                  <option>Texas</option>
                  <option>Washington</option>
                </select>
              </div>
						<div class="form-group">
							<button class="btn btn-success pull-right">Send</button>
						</div>
					</div>
				</div>
			</div>
		</section>
    <!-- Main content -->
	    <section>
		<div class="col-md-6 col-xs-12" align="center">
          <!-- /.col -->
			<div class="panel panel-default">
				<div class="panel-heading">
					Deduction
				</div>
			<div class="panel-body">
			<!-- THE CALENDAR -->
				<div width="100%" height="300px" id="calendar">
				</div>
			</div>
			<!-- /.card-body -->
			<br>
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
							<h4 class="card-title">Draggable Events</h4>
							</div>
							<div class="card-body">
							<!-- the events -->
								<div id="external-events">
								<div class="external-event label-success">Lunch</div>
								<div class="external-event label-warning">Go home</div>
								<div class="external-event label-info">Do homework</div>
								<div class="external-event label-primary">Work on UI design</div>
								<div class="external-event label-danger">Sleep tight</div>
								<div class="checkbox">
								<label for="drop-remove">
								<input type="checkbox" id="drop-remove">
								remove after drop
								</label>
							</div>
						</div>
					</div>
					<!-- /.card-body -->
					</div>
				</div>
				<!-- /.col -->
				</div>
			<!-- /.row -->
			</div>
		</div>
	    </section>
	</div>
</div>
<!-- AdminLTE for demo purposes -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!-- Slimscroll -->
<script src="../plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>
<!-- fullCalendar 2.2.5 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
<script src="../plugins/fullcalendar/fullcalendar.min.js"></script>
<script>
  $(function () {

    /* initialize the external events
     -----------------------------------------------------------------*/
    function ini_events(ele) {
      ele.each(function () {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
          title: $.trim($(this).text()) // use the element's text as the event title
        }

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject)

        // make the event draggable using jQuery UI
        $(this).draggable({
          zIndex        : 1070,
          revert        : true, // will cause the event to go back to its
          revertDuration: 0  //  original position after the drag
        })

      })
    }

    ini_events($('#external-events div.external-event'))

    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date()
    var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear()
    $('#calendar').fullCalendar({
      header    : {
        left  : 'prev,next today',
        center: 'title',
        right : 'month,agendaWeek,agendaDay'
      },
      buttonText: {
        today: 'today',
        month: 'month',
        week : 'week',
        day  : 'day'
      },
      //Random default events
      events    : [
        {
          title          : 'All Day Event',
          start          : new Date(y, m, 1),
          end            : new Date(y, m, 3),
          backgroundColor: '#f56954', //red
          borderColor    : '#f56954' //red
        },
        {
          title          : 'Long Event',
          start          : new Date(y, m, d - 5),
          end            : new Date(y, m, d - 2),
          backgroundColor: '#f39c12', //yellow
          borderColor    : '#f39c12' //yellow
        },
        {
          title          : 'Meeting',
          start          : new Date(y, m, d, 10, 30),
          allDay         : false,
          backgroundColor: '#0073b7', //Blue
          borderColor    : '#0073b7' //Blue
        },
        {
          title          : 'Lunch',
          start          : new Date(y, m, d, 12, 0),
          end            : new Date(y, m, d, 14, 0),
          allDay         : false,
          backgroundColor: '#00c0ef', //Info (aqua)
          borderColor    : '#00c0ef' //Info (aqua)
        },
        {
          title          : 'Birthday Party',
          start          : new Date(y, m, d + 1, 19, 0),
          end            : new Date(y, m, d + 1, 22, 30),
          allDay         : false,
          backgroundColor: '#00a65a', //Success (green)
          borderColor    : '#00a65a' //Success (green)
        },
        {
          title          : 'Click for Google',
          start          : new Date(y, m, 28),
          end            : new Date(y, m, 29),
          url            : 'http://google.com/',
          backgroundColor: '#3c8dbc', //Primary (light-blue)
          borderColor    : '#3c8dbc' //Primary (light-blue)
        }
      ],
      editable  : true,
      droppable : true, // this allows things to be dropped onto the calendar !!!
      drop      : function (date, allDay) { // this function is called when something is dropped

        // retrieve the dropped element's stored Event Object
        var originalEventObject = $(this).data('eventObject')

        // we need to copy it, so that multiple events don't have a reference to the same object
        var copiedEventObject = $.extend({}, originalEventObject)

        // assign it the date that was reported
        copiedEventObject.start           = date
        copiedEventObject.allDay          = allDay
        copiedEventObject.backgroundColor = $(this).css('background-color')
        copiedEventObject.borderColor     = $(this).css('border-color')

        // render the event on the calendar
        // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
        $('#calendar').fullCalendar('renderEvent', copiedEventObject, true)

        // is the "remove after drop" checkbox checked?
        if ($('#drop-remove').is(':checked')) {
          // if so, remove the element from the "Draggable Events" list
          $(this).remove()
        }

      }
    })

    /* ADDING EVENTS */
    var currColor = '#3c8dbc' //Red by default
    //Color chooser button
    var colorChooser = $('#color-chooser-btn')
    $('#color-chooser > li > a').click(function (e) {
      e.preventDefault()
      //Save color
      currColor = $(this).css('color')
      //Add color effect to button
      $('#add-new-event').css({
        'background-color': currColor,
        'border-color'    : currColor
      })
    })
    $('#add-new-event').click(function (e) {
      e.preventDefault()
      //Get value and make sure it is not null
      var val = $('#new-event').val()
      if (val.length == 0) {
        return
      }

      //Create events
      var event = $('<div />')
      event.css({
        'background-color': currColor,
        'border-color'    : currColor,
        'color'           : '#fff'
      }).addClass('external-event')
      event.html(val)
      $('#external-events').prepend(event)

      //Add draggable funtionality
      ini_events(event)

      //Remove event from text input
      $('#new-event').val('')
    })
  })
</script>

@hasanyrole('masterowner','normalowner')
<div class="box">
	<div class="box-body">
		Hello, this is home!
	</div>
</div>
@endhasanyrole

@stop 

@section('footer')

<script type="text/javascript">
	$(document).ready(function() {

		setInterval(function(){
	        $('blink').each(function() {
	            $(this).toggle();
	        }); }, 
		800);

		setInterval(function(){
			//getEmergencyCount();
		},10000);

	});

	function getEmergencyCount(){

		//$('#emergencycount').empty();

		$.ajax({
            url: '{{ URL::to("resident/emergency/count") }}',
            type: 'get',
            dataType: 'json',
            success:function(data) {
            	$('#emergencycount').empty();
                if(data=='0'){
                	$('#emergencycount').append(data);
                }else{
                	$('#emergencycount').append('<blink>'+data+'</blink> Case(s)');
                }

            }
        });

	}
</script>
@stop