
  <div>
    <div>
      <div>
        <table>

          <tr>
            <th>Número comprobante</th>
            <th>Fecha de ingreso</th>
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
            <th>{{$dat->numero_comprobante}}</th>
            <th>{{$dat->fecha_ingreso}}</th>
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
