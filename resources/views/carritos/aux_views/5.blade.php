
  <div>
    <div>
      <div>
        <table>

          <tr>
            <th>Fecha</th>
            <th>Monto</th>
            <th>Iva</th>
            <th>Monto final</th>
            <th>Pagador</th>
            <th>Descripción</th>
          </tr>

          @foreach($data as $dat)
          <tr>
            <td>{{$dat->fecha}}</td>
            <td>$ {{$dat->monto_neto}}</td>
            <td>$ {{$dat->iva}}</td>
            <td>$ {{$dat->total}}</td>
            <td>{{$dat->pagador}}</td>
            <td>{{$dat->descripcion}}</td>
          </tr>
          @endforeach
        </table>
      </div>
    </div>
  </div>
