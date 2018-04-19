
  <div>
    <div>
      <div>
        <table>

          <tr>
            <th>Número documento</th>
            <th>Fecha de documento</th>
            <th>Tipo documento</th>
            <th>Rut proveedor</th>
            <th>Neto</th>
            <th>Iva</th>
            <th>Total</th>
          </tr>

          @foreach($data as $fac)
          <tr>
            <td># {{$fac->numero_documento}}</td>
            <td>{{$fac->fecha_documento}}</td>
            <td>{{$fac->tipo_documento}}</td>
            <td>{{$fac->rut}}</td>
            <td>$ {{$fac->monto_neto}}</td>
            <td>$ {{$fac->iva}}</td>
            <td>$ {{$fac->total}}</td>
          </tr>
          @endforeach
        </table>
      </div>
    </div>
  </div>
