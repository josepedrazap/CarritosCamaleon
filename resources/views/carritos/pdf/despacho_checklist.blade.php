<!DOCTYPE html>
<html>
<head>
	<title>Despacho evento número {{$evento[0]->id}} </title>
	<style type="text/css">

		table{
			width: 100%;
		}
		td, th{
		}
	</style>
</head>
<body>

<h2>Guía de producción evento número {{$evento[0]->id}}</h2>
<h3>Fecha de emisión: {{$fecha}}</h3>
<hr></hr>
<h3>Datos del evento</h3>
<table>
	<tr>
    <td><strong>Nombre cliente:</strong> {{$evento[0]->nombre}} {{$evento[0]->apellido}}</td>
    <td><strong>Dirección:</strong> {{$evento[0]->direccion  }}</td>
	</tr>
  <tr>
    <td><strong>Fecha y hora del evento:</strong> {{$evento[0]->fecha_hora}}</td>
	</tr>
  <tr>
    <td><strong>Teléfono:</strong> {{$evento[0]->contacto}}</td>
    <td><strong>Correo:</strong> {{$evento[0]->mail}}</td>
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
		<th>Ingredientes</th>
		<th>Cantidad sugerida</th>
		<th>Check</th>
	</tr>
	@foreach($ingredientes as $ingr)
	<tr>
		<td>{{$ingr->nombre}}</td>
		@if($ingr->unidad == "gramos" || $ingr->unidad == "unidad")
		@if($ingr->unidad == "gramos")
		<td>{{$ingr->sum / 1000}} kg</td>
		@endif
		@if($ingr->unidad == "unidad")
		<td>{{round($ingr->sum)}} unidades</td>
		@endif
		@else
		<td>{{$ingr->sum}} unidades</td>
		@endif
		<td>____</td>
	</tr>
	@endforeach
	@foreach($ingredientes_extras as $ingr)
	@if($ingr->aux == 0)
	<tr>
		<td>{{$ingr->nombre}}</td>
		@if($ingr->unidad == "gramos" || $ingr->unidad == "unidad")
		@if($ingr->unidad == "gramos")
		<td>{{$ingr->sum / 1000}} kg</td>
		@endif
		@if($ingr->unidad == "unidad")
		<td>{{round($ingr->cantidad * $ingr->porcion_unitaria)}} unidades</td>
		@endif
		@else
		<td>{{$ingr->cantidad * $ingr->porcion_unitaria}} unidades</td>
		@endif
			<td>____</td>
	</tr>
	@endif
	@endforeach
</table>
@if(count($extras) != 0 || count($otros) != 0)
<div>
	<hr></hr>
	<h3>Extras del evento</h3>
	<div>
		<table>
			<tr >
				<th>Extras</th>
				<th>Cantidad</th>
				<th>Check</th>
			</tr>
			@foreach($extras as $ext)
			<tr>
				<td>{{$ext->valor}}</td>
				<td>{{$ext->cantidad}}</td>
				<td>____</td>
			</tr>
			@endforeach
			@foreach($otros as $ot)
			<tr>
				<td>{{$ot->nombre}} (nuevo)</td>
				<td>{{$ot->cantidad}}</td>
				<td>____</td>
			</tr>
			@endforeach
		</table>
	</div>
</div>
@endif
</body>
</html>
