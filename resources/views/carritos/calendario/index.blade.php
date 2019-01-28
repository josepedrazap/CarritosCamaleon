@extends ('layouts.admin')
@section('contenido')

@section('style')
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
{!! $calendar->script() !!}
@endsection


<style>
  #calendar {
    max-width: 800px;
    margin: 0 auto;
    border: solid;
  }
</style>

<div class="container">
  <div class="row">
      <div class="col-md-8 col-md-offset-2">
          <div class="panel panel-default">
              <div class="panel-heading">{{$tit}}</div>
              <div class="panel-body">
                  {!! $calendar->calendar() !!}
                  {!! $calendar->script() !!}
              </div>
          </div>
      </div>
  </div>
</div>

@endsection
