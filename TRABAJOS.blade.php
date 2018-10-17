<div class="row">
  <div class="panel panel-primary">
    <div class="panel-body">
      <div class="col-lg-3 col-sm-3 col-md-12 col-xs-12">
        <div class="form-group">
          <label>Productos</label>
          <select name="" class="form-control selectpicker" id="" data-live-search="true">

              <option value=""></option>

          </select>
        </div>
      </div>
      <div class="col-lg-2 col-sm-2 col-md-12 col-xs-12">
        <div class="form-group">
          <label>Cantidad</label>
          <input type="number" name="cantidad_productos" id="cantidad_productos" class="form-control">
        </div>
      </div>
      <div class="col-lg-2 col-sm-2 col-md-12 col-xs-12">
        <div class="form-group">
          <label>Precio Neto Unidad</label>
          <input type="number" name="precio_neto_unidad_producto" id="precio_neto_unidad_producto" class="form-control" >
        </div>
      </div>
      <div class="col-lg-2 col-sm-2 col-md-12 col-xs-12">
        <div class="form-group">
          <label>Precio Bruto Unidad</label>
          <input  type="number" name="precio_bruto_unidad_producto" id="precio_bruto_unidad_producto" class="form-control">
        </div>
      </div>
      <div class="col-lg-2 col-sm-2 col-md-12 col-xs-12">
        <div class="form-group">
          <button type="button" onClick="" class="btn btn-primary">+</button>
        </div>
      </div>
      <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#A9D0F5">
            <th>Quitar</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>P Neto Unidad</th>
            <th>P Bruto Unidad</th>
            <th>Total Neto</th>
            <th>Total Bruto</th>
          </thead>
          <tfoot>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>
                <div class="input-group">
                  <span class="input-group-addon">$</span>
                  <input id="total_neto_productos" class="form-control" required readonly="readonly"></input>
                </div>
            </th>
            <th>
              <div class="input-group">
                <span class="input-group-addon">$</span>
                <input id="total_bruto_productos" class="form-control" required readonly="readonly"></input>
              </div></th>
            <th></th>
          </tfoot>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
