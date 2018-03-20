@extends ('layouts.admin')
@section('contenido')
  <div class="row">
    <div class="col-lg-9 col-md-9 col-sm-6">
      <h3>Gastos actuales <a href="gastos/create"><button class="btn btn-success">Ingresar gasto</button></a></h3>
      @include('carritos.gastos.search2')
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <tr style="background-color:#ABEBC6">
            <th>Fecha</th>
            <th>Tipo</th>
            <th>Monto</th>
            <th>Iva</th>
            <th>Monto final</th>
            <th>Pagador</th>
            <th>Descripción</th>
          </tr>
          @foreach($data as $dat)
          <tr>
            <td>{{$dat->fecha}}</td>
            <td>{{$dat->tipo}}</td>
            <td>$ {{$dat->monto_gasto}}</td>
            <td>$ {{$dat->iva}}</td>
            <td>$ {{$dat->valor_real}}</td>
            @if($dat->pagador == 'Empresa')
            <th style="color:blue">{{$dat->pagador}}</th>
            @else
            <th style="color:red">{{$dat->pagador}}</th>
            @endif
            <td>
              <div class="btn-group">
                  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Descripción <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu">
                    <li><h5>{{$dat->descripcion}}</h5></li>
                  </ul>
              </div>
              @if($dat->pagador != 'Empresa')
              <a href="/carritos/gastos/pagador_empresa/{{$dat->id}}"><button class="btn btn-warning">Pagar</button></a>
              @endif
            </td>
          </tr>
          @include('carritos.gastos.modal')
          @endforeach
        </table>
      </div>
    </div>
  </div>
@endsection
