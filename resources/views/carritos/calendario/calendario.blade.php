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
$("#modal_").iziModal({
    title: 'Agendar cotización',
    subtitle: ':)',
    headerColor: '#ABEBC6',
    background: null,
    theme: '',  // light
    icon: null,
    iconText: null,
    iconColor: '',
    rtl: false,
    width: 600,
    top: null,
    bottom: null,
    borderBottom: true,
    padding: 0,
    radius: 3,
    zindex: 999,
    iframe: false,
    iframeHeight: 400,
    iframeURL: null,
    focusInput: true,
    group: '',
    loop: false,
    arrowKeys: true,
    navigateCaption: true,
    navigateArrows: true, // Boolean, 'closeToModal', 'closeScreenEdge'
    history: false,
    restoreDefaultContent: false,
    autoOpen: 0, // Boolean, Number
    bodyOverflow: false,
    fullscreen: false,
    openFullscreen: false,
    closeOnEscape: true,
    closeButton: true,
    appendTo: 'body', // or false
    appendToOverlay: 'body', // or false
    overlay: true,
    overlayClose: true,
    overlayColor: 'rgba(0, 0, 0, 0.4)',
    timeout: false,
    timeoutProgressbar: false,
    pauseOnHover: false,
    timeoutProgressbarColor: 'rgba(255,255,255,0.5)',
    transitionIn: 'comingIn',
    transitionOut: 'comingOut',
    transitionInOverlay: 'fadeIn',
    transitionOutOverlay: 'fadeOut',
    onFullscreen: function(){},
    onResize: function(){},
    onOpening: function(){},
    onOpened: function(){},
    onClosing: function(){},
    onClosed: function(){},
    afterRender: function(){}
});

eventos = <?php echo json_encode($data)?>;
clientes = <?php echo json_encode($clientes) ?>;

$('#calendar').fullCalendar({
  dayClick: function(date) {
    fecha = date.format();
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
  }
</style>

@endsection
