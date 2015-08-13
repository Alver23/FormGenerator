<?php 
$this->extend('layout'); 
$this->set('title', @$title); 
$this->javascripts->add('public/js/project/lang.js');?>
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
	 	 <th class='essential'>idlang</th> 
	 	 <th class='essential'>name</th> 
	 	 <th class='essential'>code</th> 
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
<div class="row" id="idForm" style="display:none;"><div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><section class="panel"><div class="panel-body" id="divform"><form name="lang" id="lang" action="" method="POST" >
	<input type="hidden" name="idlang" id="idlang" value=""  /><br />
<div class='row'> 
	<div class='col-md-12'> 
	 	<label id="name">name: </label> 
	 	<input type="text" name="name" id="name" size="25" value="" class='form-control' /> 
	</div> 
</div> 
<div class='row'> 
	<div class='col-md-12'> 
	 	<label id="code">code: </label> 
	 	<input type="text" name="code" id="code" size="25" value="" class='form-control' /> 
	</div> 
</div> 
<span><br><input type="submit" name="submit" value="Guardar" class="btn btn-primary" />&nbsp;&nbsp;&nbsp;<input type="reset" name="reset" value="Limpiar" class="btn btn-info"/></span>
</div> 
</section> 
</div> 
</div> 
</form> 
