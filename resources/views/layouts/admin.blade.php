<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ERP Carritos Camaleon | www.erpcamaleon.cl</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap-select.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('css/font-awesome.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('css/AdminLTE.min.css')}}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{asset('css/_all-skins.min.css')}}">
    <link rel="icon" href="{{asset('img/logo_2i.png')}}">
    <link rel="shortcut icon" href="{{asset('img/logo_2i.png')}}">

    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.5.1/css/iziModal.css">

    <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
     @yield('style')

  </head>
  <body class="hold-transition skin-green sidebar-mini">
    <div class="wrapper">

      <header class="main-header">

        <!-- Logo -->
        <a href="/carritos/eventos" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><IMG SRC="{{ asset('img/logo_2i.png') }}" WIDTH=50 HEIGHT=50/></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg">
            <IMG SRC="{{ asset('img/logo_2.png') }}" WIDTH=160 HEIGHT=45/>

          </span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Navegación</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- Messages: style can be found in dropdown.less-->

              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <span class="hidden-md" ><i class="fas fa-user"></i> <strong>{{Auth::user()->name}}</strong></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">

                    <p>
                      ERP Carritos camaleón
                    </p>
                    <p style="color:yellow">
                      Usuario: {{Auth::user()->name}}
                    </p>
                    <p style="color:yellow">
                      Nivel de acceso: {{Auth::user()->nivel}}
                    </p>
                  </li>

                  <!-- Menu Footer-->
                  <li class="user-footer">

                    <div class="pull-right">
                      <a href="{{ route('logout') }}"
                          onclick="event.preventDefault();
                                   document.getElementById('logout-form').submit();"
                                   class="btn btn-default btn-flat">
                          <i class="fa fa-sign-out-alt"></i> Logout
                      </a>

                      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                          {{ csrf_field() }}
                      </form>
                    </div>
                  </li>
                </ul>
              </li>

            </ul>
          </div>

        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->

          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header"></li>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-calendar-alt"></i>
                <span>Calendarios</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="/calendario_eventos"><i class="far fa-circle"></i> Calendario eventos</a></li>
                <li><a href="/calendario"><i class="far fa-circle"></i> Calendario cotizaciones</a></li>
              </ul>
            </li>


            <li class="treeview">
              <a href="#">
                <i class="fa fa-dollar-sign"></i>
                <span>Simulación y eventos</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="/carritos/simulaciones"><i class="far fa-circle"></i> Todos las simulaciones</a></li>
                <li><a href="/simulador"><i class="far fa-circle"></i> Nueva simulación o evento</a></li>
                <li><a href="/index_eventos"><i class="far fa-circle"></i> Lista eventos</a></li>
              </ul>
            </li>
            @if(Auth::user()->nivel == 'Administrador')
            <li class="treeview">
              <a href="#">
                <i class="fa fa-th"></i>
                <span>Productos y extras</span>
                 <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="/carritos/productos"><i class="far fa-circle"></i> Todos los productos</a></li>
                <li><a href="/carritos/productos/create"><i class="far fa-circle"></i> Nuevo producto</a></li>
                <li><a href="/carritos/extras"><i class="far fa-circle"></i> Todos los extras</a></li>
                <li><a href="/carritos/extras/create"><i class="far fa-circle"></i> Nuevo extra</a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-boxes"></i>
                <span>Inventario</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="/carritos/inventario"><i class="far fa-circle"></i> Mostrar inventario</a></li>
                <li><a href="/carritos/inventario/create"><i class="far fa-circle"></i> Registrar ingreso</a></li>
                <li><a href="/carritos/ingredientes"><i class="far fa-circle"></i> Administrar ingredientes</a></li>

              </ul>
            </li>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-chart-line"></i>
                <span>Finanzas</span>
                 <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="/carritos/pagos"><i class="far fa-circle"></i> Pagos pendientes</a></li>
              </ul>
            </li>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-wrench"></i></i> <span>Personas</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="/carritos/trabajadores"><i class="far fa-circle"></i> Trabajadores</a></li>
                <li><a href="/carritos/clientes"><i class="far fa-circle"></i> Clientes</a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-users"></i>
                <span>Usuarios</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="/registro"><i class="far fa-circle"></i> Lista de usuarios</a></li>
                <li><a href="/registrar"> <i class="far fa-circle"></i> Registrar nuevo usuario</a></li>
              </ul>
            </li>
            @endif

            <li class="treeview">
              <a href="#">
                <i class="fa fa-thermometer-half"></i> <span>Estados</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="/carritos/mercaderiaproxeventos"><i class="far fa-circle"></i> Mercadería eventos próximos</a></li>
              </ul>
            </li>
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>

       <!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">

        <!-- Main content -->
        <section class="content">

          <div class="row">
            <div class="col-md-12">
              <div class="box">
                <div class="box-header with-border">

                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body" id="app">
                  	<div class="row">
	                  	<div class="col-md-12">
		                          <!--Contenido-->
                              @yield('contenido')
		                          <!--Fin Contenido-->
                           </div>
                        </div>

                  		</div>
                  	</div><!-- /.row -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      <!--Fin-Contenido-->
      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 2.1.4 release
        </div>
        <strong><a href="http://www.carritoscamaleon.cl">Carritos Camaleón</a></strong>
      </footer>
    <!-- jQuery 2.1.4 -->
    @stack('scripts')
    <script src="{{asset('js/jQuery-2.1.4.min.js')}}"></script>

    <!-- Bootstrap 3.3.5 -->
    <script src="{{asset('js/bootstrap.min.js')}}"></script>

    <script src="{{asset('js/bootstrap-select.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('js/app.min.js')}}"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="{{asset('js/sweetalert2.all.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.5.1/js/iziModal.min.js"></script>
    @yield('script')
  </body>
</html>
