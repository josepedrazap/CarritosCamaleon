

@section('titulo','Inicio')
@section('css')
        <rel="stylesheet" type="text/css" href="{{asset('fullcalendar/fullcalendar.min.css')}}">
        <rel="stylesheet" type="text/css" href="{{asset('fullcalendar/fullcalendar.print.css')}}" media="print">
        <rel="stylesheet" href="{{asset('fullcalendar/lib/cupertino/jquery-ui.min.css')}}">
@endsection

@section('contenido')
<div class="row">
<div class="card">
<div class="col l7 offset-l1 m12 s12">
<div id="calendar"></div>
</div>
</div>
</div>
@endsection
@section('scripts')
    <script src="{{asset('fullcalendar/lib/jquery-ui.custom.min.js')}}"></script>
    <script src="{{asset('fullcalendar/lib/moment.min.js')}}"></script>
    <script src="{{asset('fullcalendar/fullcalendar.js')}}"></script>
    <script src="{{asset('fullcalendar/lang-all.js')}}"></script>
    <script>
        //inicializamos el calendario al cargar la pagina
        $(document).ready(function() {

            $('#calendar').fullCalendar({

                header: {
                    left: 'prev,next today myCustomButton',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },

            });

        });
    </script>
@endsection
