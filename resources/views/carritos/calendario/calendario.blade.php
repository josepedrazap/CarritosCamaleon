@extends('layouts.admin')
@section('contenido')
@section('style')
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
<script>
function axios_verificar_num(){

}
var fecha = 0;
var id_ = 0;
var url_ = '/resumen_evento_calendario?id=';
eventos = <?php echo json_encode($data)?>;

function cambiar_url(i){
  console.log(url_)
  $('#modal_2').remove();
  $('#modal_2').iziModal({
    title: 'Ver Cotización',
    headerColor: '#ABEBC6',
    iframe: true, //iframeを利用
    iframeWidth:400,
    iframeHeight: 300, //iframeの高さ
    iframeURL: url_ + i //iframe内に表示するurl
  });
}

$('#calendar').fullCalendar({
  dayClick: function(date) {
    fecha = date.format();
    var url = '/axios/fecha?fecha=' + fecha;
    window.location.href = '/simulador?fecha=' + fecha + '&tipo_instruccion=3';
  },
  eventClick: function(calEvent, jsEvent, view) {
    cambiar_url(calEvent.id);
    $('#modal_2').iziModal('open');
    $(this).css('border-color', 'red');
  },
  header: { center: 'month,agendaWeek' },
  views: {
    month: {
      titleFormat: 'DD, MM, YYYY'
    }
  },
  events: eventos
});


</script>
@endsection
<div id="calendar">
</div>

<style>
  #calendar {
    max-width: 800px;
    margin: 0 auto;
    border: solid;
  }
</style>

@endsection
