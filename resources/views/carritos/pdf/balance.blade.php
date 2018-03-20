<!DOCTYPE html>
<html>
<head>
	<title>Balance correspondiente al período {{$date_1}} a {{$date_2}}</title>

	<style type="text/css">

		table{
			width: 100%;
		}
		td, th{
		}
	</style>
</head>
<body>
  <h2>Balance correspondiente al período {{$date_1}} a {{$date_2}}</h2>
  <hr></hr>
  <h3>Datos del balance</h3>
  <table>
    <tr>
      <td><strong>Ingresos totales netos:</strong></td>
      <td>$ {{round($ingreso_eventos_bruto - $iva_eventos)}}</td>
      <td><strong>Gastos totales netos:</strong></td>
      <td>$ {{round($valor_total_gastos_liquido)}}</td>
  	</tr>
    <tr>
      <td><strong>Iva total eventos:</strong></td>
      <td>$ {{round($iva_eventos)}}</td>
      <td><strong>Iva total gastos totales:</strong></td>
      <td>$ {{round($iva_total_gastos)}}</td>
  	</tr>
    <tr>
      <td><strong>Ingresos totales brutos:</strong></td>
      <td>$ {{round($ingreso_eventos_bruto)}}</td>
      <td><strong>Gastos totales brutos:</strong></td>
      <td>$ {{round($gastos_totales)}}</td>
  	</tr>
    <tr>
      <td>  </td>
      <td>  </td>
      <td><strong>Gasto en cocineros:</strong></td>
      <td>$ {{round($pagos_cocineros)}}</td>
  	</tr>
  </table>
  <hr size="3"></hr>
  <table>
    <tr>
      <td>Utilidad neta final: </td>
      <td>$ {{round($ingreso_eventos_bruto - $iva_eventos) - round($valor_total_gastos_liquido) - $pagos_cocineros}}</td>
    </tr>
    <tr>
      <td>Iva por pagar: </td>
      <td>$ {{round($iva_eventos - $iva_total_gastos)}}</td>
    </tr>
    <tr>
      <td></td>
    </tr>
  </table>
  <hr></hr>
  <h3>Gastos</h3>
  <table>
  	<tr>
      <th>Fecha</th>
      <th>Descripción</th>
      <th>Pagador</th>
      <th>Neto</th>
      <th>Iva</th>
      <th>Bruto</th>
  	</tr>
      @foreach($gastos as $gst)
      <tr>
        <td>{{$gst->fecha}}</td>
        <td>{{$gst->descripcion}}</td>
        <td>{{$gst->pagador}}</td>
        <td>$ {{$gst->valor_real}}</td>
        <td>$ {{$gst->iva}}</td>
        <td>$ {{$gst->monto_gasto}}</td>
      </tr>
      @endforeach
      <tr>
        <th> Total:</th>
        <th> </th>
        <th> </th>
        <th>$ {{round($valor_total_gastos_liquido)}}</th>
        <th>$ {{round($iva_total_gastos)}}</th>
        <th>$ {{round($gastos_totales)}}</th>
      </tr>
  </table>
  <hr></hr>
  <h3>Ingresos</h3>
  <table>
    <tr>
      <th>Fecha</th>
      <th>Descripción</th>
      <th>Cliente</th>
      <th>Neto</th>
      <th>Iva</th>
      <th>Bruto</th>
    </tr>
      @foreach($eventos as $eve)
      <tr>
        <td>{{$eve->fecha_hora}}</td>
        <td>{{$eve->descripcion}}</td>
        <td>{{$eve->nombre_cliente}}</td>
        <td>$ {{$eve->precio_evento - $eve->iva_por_pagar}}</td>
        <td>$ {{$eve->iva_por_pagar}}</td>
        <td>$ {{$eve->precio_evento}}</td>
      </tr>
      @endforeach
      <tr>
        <th> Total:</th>
        <th> </th>
        <th> </th>
        <th>$ {{round($ingreso_eventos_bruto - $iva_eventos)}}</th>
        <th>$ {{round($iva_eventos)}}</th>
        <th>$ {{round($ingreso_eventos_bruto)}}</th>
      </tr>
  </table>
</body>
