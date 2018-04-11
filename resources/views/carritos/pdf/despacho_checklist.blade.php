<!DOCTYPE html>
<html>
<head>
	<title>Despacho evento número {{$evento[0]->id}}</title>

	<style type="text/css">

		table{
			width: 100%;
		}
		td, th{
		}
	</style>
</head>
<body>

<h2>Despacho evento número {{$evento[0]->id}}</h2>
<hr></hr>
<h3>Datos del evento</h3>
<table>
	<tr>
    <td><strong>Nombre cliente:</strong> {{$evento[0]->nombre_cliente}}</td>
    <td><strong>Dirección:</strong> {{$evento[0]->direccion  }}</td>
	</tr>
  <tr>
    <td><strong>Fecha y hora del evento:</strong> {{$evento[0]->fecha_hora}}</td>
    <td><strong>Fecha y hora despacho:</strong> {{$evento[0]->fecha_despacho}}</td>
	</tr>
  <tr>
    <td><strong>Teléfono:</strong> {{$evento[0]->contacto}}</td>
    <td><strong>Correo:</strong> {{$evento[0]->email}}</td>
  </tr>
</table>
<p><strong>Descripción</strong>: {{$evento[0]->descripcion}}</p>
<hr></hr>
<h3>Productos</h3>
<table>
	<tr>
    <th>Producto</th>
    <th>Cantidad</th>
	</tr>
    @foreach($productos as $prod)
    <tr>
      <td>{{$prod->nombre}}</td>
      <td>{{$prod->cantidad}} unidades</td>
    </tr>
    @endforeach
</table>
<hr></hr>
<h3>Ingredientes</h3>
<table>
	<tr>
    <th>Ingrediente</th>
    <th>Cantidad sugerida</th>
    <th>Check</th>
	</tr>
    @foreach($ingredientes as $ingr)
    <tr>
      <td>{{$ingr->nombre}}</td>
      @if($ingr->unidad == "gramos" || $ingr->unidad == "unidad" || $ingr->unidad == "lámina")
      @if($ingr->unidad == "gramos")
      <td>{{round($ingr->sum / 1000, 1)}} kg</td>
      @endif
      @if($ingr->unidad == "unidad")
      <td>{{round($ingr->sum)}} unidades</td>
      @endif
      @if($ingr->unidad == "lámina")
      <td>{{round($ingr->sum)}} láminas</td>
      @endif
      @else
      <td>{{round($ingr->sum)}} unidades</td>
      @endif
      <td>_____</td>
    </tr>
    @endforeach

		<tr>
			<td colspan="3">------------------------------------------------Ingredientes extras-------------------------------------------------------</td>
		</tr>

		@foreach($ingr_extras as $ext)
		<tr>
			<td>{{$ext->nombre}}</td>
			@if($ext->uni_porcion == "gramos")
			<td>{{$ext->cantidad * $ext->porcion_/1000}} Kg</td>
			@else
			<td>{{$ext->cantidad * $ext->porcion_}} {{$ext->uni_porcion}}</td>
			@endif
			<td>_____</td>
		</tr>
		@endforeach
</table>
<hr></hr>
<h3>Extras</h3>
<table>
	<tr>
    <th>Extra</th>
    <th>Check</th>
	</tr>
  @foreach($extras as $ext)
  <tr>
    <td>{{$ext->valor}}</td>
    <td>_____</td>
  </tr>
  @endforeach
</table>

<hr></hr>
<h3>Utencilios</h3>
<table>
	<tr>
    <th>Utencilio</th>
    <th>Cantidad sugerida</th>
    <th>Check</th>
	</tr>
    @foreach($base as $bs)
    <tr>
      <td>{{$bs->base}}</td>
      <td>{{$bs->sum}} unidades</td>
      <td>_____</td>
    </tr>
    @endforeach
</table>
</body>

</html>
