
  <div>
    <div>
      <div>
        <table>

          <tr>
            <th>Número evento</th>
            <th>Fecha documento</th>
            <th>Número documento</th>
            <th>Tipo documento</th>
            <th>Nombre tercero</th>
            <th>Rut tercero</th>
            <th>Monto neto</th>
            <th>Iva</th>
            <th>Total</th>
          </tr>

          @foreach($data as $dat)
          <tr>
            <td># {{$dat->id_eve}}</td>
            <td>{{$dat->fecha_documento}}</td>
            <td>{{$dat->numero_documento}}</td>
            <td>{{$dat->tipo_documento}}</td>
            <td>{{$dat->nombre}}</td>
            <td>{{$dat->rut}}</td>
            <th>{{$dat->monto_neto}}</th>
            <th>{{$dat->iva}}</th>
            <th>{{$dat->total}}</th>
          </tr>
          @endforeach
        </table>
      </div>
    </div>
  </div>
