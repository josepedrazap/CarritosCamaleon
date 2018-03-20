@extends ('layouts.admin')
@section('contenido')
  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8">
      <h3>Cuentas <a href="cuentas_contables/create"><button class="btn btn-success">Nueva</button></a></h3>

    </div>
  </div>

  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#ABEBC6">
            <th>Prefijo y nombre</th>
            <th>Tipo</th>

          </thead>

          @foreach($prefijos_2 as $pf_2)
          <tr>
              <td><strong>{{$pf_2->prefijo}} {{$pf_2->valor}}</strong></td>
              <td></td>

          </tr>
          @foreach($prefijos_1 as $pf_1)
          @if($pf_1->prefijo[0] == $pf_2->prefijo[0])
          <tr>
              <td>____<strong>{{$pf_1->prefijo}} {{$pf_1->valor}}</strong></td>
              <td></td>

          </tr>
          @foreach($prefijos_0 as $pf_0)
          @if($pf_1->prefijo[0] == $pf_0->prefijo[0] && $pf_1->prefijo[2] == $pf_0->prefijo[2])
          <tr>
              <td>________<strong>{{$pf_0->prefijo}} {{$pf_0->valor}}</strong></td>
              <td></td>

          </tr>
          @foreach($data as $dat)
          @if($pf_0->prefijo[0] == $dat->prefijo[0] && $pf_0->prefijo[2] == $dat->prefijo[2] && $pf_0->prefijo[4] == $dat->prefijo[4]  && $pf_0->prefijo[5] == $dat->prefijo[5])
          <tr>
            <td >____________<strong style="color:blue">{{$dat->prefijo}}{{$dat->num_prefijo_f}} {{$dat->nombre_cuenta}}</strong></td>
            <td>{{$dat->tipo}}</td>

          </tr>
          @endif
          @endforeach
          @endif
          @endforeach
          @endif
          @endforeach
          @endforeach

        </table>
      </div>
    </div>
  </div>
@endsection
