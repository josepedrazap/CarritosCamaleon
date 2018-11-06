<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>ERP Carritos Camaleon | www.erpcamaleon.cl Simulador</title>
  <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
  <script src="{{asset('js/jQuery-2.1.4.min.js')}}"></script>
  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

  <script>

      //variables de totales ventas
      var total_productos_neto_venta = 0;
      var total_extras_neto_venta = 0;
      var total_nuevos_neto_venta = 0;
      var total_ingrs_neto_venta = 0;

      //variables de costo
      var costo_extras_neto = 0;
      var costo_nuevos_neto = 0;
      var costo_ingredientes = 0;
      var costo_ingrs = 0;
      var costo_cocineros = 0;

      //variables de totales
      var total_neto = 0;
      var iva = 0.19;
      var impuesto_renta = 0.10;

      //variables de control contadores.
      var cont_prods = 0;
      var cont_extras = 0;
      var cont_ingrs = 0;
      var cont_nuevos = 0;

      //variables show_hide
      var productos_div = 0;
      var extras_div = 0;
      var ingrs_extra_div = 0;
      var nuevos_div = 0;
      var cocineros_div = 0;
      //la variable control debe recibir add para gregar o less para quitar.
      function get_ingrs_prods(id, cantidad, index){
        console.log('hola')
        axios.get('/costo_bruto_ingredientes_producto?id=' + id + '&cantidad=' + cantidad)
            .then(function (response) {
              costo_ingredientes += parseInt(response.data / 1.19);
              document.getElementById('costo_neto_prod_' + index).value = parseInt(response.data / 1.19);
              document.getElementById('costo_bruto_prod_' + index).value = parseFloat(response.data);
              totales();
              console.log(response);
            })
            .catch(function (error) {
              // handle error
              console.log(error);
            })
            .then(function () {
              // always executed
            });
      }
      function adm_productos(control, index){

          if(control == 5){
            alert('mostrar_tabla_ingredientes');
          }
          if(control == 4){
            if(productos_div == 0){
                $("#productos_div").show();
                productos_div = 1;
                $("#btn_prod_div").addClass("btn-danger");
                $("#btn_prod_div").removeClass("btn-warning");
            }else{
                $("#productos_div").hide();
                productos_div = 0;
                $("#btn_prod_div").addClass("btn-warning");
                $("#btn_prod_div").removeClass("btn-danger");
            }
            return;
          }

          id = $("#id_producto option:selected").val();
          producto = $("#id_producto option:selected").text();
          precio_neto = $("#precio_neto_unidad_producto").val();
          cantidad = $("#cantidad_productos").val();

          if(control == 0){

            sv = '#precio_neto_unidad_producto_' + index;
            sv2 = '#cantidad_producto_' + index;
            sv3 = '#costo_neto_prod_' + index;
            if($(sv).val() != ''){
              total_productos_neto_venta = total_productos_neto_venta - parseFloat($(sv).val() * $(sv2).val());
              costo_ingredientes = costo_ingredientes - parseFloat($(sv3).val());
            }
            document.getElementById('total_neto_productos').value = parseFloat(total_productos_neto_venta);
            document.getElementById('total_bruto_productos').value = parseFloat(conversor_neto_a_bruto(total_productos_neto_venta));

              sv = "#fila_productos_" + index;
              $(sv).remove();
              cont_prods--;
          }
          if(control == 1){
            var productos = <?php echo json_encode($productos);?>;
            for(var i = 0; i < productos.length; i++){
                if(productos[i].id == index){
                  document.getElementById('precio_neto_unidad_producto').value = productos[i].precio;
                }
            }
          }
          if(control == 2){

              if(producto != "" && precio_neto != "" && cantidad != ""){
                var bruto = conversor_neto_a_bruto(precio_neto);
                var fila = '<tr class="selected" id="fila_productos_' + cont_prods + '"><td><button type="button" class="btn btn-warning" onClick="adm_productos(0, '+ cont_prods +')">X</button></td><td><input hidden name="id_producto[]" value="' + id + '"> <input class="form-control" readonly value="' + producto + '"></td><td><input class="form-control" type="number" id="cantidad_producto_'+cont_prods+'" name="cant_prods_[]" readonly="readonly" value="'+cantidad+'"></td><td><input class="form-control" readonly="readonly" id="costo_neto_prod_' + cont_prods + '"/></td><td><input class="form-control" readonly="readonly" id="costo_bruto_prod_' + cont_prods + '"/></td><td><input class="form-control" type="number" id="precio_neto_unidad_producto_'+ cont_prods + '" readonly="readonly" name="precio_neto_unidad_producto[]" value="' + precio_neto + '"></td><td><input class="form-control" name="precio_bruto_unidad_producto[]" value="'+ bruto +'"></td><td><input class="form-control" name="total_neto_producto[]" value="'+ cantidad * precio_neto +'"></td><td><input class="form-control" name="total_bruto_producto[]" value="'+ cantidad * bruto +'"></td></tr>';

                $("#detalles_producto").append(fila);

                total_productos_neto_venta += precio_neto * cantidad;
                document.getElementById('total_neto_productos').value = parseFloat(total_productos_neto_venta);
                document.getElementById('total_bruto_productos').value = parseFloat(conversor_neto_a_bruto(total_productos_neto_venta));

                document.getElementById('precio_neto_unidad_producto').value = "";
                document.getElementById('cantidad_productos').value = "";

                get_ingrs_prods(id, cantidad, cont_prods);

                cont_prods++;

              }else{
                alert("Faltan campos por rellenar");
              }
          }
          totales();
      }
      function adm_extras(control, index){

        if(control == 4){
          if(extras_div == 0){
              $("#extras_div").show();
              extras_div = 1;
              $("#btn_extras_div").addClass("btn-danger");
              $("#btn_extras_div").removeClass("btn-warning");
          }else{
              $("#extras_div").hide();
              extras_div = 0;
              $("#btn_extras_div").addClass("btn-warning");
              $("#btn_extras_div").removeClass("btn-danger");
          }
          return;
        }

        id = $("#id_extra option:selected").val();
        extra = $("#id_extra option:selected").text();
        costo_neto = $("#costo_neto_empresa_extra").val();
        precio_neto = $("#precio_neto_venta_extra").val();
        cantidad = $("#cantidad_extra").val();

        if(control == 0){
          sv = '#precio_neto_unidad_extra_' + index;
          sv3 = '#costo_neto_extra_' + index;
          sv2 = '#cantidad_extra_' + index;
          if($(sv).val() != ''){
            total_extras_neto_venta = total_extras_neto_venta - parseFloat($(sv).val() * $(sv2).val());
            costo_extras_neto = costo_extras_neto - parseFloat($(sv3).val() * $(sv2).val());
          }
          document.getElementById('costo_total_neto_extras').value = parseFloat(costo_extras_neto);
          document.getElementById('costo_total_bruto_extras').value = parseFloat(conversor_neto_a_bruto(costo_extras_neto));
          document.getElementById('precio_total_neto_extras').value = parseFloat(total_extras_neto_venta);
          document.getElementById('precio_total_bruto_extras').value = parseFloat(conversor_neto_a_bruto(total_extras_neto_venta));

            sv = "#fila_extras_" + index;
            $(sv).remove();
            cont_extras--;
        }

        if(control == 2){

            if(extra != "" && precio_neto != "" && costo_neto != "" && cantidad != ""){
              var bruto = conversor_neto_a_bruto(precio_neto);
              var costo_bruto = conversor_neto_a_bruto(costo_neto);

              var fila = '<tr class="selected" id="fila_extras_' + cont_extras + '"><td><button type="button" class="btn btn-warning" onClick="adm_extras(0, '+ cont_extras +')">X</button></td><td><input hidden name="id_extra[]" value="' + id + '"> <input class="form-control" readonly value="' + extra + '"></td><td><input class="form-control" type="number" id="cantidad_extra_'+cont_extras+'" name="cant_extras_[]" readonly="readonly" value="'+cantidad+'"></td><td><input name="costo_neto_extra_'+ cont_extras + '" id="costo_neto_extra_'+ cont_extras + '" class="form-control" readonly="readonly" value="' + costo_neto + '"></td><td><input name="costo_bruto_extra_'+ cont_extras + '" id="costo_bruto_extra_'+ cont_extras + '" class="form-control" readonly="readonly" value="' + costo_bruto + '"></td><td><input class="form-control" type="number" id="precio_neto_unidad_extra_'+ cont_extras + '" readonly="readonly" name="precio_neto_unidad_extra[]" value="' + precio_neto + '"></td><td><input class="form-control" name="precio_bruto_unidad_extra[]" value="'+ bruto +'"></td><td><input class="form-control" name="costo_total_neto_extra[]" value="'+ cantidad * costo_neto +'"></td><td><input class="form-control" name="costo_total_bruto_extra[]" value="'+ cantidad * costo_bruto +'"></td><td><input class="form-control" name="precio_total_neto_extra[]" value="'+ cantidad * precio_neto +'"></td><td><input class="form-control" name="precio_total_bruto_extra[]" value="'+ cantidad * bruto +'"></td></tr>';

              $("#detalles_extra").append(fila);

              total_extras_neto_venta += precio_neto * cantidad;
              costo_extras_neto += costo_neto * cantidad;
              cont_extras++;

              document.getElementById('costo_total_neto_extras').value = parseFloat(costo_extras_neto);
              document.getElementById('costo_total_bruto_extras').value = parseFloat(conversor_neto_a_bruto(costo_extras_neto));
              document.getElementById('precio_total_neto_extras').value = parseFloat(total_extras_neto_venta);
              document.getElementById('precio_total_bruto_extras').value = parseFloat(conversor_neto_a_bruto(total_extras_neto_venta));

              document.getElementById('cantidad_extra').value = "";
              document.getElementById('costo_neto_empresa_extra').value = "";
              document.getElementById('precio_neto_venta_extra').value = "";

            }else{
              alert("Faltan campos por rellenar");
            }
        }
        totales();
      }
      function adm_ingrs(control, index){

        if(control == 4){
          if(ingrs_extra_div == 0){
              $("#ingrs_extra_div").show();
              ingrs_extra_div = 1;
              $("#btn_ingrs_div").addClass("btn-danger");
              $("#btn_ingrs_div").removeClass("btn-warning");
          }else{
              $("#ingrs_extra_div").hide();
              ingrs_extra_div = 0;
              $("#btn_ingrs_div").addClass("btn-warning");
              $("#btn_ingrs_div").removeClass("btn-danger");
          }
          return;
        }

        id = $("#id_ingr option:selected").val();
        ingr = $("#id_ingr option:selected").text();
        costo_neto = $("#costo_neto_ingr").val();
        precio_neto = $("#precio_neto_venta_ingr").val();
        cantidad = $("#cantidad_ingr").val();
        unidad = $("#unidad_ingr").val();

        if(control == 0){
          sv = '#precio_neto_unidad_ingr_' + index;
          sv3 = '#costo_neto_ingr_' + index;
          sv2 = '#cantidad_ingr_' + index;
          if($(sv).val() != ''){
            total_ingrs_neto_venta = total_ingrs_neto_venta - parseFloat($(sv).val() * $(sv2).val());
            costo_ingrs = costo_ingrs - parseFloat($(sv3).val() * $(sv2).val());
          }
          document.getElementById('costo_total_neto_ingrs').value = parseFloat(costo_ingrs);
          document.getElementById('costo_total_bruto_ingrs').value = parseFloat(conversor_neto_a_bruto(costo_ingrs));
          document.getElementById('precio_total_neto_ingrs').value = parseFloat(total_ingrs_neto_venta);
          document.getElementById('precio_total_bruto_ingrs').value = parseFloat(conversor_neto_a_bruto(total_ingrs_neto_venta));

            sv = "#fila_extras_" + index;
            $(sv).remove();
            cont_ingrs--;
        }
        if(control == 1){
          var ingrs = <?php echo json_encode($ingredientes);?>;
          for(var i = 0; i < ingrs.length; i++){
              if(ingrs[i].id == index){
                console.log(ingrs)
                document.getElementById('unidad_ingr').value = ingrs[i].unidad;
                document.getElementById('costo_neto_ingr').value = ingrs[i].precio_liquido;
              }
          }
        }
        if(control == 2){

            if(ingr != "" && precio_neto != "" && costo_neto != "" && cantidad != "" && unidad != ""){
              var bruto = conversor_neto_a_bruto(precio_neto);
              var costo_bruto = conversor_neto_a_bruto(costo_neto);

              var fila = '<tr class="selected" id="fila_ingrs_' + cont_ingrs + '"><td><button type="button" class="btn btn-warning" onClick="adm_ingrs(0, '+ cont_ingrs +')">X</button></td> <td><input hidden name="id_ingr[]" value="' + id + '"> <input class="form-control" readonly value="' + ingr + '"></td><td><input class="form-control" type="number" id="cantidad_ingr_'+cont_ingrs+'" name="cant_ingrs_[]" readonly="readonly" value="'+cantidad+'"></td><td><input class="form-control" name="unidad_ingr_[]" id="unidad_ingr_' + cont_ingrs +'" readonly="readonly" value="'+ unidad +'"/></td><td><input class="form-control" name="costo_neto_ingr_'+ cont_ingrs + '" id="costo_neto_ingr_'+ cont_ingrs + '" readonly="readonly" value="' + costo_neto + '"></td><td><input class="form-control" name="costo_bruto_ingr_'+ cont_ingrs + '" id="costo_bruto_ingr_'+ cont_ingrs + '" readonly="readonly" value="' + costo_bruto + '"></td><td><input class="form-control" name="precio_neto_unidad_ingr[]" id="precio_neto_unidad_ingr_'+ cont_ingrs + '" readonly="readonly"  value="' + precio_neto + '"></td><td><input class="form-control" name="precio_bruto_unidad_ingr[]" value="'+ bruto +'" readonly="readonly"></td><td><input class="form-control" name="costo_total_neto_ingr[]" value="'+ cantidad * costo_neto +'" readonly="readonly"></td><td><input class="form-control" name="costo_total_bruto_ingr[]" value="'+ cantidad * costo_bruto +'" readonly="readonly"></td><td><input class="form-control" name="precio_total_neto_ingr[]" value="'+ cantidad * precio_neto +'" readonly="readonly"></td><td><input class="form-control" name="precio_total_bruto_ingr[]" value="'+ cantidad * bruto +'" readonly="readonly"></td></tr>';

              $("#detalles_ingrs").append(fila);

              total_ingrs_neto_venta += precio_neto * cantidad;
              costo_ingrs += costo_neto * cantidad;
              cont_ingrs++;
              document.getElementById('costo_total_neto_ingrs').value = parseFloat(costo_ingrs);
              document.getElementById('costo_total_bruto_ingrs').value = parseFloat(conversor_neto_a_bruto(costo_ingrs));
              document.getElementById('precio_total_neto_ingrs').value = parseFloat(total_ingrs_neto_venta);
              document.getElementById('precio_total_bruto_ingrs').value = parseFloat(conversor_neto_a_bruto(total_ingrs_neto_venta));

              document.getElementById('cantidad_extra').value = "";
              document.getElementById('precio_neto_venta_ingr').value = "";

            }else{
              alert("Faltan campos por rellenar");
            }
        }
        totales();
      }
      function adm_nuevos(control, index){

        if(control == 4){
          if(nuevos_div == 0){
              $("#nuevos_div").show();
              nuevos_div = 1;
              $("#btn_nuevos_div").addClass("btn-danger");
              $("#btn_nuevos_div").removeClass("btn-warning");
          }else{
              $("#nuevos_div").hide();
              nuevos_div = 0;
              $("#btn_nuevos_div").addClass("btn-warning");
              $("#btn_nuevos_div").removeClass("btn-danger");
          }
          return;
        }

        nuevo = $("#nombre_nuevo_item").val();
        costo_neto = $("#costo_neto_empresa_nuevo").val();
        precio_neto = $("#precio_neto_venta_nuevo").val();
        cantidad = $("#cantidad_nuevo").val();

        if(control == 0){
          sv = '#precio_neto_unidad_nuevo_' + index;
          sv3 = '#costo_neto_nuevo_' + index;
          sv2 = '#cantidad_nuevo_' + index;
          if($(sv).val() != ''){
            total_nuevos_neto_venta = total_nuevos_neto_venta - parseFloat($(sv).val() * $(sv2).val());
            costo_nuevos_neto = costo_nuevos_neto - parseFloat($(sv3).val() * $(sv2).val());
          }
          document.getElementById('costo_total_neto_nuevos').value = parseFloat(costo_nuevos_neto);
          document.getElementById('costo_total_bruto_nuevos').value = parseFloat(conversor_neto_a_bruto(costo_nuevos_neto));
          document.getElementById('precio_total_neto_nuevos').value = parseFloat(total_nuevos_neto_venta);
          document.getElementById('precio_total_bruto_nuevos').value = parseFloat(conversor_neto_a_bruto(total_nuevos_neto_venta));

            sv = "#fila_nuevos_" + index;
            $(sv).remove();
            cont_nuevos--;
        }

        if(control == 2){

            if(nuevo != "" && precio_neto != "" && costo_neto != "" && cantidad != ""){
              var bruto = conversor_neto_a_bruto(precio_neto);
              var costo_bruto = conversor_neto_a_bruto(costo_neto);

              var fila = '<tr class="selected" id="fila_nuevos_' + cont_nuevos + '"><td><button type="button" class="btn btn-warning" onClick="adm_nuevos(0, '+ cont_nuevos +')">X</button></td><td><input class="form-control" readonly value="' + nuevo + '"></td><td><input class="form-control" type="number" id="cantidad_nuevo_'+cont_nuevos+'" name="cant_nuevos_[]" readonly="readonly" value="'+cantidad+'"></td><td><input name="costo_neto_nuevo_'+ cont_nuevos + '" id="costo_neto_nuevo_'+ cont_nuevos + '" class="form-control" readonly="readonly" value="' + costo_neto + '"></td><td><input name="costo_bruto_nuevo_'+ cont_nuevos + '" id="costo_bruto_nuevo_'+ cont_nuevos + '" class="form-control" readonly="readonly" value="' + costo_bruto + '"></td><td><input class="form-control" type="number" id="precio_neto_unidad_nuevo_'+ cont_nuevos + '" readonly="readonly" name="precio_neto_unidad_nuevo[]" value="' + precio_neto + '"></td><td><input class="form-control" name="precio_bruto_unidad_nuevo[]" value="'+ bruto +'"></td><td><input class="form-control" name="costo_total_neto_nuevo[]" value="'+ cantidad * costo_neto +'"></td><td><input class="form-control" name="costo_total_bruto_nuevo[]" value="'+ cantidad * costo_bruto +'"></td><td><input class="form-control" name="precio_total_neto_nuevo[]" value="'+ cantidad * precio_neto +'"></td><td><input class="form-control" name="precio_total_bruto_nuevo[]" value="'+ cantidad * bruto +'"></td></tr>';

              $("#detalles_nuevo").append(fila);

              total_nuevos_neto_venta += precio_neto * cantidad;
              costo_nuevos_neto += costo_neto * cantidad;
              cont_nuevos++;
              
              document.getElementById('costo_total_neto_nuevos').value = parseFloat(costo_nuevos_neto);
              document.getElementById('costo_total_bruto_nuevos').value = parseFloat(conversor_neto_a_bruto(costo_nuevos_neto));
              document.getElementById('precio_total_neto_nuevos').value = parseFloat(total_nuevos_neto_venta);
              document.getElementById('precio_total_bruto_nuevos').value = parseFloat(conversor_neto_a_bruto(total_nuevos_neto_venta));

              document.getElementById('cantidad_nuevo').value = "";
              document.getElementById('costo_neto_empresa_nuevo').value = "";
              document.getElementById('precio_neto_venta_nuevo').value = "";

            }else{
              alert("Faltan campos por rellenar");
            }
        }
        totales();
      }
      function adm_cocineros(control){

        if(control == 4){
          if(cocineros_div == 0){
              $("#cocineros_div").show();
              cocineros_div = 1;
              $("#btn_coc_div").addClass("btn-danger");
              $("#btn_coc_div").removeClass("btn-warning");
          }else{
              $("#cocineros_div").hide();
              cocineros_div = 0;
              $("#btn_coc_div").addClass("btn-warning");
              $("#btn_coc_div").removeClass("btn-danger");
          }
          return;
        }

        liquido = $('#monto_cocineros_liquido').val();
        cantidad = $('#cantidad_cocineros').val();
        costo_cocineros = parseInt((cantidad * liquido * impuesto_renta)) + parseInt(liquido * cantidad);
        document.getElementById('impuesto_10').value = parseInt(cantidad * liquido * impuesto_renta);
        document.getElementById('total_trabajadores_bruto').value = costo_cocineros
        totales();
      }
      function conversor_neto_a_bruto(neto){
        return neto * (1 + iva);
      }
      function totales(){
        var sum_precio_total_neto = total_productos_neto_venta + total_extras_neto_venta + total_nuevos_neto_venta + total_ingrs_neto_venta;
        var sum_costo_total_neto = costo_extras_neto + costo_nuevos_neto + costo_ingredientes  + costo_ingrs + costo_cocineros;

        document.getElementById('costo_parcial_neto').value = parseInt(sum_costo_total_neto);
        document.getElementById('costo_parcial_bruto').value = parseInt(conversor_neto_a_bruto(sum_costo_total_neto));

        document.getElementById('ganancia_neta').value = parseInt(sum_precio_total_neto - sum_costo_total_neto);
        document.getElementById('ganancia_bruta').value = parseInt(conversor_neto_a_bruto(sum_precio_total_neto) - conversor_neto_a_bruto(sum_costo_total_neto));

        document.getElementById('porcentaje_ganacia').value = parseInt((sum_precio_total_neto - sum_costo_total_neto) * 100 / sum_precio_total_neto);


        document.getElementById('total_final_neto').value = parseInt(sum_precio_total_neto);
        document.getElementById('total_final_iva').value = parseInt(sum_precio_total_neto * 0.19);
        document.getElementById('total_final_bruto').value = parseInt(conversor_neto_a_bruto(sum_precio_total_neto));
      }
      function show_hide(e){
        alert(e);
      }
  </script>
</head>
<body>
  <div class="wrapper">
    <div class="row">
      <div class="col-lg-8 col-md-8 col-sm-8">
        <h3>Datos de la simulación</h3>
        <hr></hr>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
        <div class="form-group">
          <label for="fecha_cliente">Nombre de la simulación</label>
          <input class="form-control" name="nombre_simulacion" required/>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
        <div class="form-group">
          <label for="fecha_cliente">Fecha de la simulación</label>
          <input class="form-control" type="date" name="fecha_simulacion" required/>
        </div>
      </div>

      <div class="col-lg-6 col-md-3 col-sm-3 col-xs-6">
        <div class="form-group">
          <label for="fecha_cliente">Descripción de la simulación</label>
          <textarea class="form-control">Aqui va una descripcion</textarea>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-8 col-md-8 col-sm-8">
        <h3>Contenido de la simulación</h3>
        <hr></hr>
      </div>
    </div>
    <div class="col-lg-12" name="TRABAJADORES">
      <div class="row">
        <div class="col-lg-2">
            <h4>Trabajadores</h4>
        </div>
        <div class="col-lg-2">
          <button class="btn btn-warning" id="btn_coc_div" onclick="adm_cocineros(4,0)"><i class="fas fa-eye"></i></button>
        </div>
      </div>
      <div class="table-responsive col-lg-8" hidden id="cocineros_div">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#D2B4DE">
            <th>Pago líquido por cocinero</th>
            <th>Cantidad cocineros</th>
            <th>Impuesto (10%)</th>
            <th>Total: </th>
          </thead>
          <tr>
            <th>
              <input class="form-control" onkeyup="adm_cocineros()" name="monto_cocineros_liquido" id="monto_cocineros_liquido" type="number" placeholder="$20000" required/>
            </th>
            <th>
              <input class="form-control" onkeyup="adm_cocineros()" name="cantidad_cocineros" id="cantidad_cocineros" value="0"/>
            </th>
            <th><input class="form-control" name="impuesto_10" id="impuesto_10"></th>
            <th><input class="form-control" name="total_trabajadores_bruto" id="total_trabajadores_bruto"></th>
          </tr>
        </table>
      </div>
    </div>
    <div class="col-lg-12" name="PRODUCTOS">
      <div class="row">
        <div class="col-lg-2">
            <h4>Productos</h4>
        </div>
        <div class="col-lg-2">
          <button class="btn btn-warning" id="btn_prod_div" onclick="adm_productos(4,0)"><i class="fas fa-eye"></i></button>
        </div>
      </div>

      <div class="row" id="productos_div" hidden>
        <div class="panel panel-primary">
          <div class="panel-body">
            <div class="col-lg-3 col-sm-3 col-md-12 col-xs-12">
              <div class="form-group">
                <label>Productos</label>
                <select name="" onchange="adm_productos(1, this.value)" class="form-control selectpicker" id="id_producto" data-live-search="true">
                  @foreach($productos as $prods)
                    <option value="{{$prods->id}}">{{$prods->nombre}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-lg-3 col-sm-2 col-md-12 col-xs-12">
              <div class="form-group">
                <label>Cantidad</label>
                <input type="number" name="cantidad_productos" id="cantidad_productos" class="form-control">
              </div>
            </div>
            <div class="col-lg-3 col-sm-2 col-md-12 col-xs-12">
              <div class="form-group">
                <label>Precio Neto Unidad</label>
                <input type="number" name="precio_neto_unidad_producto" id="precio_neto_unidad_producto" class="form-control" >
              </div>
            </div>
            <div class="col-lg-1 col-sm-2 col-md-12 col-xs-12">
              <div class="form-group">
                <button type="button" onClick="adm_productos(2)" class="btn btn-primary">+</button>
              </div>
            </div>
            <div class="col-lg-2 col-sm-2 col-md-12 col-xs-12">
              <div class="form-group">
                <button type="button" onClick="adm_productos(5)" class="btn btn-danger">no implementado</button>
              </div>
            </div>
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
              <table id="detalles_producto" class="table table-striped table-bordered table-condensed table-hover">
                <thead style="background-color:#A9D0F5">
                  <th>Quitar</th>
                  <th>Producto</th>
                  <th>Cantidad</th>
                  <th>Costo Total Neto</th>
                  <th>Costo Total Bruto</th>
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
                  <th></th>
                  <th></th>
                  <th>Totales:</th>
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
                </tfoot>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12" name="INGREDIENTES EXTRAS">
      <div class="row">
        <div class="col-lg-2">
            <h4>Ingredientes Extras</h4>
        </div>
        <div class="col-lg-2">
          <button class="btn btn-warning" id="btn_ingrs_div" onclick="adm_ingrs(4,0)"><i class="fas fa-eye"></i></button>
        </div>
      </div>
      <div class="row" hidden id="ingrs_extra_div">
        <div class="panel panel-primary">
          <div class="panel-body">
            <div class="col-lg-3 col-sm-3 col-md-12 col-xs-12">
              <div class="form-group">
                <label>Ingredientes extras</label>
                <select onchange="adm_ingrs(1, this.value)" name="" class="form-control selectpicker" id="id_ingr" data-live-search="true">
                  @foreach($ingredientes as $ingr)
                    <option value="{{$ingr->id}}">{{$ingr->nombre}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-lg-2 col-sm-2 col-md-12 col-xs-12">
              <div class="form-group">
                <label>Cantidad Porciones</label>
                <input type="number" name="cantidad_ingr" id="cantidad_ingr" class="form-control">
              </div>
            </div>
            <div class="col-lg-2 col-sm-2 col-md-12 col-xs-12">
              <div class="form-group">
                <label>Unidad</label>
                <input readonly="readonly" name="unidad_ingr" id="unidad_ingr" class="form-control">
              </div>
            </div>
            <div class="col-lg-2 col-sm-2 col-md-12 col-xs-12">
              <div class="form-group">
                <label>Costo Neto Unidad</label>
                <input type="number" name="costo_neto_ingr" id="costo_neto_ingr" class="form-control" >
              </div>
            </div>
            <div class="col-lg-2 col-sm-2 col-md-12 col-xs-12">
              <div class="form-group">
                <label>Precio Neto Unidad</label>
                <input  type="number" name="precio_neto_venta_ingr" id="precio_neto_venta_ingr" class="form-control">
              </div>
            </div>
            <div class="col-lg-1 col-sm-2 col-md-12 col-xs-12">
              <div class="form-group">
                <button type="button" onClick="adm_ingrs(2)" class="btn btn-primary">+</button>
              </div>
            </div>
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
              <table id="detalles_ingrs" class="table table-striped table-bordered table-condensed table-hover">
                <thead style="background-color:#A9D0F5">
                  <th>Quitar</th>
                  <th>Ingrediente</th>
                  <th>Cantidad Porciones</th>
                  <th>Unidad</th>
                  <th>C Neto Unidad</th>
                  <th>C Bruto Unidad</th>
                  <th>P Neto Unidad</th>
                  <th>P Bruto Unidad</th>
                  <th>Total C Neto</th>
                  <th>Total C Bruto</th>
                  <th>Total P Neto</th>
                  <th>Total P Bruto</th>
                </thead>
                <tfoot>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th>Totales:</th>
                  <th>
                      <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input id="costo_total_neto_ingrs" class="form-control" required readonly="readonly"></input>
                      </div>
                  </th>
                  <th>
                    <div class="input-group">
                      <span class="input-group-addon">$</span>
                      <input id="costo_total_bruto_ingrs" class="form-control" required readonly="readonly"></input>
                    </div>
                  </th>
                  <th>
                      <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input id="precio_total_neto_ingrs" class="form-control" required readonly="readonly"></input>
                      </div>
                  </th>
                  <th>
                    <div class="input-group">
                      <span class="input-group-addon">$</span>
                      <input id="precio_total_bruto_ingrs" class="form-control" required readonly="readonly"></input>
                    </div>
                  </th>
                </tfoot>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12" name="EXTRAS">
      <div class="row">
        <div class="col-lg-2">
            <h4>Extras</h4>
        </div>
        <div class="col-lg-2">
          <button class="btn btn-warning" id="btn_extras_div" onclick="adm_extras(4,0)"><i class="fas fa-eye"></i></button>
        </div>
      </div>
      <div class="row" hidden id="extras_div">
        <div class="panel panel-primary">
          <div class="panel-body">
            <div class="col-lg-3 col-sm-3 col-md-12 col-xs-12">
              <div class="form-group">
                <label>Extras</label>
                <select name="" class="form-control selectpicker" id="id_extra" data-live-search="true">
                  @foreach($extras as $ext)
                    <option value="{{$ext->id}}">{{$ext->valor}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-lg-2 col-sm-2 col-md-12 col-xs-12">
              <div class="form-group">
                <label>Cantidad</label>
                <input type="number" id="cantidad_extra" class="form-control">
              </div>
            </div>
            <div class="col-lg-2 col-sm-2 col-md-12 col-xs-12">
              <div class="form-group">
                <label>Costo Neto Empresa Unidad  </label>
                <input type="number" name="costo_neto_empresa_extra" id="costo_neto_empresa_extra" class="form-control">
              </div>
            </div>
            <div class="col-lg-2 col-sm-2 col-md-12 col-xs-12">
              <div class="form-group">
                <label>Precio Neto Venta Unidad</label>
                <input type="number" name="precio_neto_venta_extra" id="precio_neto_venta_extra" class="form-control" >
              </div>
            </div>
            <div class="col-lg-1 col-sm-2 col-md-12 col-xs-12">
              <div class="form-group">
                <button type="button" onClick="adm_extras(2)" class="btn btn-primary">+</button>
              </div>
            </div>
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
              <table id="detalles_extra" class="table table-striped table-bordered table-condensed table-hover">
                <thead style="background-color:#A9D0F5">
                  <th>Quitar</th>
                  <th>Extra</th>
                  <th>Cantidad</th>
                  <th>C Neto Unidad</th>
                  <th>C Bruto Unidad</th>
                  <th>P Neto Unidad</th>
                  <th>P Bruto Unidad</th>
                  <th>Total Costo Neto</th>
                  <th>Total Costo Bruto</th>
                  <th>Total Venta Neto</th>
                  <th>Total Venta Bruto</th>
                </thead>
                <tfoot>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th>Totales:</th>
                  <th>
                      <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input id="costo_total_neto_extras" class="form-control" required readonly="readonly"></input>
                      </div>
                  </th>
                  <th>
                    <div class="input-group">
                      <span class="input-group-addon">$</span>
                      <input id="costo_total_bruto_extras" class="form-control" required readonly="readonly"></input>
                    </div>
                  </th>
                  <th>
                      <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input id="precio_total_neto_extras" class="form-control" required readonly="readonly"></input>
                      </div>
                  </th>
                  <th>
                    <div class="input-group">
                      <span class="input-group-addon">$</span>
                      <input id="precio_total_bruto_extras" class="form-control" required readonly="readonly"></input>
                    </div>
                  </th>
                </tfoot>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12" name="NUEVOS">
      <div class="row">
        <div class="col-lg-2">
            <h4>Nuevos</h4>
        </div>
        <div class="col-lg-2">
          <button class="btn btn-warning" id="btn_nuevos_div" onclick="adm_nuevos(4,0)"><i class="fas fa-eye"></i></button>
        </div>
      </div>
      <div class="row" hidden id="nuevos_div">
        <div class="panel panel-primary">
          <div class="panel-body">
            <div class="col-lg-3 col-sm-3 col-md-12 col-xs-12">
              <div class="form-group">
                <label>Nuevos items</label>
                <input class="form-control" name="nombre_nuevo_item" id="nombre_nuevo_item"/>
              </div>
            </div>
            <div class="col-lg-2 col-sm-2 col-md-12 col-xs-12">
              <div class="form-group">
                <label>Cantidad</label>
                <input type="number" id="cantidad_nuevo" class="form-control">
              </div>
            </div>
            <div class="col-lg-2 col-sm-2 col-md-12 col-xs-12">
              <div class="form-group">
                <label>Costo Neto Empresa Unidad  </label>
                <input type="number" id="costo_neto_empresa_nuevo" class="form-control">
              </div>
            </div>
            <div class="col-lg-2 col-sm-2 col-md-12 col-xs-12">
              <div class="form-group">
                <label>Precio Neto Venta Unidad</label>
                <input type="number" id="precio_neto_venta_nuevo" class="form-control" >
              </div>
            </div>
            <div class="col-lg-1 col-sm-2 col-md-12 col-xs-12">
              <div class="form-group">
                <button type="button" onClick="adm_nuevos(2)" class="btn btn-primary">+</button>
              </div>
            </div>
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
              <table id="detalles_nuevo" class="table table-striped table-bordered table-condensed table-hover">
                <thead style="background-color:#A9D0F5">
                  <th>Quitar</th>
                  <th>Extra</th>
                  <th>Cantidad</th>
                  <th>C Neto Unidad</th>
                  <th>C Bruto Unidad</th>
                  <th>P Neto Unidad</th>
                  <th>P Bruto Unidad</th>
                  <th>Total Costo Neto</th>
                  <th>Total Costo Bruto</th>
                  <th>Total Venta Neto</th>
                  <th>Total Venta Bruto</th>
                </thead>
                <tfoot>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th>Totales:</th>
                  <th>
                      <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input id="costo_total_neto_nuevos" class="form-control" required readonly="readonly"></input>
                      </div>
                  </th>
                  <th>
                    <div class="input-group">
                      <span class="input-group-addon">$</span>
                      <input id="costo_total_bruto_nuevos" class="form-control" required readonly="readonly"></input>
                    </div>
                  </th>
                  <th>
                      <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input id="precio_total_neto_nuevos" class="form-control" required readonly="readonly"></input>
                      </div>
                  </th>
                  <th>
                    <div class="input-group">
                      <span class="input-group-addon">$</span>
                      <input id="precio_total_bruto_nuevos" class="form-control" required readonly="readonly"></input>
                    </div>
                  </th>
                </tfoot>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12" name="TOTALES">
      <hr></hr>
      <div class="form-group">
          <h3>Totales</h3>
      </div>
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#D2B4DE">
            <th>Costo parcial neto</th>
            <th>Costo parcial bruto</th>
            <th>Ganancia neta</th>
            <th>Ganancia bruta</th>
            <th>(%) estimado de ganancia neto</th>
            <th>Total Neto</th>
            <th>Iva</th>
            <th>Monto a cobrar evento (Bruto)</th>
          </thead>
          <tr>
            <th><input name="costo_parcial_neto"  class="form-control" placeholder="" id="costo_parcial_neto" readonly="readonly"></th>
            <th><input name="costo_parcial_bruto"  class="form-control" placeholder="" id="costo_parcial_bruto" readonly="readonly"></th>
            <th><input name="ganancia_neta"  class="form-control" placeholder="" id="ganancia_neta" readonly="readonly"></th>
            <th><input name="ganancia_bruta"  class="form-control" placeholder="" id="ganancia_bruta" readonly="readonly"></th>
            <th><input name="porcentaje_ganacia"   class="form-control" placeholder="" id="porcentaje_ganacia" readonly="readonly"></th>
            <th><input name="total_final_neto"   class="form-control" placeholder="" id="total_final_neto" readonly="readonly"></th>
            <th><input name="total_final_iva"   class="form-control" placeholder="" id="total_final_iva" readonly="readonly"></th>
            <th><input name="total_final_bruto"   class="form-control" placeholder="" id="total_final_bruto" readonly="readonly"></th>

          </tr>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
