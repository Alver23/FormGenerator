<?php 
$this->extend('layout'); 
$this->set('title', @$title); 
$this->javascripts->add('public/js/project/groups.js');
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
	 	 <th class='essential'>group_id</th> 
	 	 <th class='essential'>group_name</th> 
	 	 <th class='essential'>group_description</th> 
	 	 <th class='essential'>is_disabled</th> 
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
	 	 	 <form name="groups" id="groups" action="" method="POST" > 
	 	 	 <input type="hidden" name="group_id" id="group_id" value=""  /><br />
<div class='row'> 
	<div class='col-md-12'> 
	 	<label id="group_name">group_name: </label> 
	 	<input type="text" name="group_name" id="group_name" size="25" value="" class='form-control' /> 
	</div> 
</div> 
	 	 	 <label>group_description: </label>
	 	 	 <select name="group_description" >
	 	 	 <option selected value="">[Select group_description]</option>
	 	 	 </select><br />
	 	 	 <input type="submit" name="submit" value="Guardar" class="btn btn-primary" /> 
	 	 	 <input type="reset" name="reset" value="Limpiar" class="btn btn-info"/> 
 	 	 	 <input type="hidden" name='act' value='save' /> 
	 	 	 </form> 
	 	 	 </div> 
	 	 </section> 
	 </div> 
</div> 
