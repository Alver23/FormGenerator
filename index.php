<?php require "process.php"; $noIncluir = array("ip_address", "created_at", "updated_at", "owner_user_id", "updater_user_id"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="public/sweetalert/sweetalert.css">
<link rel="stylesheet" href="public/bootstrap/css/bootstrap.min.css">
<script type="text/javascript" src="public/js/jquery-1.7.2.js"></script>
<script type="text/javascript" src="public/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="public/js/jquery.selectboxes.min.js"></script>
<script type="text/javascript" src="public/sweetalert/sweetalert.min.js"></script>
<script type="text/javascript" src="public/js/generador.js"></script>
<title>Lucumi - Form Generator</title>
</head>
<body>
<?php if(!empty($rs) && $sw!=1){ ?>
<div class="container">
	<div class="row">
		<div id="stylized" class="myform col-md-12">
			<form method="post" name="formCrear" id="formCrear" action="">
				<table class="table">
				  <tr>
					<td align="center"><strong>Tabla</strong></td>
					<td align="center"><strong>Action</strong></td>
				  </tr>
				  <?php foreach($rs as $row){?>
				  <tr>
					<td><?php echo ucwords($row); ?></td>
					<td align="center"><input name="table[]" id="table[]" type="checkbox" value="<?php echo $row; ?>" /></td>
				  </tr>
				  <?php } ?>
				  <tr>
					<td colspan="2" align="center">
						<input class="btn btn-warning" type="submit" name="button" id="button" value="Generar Formularios" />
						<input name="act" id="act" type="hidden" value="cForm" />
					</td>
				  </tr>
				</table>
			</form>
		</div>
	</div>
</div>
<?php 
}else{
	if(!empty($array)){
?>
<div class="container">
	<div class="row">	
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-12">
					<ul class="nav nav-tabs">
						<?php foreach(array_keys($array) as $row) {?>
						<li><a href="<?php echo "#tab".$row?>"><?php echo ucwords($row); ?></a></li>
						<?php } ?>
					</ul>
				</div>
			</div>
			
			<br><br>
			<div class="tab_container">
			<?php foreach(array_keys($array) as $row) { ?>
			<div id="<?php echo "tab".$row?>" class="tab_content">
				<form method="post" name="<?php echo "form".$row; ?>" id="<?php echo "form".$row; ?>" action="" class="form-action">
					<input type="hidden" name="tabla" value="<?php echo $row; ?>" />
					<div class="row">
						<div class="col-md-4" align="center">
							<label>Editar</label>
							Si <input type="radio" name="editar" id="editar" value="si" checked="checked"/> 
							No <input type="radio" name="editar" id="editar" value="no" />
						</div>
						<div class="col-md-4" align="center">
							<label>Eliminar</label>
							Si <input type="radio" name="eliminar" id="eliminar" value="si" checked="checked"/> 
							No <input type="radio" name="eliminar" id="eliminar" value="no" />
						</div>
						<div class="col-md-4" align="center">
							<label>Grilla</label>
							Si <input type="radio" name="grilla" id="checkbox" value="si" checked="checked"/> 
							No <input type="radio" name="grilla" id="checkbox" value="no"/>
						</div>
					</div>
					<br><br>
					<table class="table table-striped table-flip-scroll cf">
						  <tr>
							<th align="center">Campo</th>
							<th align="center">Tipo</th>
							<th align="center">Obligatorio</th>
							<th align="center">Autocompletar</th>
							<th align="center">En datatable</th>
							<th align="center">Tabla relacion</th>
							<th align="center">Campo relacion</th>
							<th align="center">Utilizar en el form si/no</th>
						  </tr>
						  <?php $x = 0;  
								foreach($array[$row] as $rw){
									if(!in_array($rw, $noIncluir)){
						  ?>
						  <tr>
							<td>
								<?php echo $rw; ?>
								<input name="campoName[<?php echo $x; ?>]" type="hidden" value="<?php echo $rw; ?>" />
							</td>
							<td>
							  <select id="type<?php echo $x; ?>" name="type[<?php echo $x; ?>]" class="typeField form-control" data-id="<?php echo $x; ?>">
								<option value="">[Select Type]</option>
								<option value="texto">Campo de Texto</option>
								<option value="option">Select Option</option>
								<option value="oculto">Campo Oculto</option>
								<option value="area">Area Texto</option>
								<option value="check">Check</option>
								<option value="file">File</option>
							  </select>
							</td>
							<td align="center"> Si <input type="radio" name="obligatorio[<?php echo $x; ?>]" value="si" checked="checked"/> No <input type="radio" name="obligatorio[<?php echo $x; ?>]" value="no" /></td>
							<td align="center"> Si <input type="radio" name="autocompletar[<?php echo $x; ?>]" value="si" /> No <input type="radio" name="autocompletar[<?php echo $x; ?>]" value="no" checked="checked"/></td>
							<td align="center"> 
								Si <input type="radio" name="mostrarData[<?php echo $x; ?>]" value="si" checked="checked"/> 
								No <input type="radio" name="mostrarData[<?php echo $x; ?>]" value="no"/>
							</td>
							<td>
							  <select id="fk<?php echo $x; ?>" name="TablaR[<?php echo $x; ?>]" class="fk form-control" data-id="<?php echo $x; ?>">
								<option value="">[Select Tabla]</option>
								<?php foreach($rs as $r){?>
								<option value="<?php echo $r; ?>"><?php echo $r; ?></option>
								<?php } ?>
							  </select>
							</td>
							<td>
							  <select id="campos<?php echo $x; ?>" name="relacion[<?php echo $x; ?>]" class="campos form-control">
								<option value="">[Campo id]</option>  	
							  </select>
							  <!--
							  <select id="camposName<?php echo $x; ?>" name="relacionName[<?php echo $x; ?>]" class="campos form-control">
								<option value="">[Campo id]</option>  	
							  </select>
							  -->
							</td>
							<td align="center"> 
								Si <input type="radio" name="utilizar[<?php echo $x; ?>]" value="si" checked="checked" /> 
								No <input type="radio" name="utilizar[<?php echo $x; ?>]" value="no" />
							</td>
						  </tr>
									<?php } $x++; } ?>
						</table>
						<input type="hidden" name="act" value="CrearForm" />
						<input class="btn btn-success btn-cons" name="enviar" type="submit" value="Generar" class="button"/>
				</form>
			</div>
			<?php 	
				 }
			?>
		</div>
		</div>
	</div>
</div>
<?php
	}
}
?>
<script>
$(document).ready(function() {
	$(".tab_content").hide(); //Ocultamos los contenidos
	$("ul.tabs li").eq(0).addClass("active").show(); //Damos el focus al primer tab
	$(".tab_content").eq(0).show(); //Mostramos el primer contenido
 
	$("ul.tabs li").click(function() {
		//console.log($(this))
		$("ul.tabs li").removeClass("active"); //Eliminamos la clase active de los li
		$(this).addClass("active");//Adicionamos la clase active al elemento
		$(".tab_content").hide(); //Ocultamos todos los tab_content
		var actTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
		$(actTab).fadeIn(); //Mostrar el content actual
		return false;
	});
	
	<?php 
	if(!empty($array)){
		foreach(array_keys($array) as $row) {
		 	 $uno = '$("#form'.$row.'").validate({';
			 $uno .= 'submitHandler: function(form) {';
			 $uno .= 'Referencia = $("#form'.$row.'").serialize();';
			 $uno .= '$.ajax({';
			 $uno .= 'type: "POST",';
		 	 $uno .= 'url: "process.php",';
			 $uno .= 'data: Referencia,';
			 $uno .= '	success: function(data){';
			 $uno .= '	swal("Formularios Creados");';
			 $uno .= ' }});';
			 $uno .= 'return false;';
			 $uno .= '}';
			 $uno .= '});';
		echo $uno;
		}
	}
	 ?>
});
</script>
</body>
</html>
