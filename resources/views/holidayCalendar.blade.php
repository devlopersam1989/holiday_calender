<!DOCTYPE html>
<html lang='en'>
  <head>
    <meta charset='utf-8' />
    <style>
      div#country {
        margin-left: 22rem;
        margin-top: 1rem;
    }
    div#eventModal {
     max-width: 45%;
    }
    button#addNewEventBtn {
      margin-left: 22rem;
      margin-top: -4rem;
    }
    </style>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"/>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>
      $(document).ready(function() {
        $("#eventModal").modal('hide');
        $("#addNewEventBtn").click(function(){
          $("#eventName").val('');
          $("#eventDate").val('');
          $("#eventType").val('');
          $("#eventModal").modal('show');
          $("button#insertBtn").css('display','block');
          $("button#updateBtn").css('display','none');
          $("button#deleteBtn").css('display','none');
        });
        var countryValue = localStorage.getItem('iso_3166');
        $("#countryList").val(countryValue);
        countryHolidays();
      });
      function countryHolidays()
        {
          var iso_3166 = document.getElementById("countryList").value;
          var year = new Date().getFullYear();
          var events = new Array();
          localStorage.setItem('iso_3166', iso_3166);
            $.ajax({
              url: 'holidayCalendarList/'+iso_3166+'/'+year,  
              success: function(res) {
                console.log(res.length);
              if(typeof(res.length) != 'undefined')
              {
                $.each(res, function (i, item) {
                  events.push({
                    id: item.id,
                    title: item.name,
                    start: item.date,
                    extendedProps: {
                      type: item.type
                    },
                  });
                });
              } else{
                alert(res.message);
              }
              
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                  initialView: 'dayGridMonth',
                  events: events,
                  eventClick: function(info){
                    var startD = new Date(info.event.start);
                    var startDFormat = startD.toLocaleDateString("en-CA");
                    $("#eventModal").modal('show');
                    $("button#insertBtn").css('display','none');
                    $("button#updateBtn").css('display','block');
                    $("button#deleteBtn").css('display','block');
                    $("#eventId").val(info.event.id);
                    $("#eventName").val(info.event.title);
                    $("#eventDate").val(startDFormat);
                    $("#eventType").val(info.event.extendedProps.type);
                  }
                });
                calendar.render();
              },
              error:function(){
                console.log('Error');
                alert('Failed to load holiday calendar');
              }
            });
        };

        function insertEvent() // Add New Event
        { 
          var eventName = $("#eventName").val();
          var eventDate = $("#eventDate").val();
          var eventType = $("#eventType").val();
          var iso_3166 = document.getElementById("countryList").value;
          if(eventName != '' && eventDate != '' && eventType != '')
          {
            $.ajax({
              url:'/insertEvent/'+eventName+'/'+eventDate+'/'+eventType+'/'+iso_3166,
              success: function(res){
                $('#eventModal').modal('hide');
                alert(res.message);
                location.reload();
              }, error: function(){
                alert('Failed to insert event');
              }
            });
          } else {
            alert('All fields required'); 
            return false;
          }
        }
        function updateEvent() // Update Event
        {
          var eventId = $('#eventId').val();
          var eventName = $("#eventName").val();
          var eventDate = $("#eventDate").val();
          var eventType = $("#eventType").val();
          if(eventName != '' && eventDate != '' && eventType != '')
          {
            $.ajax({
              url:'/updateEvent/'+eventId+'/'+eventName+'/'+eventDate+'/'+eventType,
              success: function(res){
                $('#eventModal').modal('hide');
                alert(res.message);
                location.reload();
              }, error: function(){
                alert('Failed to update event');
              }
            });
          } else {
            alert('All fields required'); 
            return false;
          }
        }
        function deleteEvent() //Delete Event
        {
          var eventId = $('#eventId').val();
          if(confirm("Are you sure you want to Delete"))
          {
            $.ajax({
              url:'/deleteEvent/'+eventId,
              success: function(res){
                $('#eventModal').modal('hide');
                alert(res.message);
                location.reload();
              }, error: function(){
                alert('Failed to delete event');
              }
            });
          } else {
            return false;
          }
        }
    </script>
  </head>
  <body>
    <div>
      <h6><strong>Assignment Option 1:</strong> Holiday Calendar</h6>
    </div>
    <div id="country" onchange="countryHolidays()">
      <div class="countryCls">
        <label>Country: </label>
        <select id="countryList">
          @foreach($country as $value)
            <option id="{{ $value->id }}" value="{{ $value->iso_3166 }}">{{ $value->country_name }}</option>
          @endforeach
        </select>
      </div>
      <div class="addNewEventCls">
        <button type="button" class="btn btn-primary" id="addNewEventBtn">Add New Event</button>
      </div>
     
    </div>
    <div id='calendar'></div>
    <div class="modal" tabindex="-1" role="dialog" id="eventModal">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Event Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <div class="form-group">
                <label class="control-label col-sm-2" for="name">Name:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="eventId" name="eventId" hidden>
                  <input type="text" class="form-control" id="eventName" name="eventName">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-2" for="date">Date:</label>
                <div class="col-sm-10">
                  <input type="date" class="form-control" id="eventDate" name="eventDate">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-2" for="type">Type:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="eventType" name="eventType">
                </div>
              </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" id="insertBtn" onclick="insertEvent()">Add</button>
              <button type="button" class="btn btn-primary" id="updateBtn" onclick="updateEvent()">Update</button>
              <button type="button" class="btn btn-secondary" id="deleteBtn" onclick="deleteEvent()">Delete</button>
            </div>
          </div>
      </div>
    </div>

  </body>
</html>