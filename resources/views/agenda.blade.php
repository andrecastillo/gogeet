@extends('layouts.app')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3><span class="glyphicon glyphicon-dashboard"></span> Agenda </h3> <br>
                    </div>

                    <div class="panel-body">
                        Agenda will be like the old school agenda books where it would show you everything you had to do for a particular day. 
                        It'll take the events from the calendar, any tasks due that day (in the case of today it will take any tasks set for "today" (like in Asana) and any reminders for the day as well. See my notes in my notebook from Jan 5, 2021 for info.
<br><br>
Kind of like this:
<br><br>
Today:<br>
 - Event x<br>
 - Event y<br>
 - Task a<br>
 - Reminder h<br><br>
Tomorrow:<br>
 - Task r<br>
 - Task s<br>
 - Reminder p<br>
 - Event w<br>
 - Event v<br>
Next day:<br>
Next day, etc...<br>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
