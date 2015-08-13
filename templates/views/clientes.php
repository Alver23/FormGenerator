<?php 
$this->extend('layout'); 
$this->set('title', @$title); 
$this->javascripts->add('public/js/project/clientes.js');?>
<div class='row'> 
<div class='col-lg-12'> 
<section class='panel'> 
<button class='btn btn-success' id='newform'>Mostrar Formulario</button> 
</section> 
</div> 
</div> 
<div class='row' id='divtable'> 
<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'> 
<section class='panel'> 
<header class='panel-heading'>Mantenimiento </header> <div class='panel-body' id='gallery'> 
<table class='table table-bordered table-striped' id='table'> 
	 <thead> 
	   <tr> 
	 	 <th class='essential'>idclientes</th> 
	 	 <th class='essential'>nombre</th> 
	 	 <th class='essential'>apellido</th> 
	 	 <th class='essential'>telefono</th> 
	 	 <th class='essential'>celular</th> 
	 	 <th class='essential'>email</th> 
	 	 <th class='essential'>Actions</th> 
	    </tr> 
	    </thead> 
	   <tbody> 
	   </tbody> 
</table> 
</div> 
</section> 
</div> 
</div> 
<div class="row" id="idForm" style="display:none;"><div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><section class="panel"><div class="panel-body" id="divform"><form name="clientes" id="clientes" action="" method="POST" >
	<input type="hidden" name="idclientes" id="idclientes" value=""  /><br />
<div class='row'> 
	<div class='col-md-12'> 
	 	<label id="nombre">nombre: </label> 
	 	<input type="text" name="nombre" id="nombre" size="25" value="" class='form-control' /> 
	</div> 
</div> 
<div class='row'> 
	<div class='col-md-12'> 
	 	<label id="apellido">apellido: </label> 
	 	<input type="text" name="apellido" id="apellido" size="25" value="" class='form-control' /> 
	</div> 
</div> 
<div class='row'> 
	<div class='col-md-12'> 
	 	<label id="telefono">telefono: </label> 
	 	<input type="text" name="telefono" id="telefono" size="25" value="" class='form-control' /> 
	</div> 
</div> 
<div class='row'> 
	<div class='col-md-12'> 
	 	<label id="celular">celular: </label> 
	 	<input type="text" name="celular" id="celular" size="25" value="" class='form-control' /> 
	</div> 
</div> 
<div class='row'> 
	<div class='col-md-12'> 
	 	<label id="email">email: </label> 
	 	<input type="text" name="email" id="email" size="25" value="" class='form-control' /> 
	</div> 
</div> 
<span><br><input type="submit" name="submit" value="Guardar" class="btn btn-primary" />&nbsp;&nbsp;&nbsp;<input type="reset" name="reset" value="Limpiar" class="btn btn-info"/></span>
</div> 
</section> 
</div> 
</div> 
</form> 
