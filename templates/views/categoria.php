<?php 
$this->extend('layout'); 
$this->set('title', @$title); 
$this->javascripts->add('public/js/project/categoria.js');?>
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
	 	 <th class='essential'>idcategoria</th> 
	 	 <th class='essential'>tienda_idtienda</th> 
	 	 <th class='essential'>nombre</th> 
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
<div class="row" id="idForm" style="display:none;"><div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><section class="panel"><div class="panel-body" id="divform"><form name="categoria" id="categoria" action="" method="POST" >
	<input type="hidden" name="idcategoria" id="idcategoria" value=""  /><br />
<div class='row'> 
	<div class='col-md-12'> 
	 	<label id="tienda_idtienda">tienda_idtienda: </label> 
	 	<input type="text" name="tienda_idtienda" id="tienda_idtienda" size="25" value="" class='form-control' /> 
	</div> 
</div> 
<div class='row'> 
	<div class='col-md-12'> 
	 	<label id="nombre">nombre: </label> 
	 	<input type="text" name="nombre" id="nombre" size="25" value="" class='form-control' /> 
	</div> 
</div> 
<span><br><input type="submit" name="submit" value="Guardar" class="btn btn-primary" />&nbsp;&nbsp;&nbsp;<input type="reset" name="reset" value="Limpiar" class="btn btn-info"/>	<input type="hidden" name='act' value='save' />
</form> 
</div> 
</section> 
</div> 
</div> 
