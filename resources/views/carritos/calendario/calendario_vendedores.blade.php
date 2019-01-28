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
eventos = <?php echo json_encode($data)?>;
clientes = <?php echo json_encode($clientes) ?>;

$('#calendar').fullCalendar({
  dayClick: function(date) {
    fecha = date.format();
    window.open('/simulador', '_blank');

    var url = '/axios/fecha?fecha=' + fecha;
    axios.get(url, {
    }).then(function (response) {
      $('#modal').iziModal('open');
    }).catch(function (error) {
      console.log(error);
    });
  },
  eventClick: function(calEvent, jsEvent, view) {

    id = calEvent.id;
    var url = '/axios/id?id=' + id;
    axios.get(url, {
    }).then(function (response) {
      $('#modal_2').iziModal('open');
    }).catch(function (error) {
      console.log(error);
    });

    // change the border color just for fun
    $(this).css('border-color', 'red');

  },
  header: { center: 'month,agendaWeek' }, // buttons for switching between views

  views: {
    month: { // name of view
      titleFormat: 'DD, MM, YYYY'
      // other view-specific options here
    }
  },
  events: eventos

});

$('#modal').iziModal({
  title: 'Agendar Cotización',
  headerColor: '#ABEBC6',
  iframe: true, //iframeを利用
  iframeWidth:400,
  iframeHeight: 300, //iframeの高さ
  iframeURL: '/carritos/cotizaciones_text' //iframe内に表示するurl
});

$('#modal_2').iziModal({
  title: 'Ver Cotización',
  headerColor: '#ABEBC6',
  iframe: true, //iframeを利用
  iframeWidth:400,
  iframeHeight: 300, //iframeの高さ
  iframeURL: '/carritos/cotizaciones_text_2' //iframe内に表示するurl
});

</script>
@endsection
<div id="calendar">
</div>

<style>
  #calendar {
    max-width: 900px;
    margin: 0 auto;
    border: solid;
  }
</style>

@endsection
