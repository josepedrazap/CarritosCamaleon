@extends ('layouts.admin')
@section('contenido')
<div class="row">
  <div class="col-lg-8 col-md-8 col-sm-8">
    <h3>Libros Contables</h3>
    <hr></hr>
  </div>
</div>
<div class="row">
  <div class="col-lg-3 col-md-3 col-sm-12">
    <div class="list-group">
        <a href="/carritos/gastos?año=1" class="list-group-item list-group-item-info">
            Libro de Gastos
        </a>
    </div>
  <div class="list-group">
      <a href="/carritos/libros_contables/libro_diario" class="list-group-item list-group-item-info">
          Libro Diario
      </a>
  </div>
  <div class="list-group">
      <a href="/carritos/libros_contables/libro_mayor" class="list-group-item list-group-item-info">
          Libro Mayor
      </a>
  </div>
  <div class="list-group">
      <a href="/carritos/honorarios" class="list-group-item list-group-item-info">
          Libro de Honorarios
      </a>
  </div>
  <div class="list-group">
      <a href="/carritos/compras" class="list-group-item list-group-item-info">
          Libro de Compras
      </a>
      <a href="/carritos/libros_contables/libro_compras" class="list-group-item list-group-item-success">
          Libro de Compras Extendido
      </a>
  </div>
  <div class="list-group">
      <a href="/carritos/ingresos/mostrar_ingresos" class="list-group-item list-group-item-info">
          Libro de Ventas
      </a>
      <a href="/carritos/libros_contables/libro_ventas" class="list-group-item list-group-item-success">
          Libro de Ventas Extendido
      </a>
  </div>
</div>
</div>
@endsection
