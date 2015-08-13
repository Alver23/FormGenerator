<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="img/favicon.png">

    <title><?php $this->output('title') ?></title>
	<base href="<?PHP $this->output('baseh') ?>" />
    <!-- Bootstrap core CSS -->
    <link href="public/css/bootstrap.min.css" rel="stylesheet">
    <link href="public/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="public/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="public/css/style.css" rel="stylesheet">
    <link href="public/css/style-responsive.css" rel="stylesheet" />
    <link rel="stylesheet" href="public/plugins/toastr/toastr.min.css" type="text/css">
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/themes/base/jquery-ui.css" type="text/css" />
    <link rel="stylesheet" href="public/css/common.css" type="text/css">
	<?php echo $this->stylesheets; ?>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

  <section id="container" class="">
      <!--header start-->
      <header class="header white-bg">
          <div class="sidebar-toggle-box">
              <div data-original-title="Toggle Navigation" data-placement="right" class="icon-reorder tooltips"></div>
          </div>
          <!--logo start-->
          <a href="#" class="logo" >Trocen<span> Inventario</span></a>
          <!--logo end-->
          <div class="nav notify-row" id="top_menu">
            <!--  notification start -->
          </div>
      </header>
      <!--header end-->
      <!--sidebar start-->
      <aside>
          <div id="sidebar"  class="nav-collapse ">
              <!-- sidebar menu start-->
              <ul class="sidebar-menu">
                  <li class="sub-menu"><a  href="categoria"><i class="icon-book"></i><span>Categoria</span></a></li>
				  <li class="sub-menu"><a  href="categoria"><i class="icon-book"></i><span>Sub-categoria</span></a></li>
				  <li class="sub-menu"><a  href="categoria"><i class="icon-book"></i><span>Articulos</span></a></li>
				  <li class="sub-menu"><a  href="categoria"><i class="icon-book"></i><span>Proveedores</span></a></li>
				  <li class="sub-menu"><a  href="categoria"><i class="icon-book"></i><span>Clientes</span></a></li>
				  <li class="sub-menu"><a  href="categoria"><i class="icon-book"></i><span>Facturas</span></a></li>
				  <li class="sub-menu"><a  href="categoria"><i class="icon-book"></i><span>Orden de compra</span></a></li>
                  <li>
                      <a class="" href="salir.php">
                          <i class="icon-user"></i>
                          <span>Exit</span>
                      </a>
                  </li>
              </ul>
              <!-- sidebar menu end-->
          </div>
      </aside>
      <!--sidebar end-->
      <!--main content start-->
      <section id="main-content">
          <section class="wrapper">
              <!-- page start-->
              <?php $this->output('content') ?>
              <!-- page end-->
          </section>
      </section>
      <!--main content end-->
  </section>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="public/js/jquery.js"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js" charset="UTF-8"></script>
    <script src="public/js/bootstrap.min.js"></script>
    <script src="public/js/jquery.scrollTo.min.js"></script>
    <script src="public/js/jquery.nicescroll.js" type="text/javascript"></script>

    <script src="public/js/jquery.selectbox-0.2.js" type="text/javascript"></script>
    <script type="text/javascript" src="public/assets/data-tables/jquery.dataTables.js"></script>
    <script type="text/javascript" src="public/assets/data-tables/DT_bootstrap.js"></script>

    <script type="text/javascript" src="public/plugins/toastr/toastr.min.js"></script>
    <script type="text/javascript" src="public/plugins/bootbox/bootbox.min.js"></script>
    <!--common script for all pages-->
    <script src="public/js/common-scripts.js"></script>
    <script src="public/js/common.js"></script>
	<script src="public/plugins/tinymce3.5/jscripts/tiny_mce/tiny_mce.js"></script>
	<script type="text/javascript" src="public/js/jquery.form.js"></script>
	<script type="text/javascript" src="public/js/jquery.validate.min.js"></script>
    
	<?php echo $this->javascripts; ?>
  </body>

</html>

