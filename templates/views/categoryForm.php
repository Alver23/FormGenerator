<?php 
$this->extend('layout'); 
$this->set('title', @$title); 
$this->javascripts->add('public/js/project/category.js');
?>

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
	 	 <header class='panel-heading'>Mantenimiento </header> 
	 	 <div class='panel-body' id='gallery'> 
	 	 <table class='table table-bordered table-striped' id='table'> 
	 	 <thead> 
	 	 <tr> 
	 	 <th class='essential'>idcategory</th> 
	 	 <th class='essential'>name</th> 
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
<div class="row" id="idForm" style="display:none;"> 
	 <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"> 
	 	 <section class="panel"> 
	 	 	 <div class="panel-body" id="divform"> 
	 	 	 <form name="category" id="category" action="" method="POST" > 
	 	 	 <input type="hidden" name="idcategory" id="idcategory" value=""  /><br />
	 	 	 <label>name: </label>
	 	 	 <select name="name" >
	 	 	 <option selected value="">[Select name]</option>
	 	 	 </select><br />
	 	 	 <input type="submit" name="submit" value="Guardar" class="btn btn-primary" /> 
	 	 	 <input type="reset" name="reset" value="Limpiar" class="btn btn-info"/> 
 	 	 	 <input type="hidden" name='act' value='save' /> 
	 	 	 </form> 
	 	 	 </div> 
	 	 </section> 
	 </div> 
</div> 
