
  <div>
    <div>
      <div>
        <table>

          <tr>
            <th>Fecha</th>
            <th>Nombre</th>
            <th>Rut</th>
            <th>Tipo documento</th>
            <th>Numero documento</th>
            <th>Monto neto</th>
            <th>Retención</th>
            <th>Total</th>
          </tr>

          @foreach($data as $dat)
          <tr>
              <td>{{$dat->fecha_documento}}</td>
              <td>{{$dat->nombre}} {{$dat->apellido}}</td>
              <th>{{$dat->rut}}</th>
              <td>{{$dat->tipo_documento}}</td>
              <td>{{$dat->numero_documento}}</td>
              <td>$ {{$dat->monto_neto}}</td>
              <td>$ {{$dat->iva}}</td>
              <td>$ {{$dat->total}}</td>
          </tr>
          @endforeach
        </table>
      </div>
    </div>
  </div>
