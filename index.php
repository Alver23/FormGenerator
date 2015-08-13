<?php require "process.php"; 
$noIncluir = array("ip_address", "created_at", "updated_at", "owner_user_id", "updater_user_id"); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/jquery.alerts.css">
<link rel="stylesheet" href="templates/public/css/bootstrap.min.css">
<link rel="stylesheet" href="templates/public/css/style.css">
<link rel="stylesheet" href="css/style.css">
<script type="text/javascript" src="js/jquery-1.7.2.js"></script>
<script type="text/javascript" src="js/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/jquery.selectboxes.min.js"></script>
<script type="text/javascript" src="js/jquery.alerts.js"></script>
<script type="text/javascript" src="js/generador.js"></script>
<title>Lucumi - Form Generator</title>
</head>
<body>
<?php if(!empty($rs) && $sw!=1){ ?>
<div id="stylized" class="myform">
    <form method="post" name="formCrear" id="formCrear" action="">
        <table class="table table-striped table-flip-scroll cf">
          <tr>
            <td colspan="2" align="center"><strong>Tablas</strong></td>
          </tr>
          <?php foreach($rs as $row){?>
          <tr>
            <td><?php echo $row; ?></td>
            <td><input name="table[]" id="table[]" type="checkbox" value="<?php echo $row; ?>" /></td>
          </tr>
          <?php } ?>
          <tr>
            <td colspan="2" align="center">
                <input type="submit" name="button" id="button" value="Generar Formularios" />
                <input name="act" id="act" type="hidden" value="cForm" />
            </td>
          </tr>
        </table>
    </form>
</div>
<?php 
}else{
	if(!empty($array)){
?>
<div class="content">
    <ul class="tabs">
    	<?php foreach(array_keys($array) as $row) {?>
        <li><a href="<?php echo "#tab".$row?>"><?php echo $row; ?></a></li>
        <?php } ?>
    </ul>
    <div class="tab_container">
    	<?php foreach(array_keys($array) as $row) { ?>
    	<div id="<?php echo "tab".$row?>" class="tab_content">
       		<h2><?php echo "Crear: ".$row; ?></h2>
            <form method="post" name="<?php echo "form".$row; ?>" id="<?php echo "form".$row; ?>" action="">
            	<input type="hidden" name="tabla" value="<?php echo $row; ?>" />
         		<div class="CSSTableGenerator">
            	<table class="table table-striped table-flip-scroll cf">
                      <tr style="font-size:14px;">
                          <td>&nbsp;</td>
                          <td>Editar</td>
                          <td>Eliminar</td>
                          <td align="center">Grilla</td>
                          <td rowspan="2" align="center"></td>
                          <td rowspan="2" align="center"></td>
                          <td rowspan="2" align="center"></td>
                          <td rowspan="2" align="center"></td>
                      </tr>
                      <tr style="font-size:14px;">
                        <td>&nbsp;</td>
                        <th align="center"> Si <input type="radio" name="editar" id="editar" value="si" checked="checked"/> No <input type="radio" name="editar" id="editar" value="no" /></td>
                      </th>
                      <th>Si <input type="radio" name="eliminar" id="eliminar" value="si" checked="checked"/> No <input type="radio" name="eliminar" id="eliminar" value="no" /></th>
                      <th align="center"> Si <input type="radio" name="grilla" id="checkbox" value="si" checked="checked"/> No <input type="radio" name="grilla" id="checkbox" value="no"/></th>
                      <tr>
                        <td align="center">Campo</td>
                        <td align="center">Tipo</td>
                        <td align="center">Obligatorio</td>
                        <td align="center">Autocompletar</td>
                        <td align="center">Mostrar en Datatable</td>
                        <td align="center">Tabla Relacion</td>
                        <td align="center">Campo Relacion</td>
                        <td align="center">Utilizar en el form si/no</td>
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
                          <select id="type<?php echo $x; ?>" name="type[<?php echo $x; ?>]" class="typeField" data-id="<?php echo $x; ?>">
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
                        <td align="center"> Si <input type="radio" name="mostrarData[<?php echo $x; ?>]" value="si"/> No <input type="radio" name="mostrarData[<?php echo $x; ?>]" value="no" checked="checked"/></td>
                        <td>
                          <select id="fk<?php echo $x; ?>" name="TablaR[<?php echo $x; ?>]" class="fk" data-id="<?php echo $x; ?>">
                            <option value="">[Select Tabla]</option>
                            <?php foreach($rs as $r){?>
                            <option value="<?php echo $r; ?>"><?php echo $r; ?></option>
                            <?php } ?>
                          </select>
                        </td>
                        <td>
                          <select id="campos<?php echo $x; ?>" name="relacion[<?php echo $x; ?>]" class="campos">
                            <option value="">[Select Relacion]</option>  	
                          </select>
                        </td>
                        <td align="center"> Si <input type="radio" name="utilizar[<?php echo $x; ?>]" value="si"/> No <input type="radio" name="utilizar[<?php echo $x; ?>]" value="no" checked="checked"/></td>
                      </tr>
								<?php } $x++; } ?>
                    </table>
                    </div>
                    <input type="hidden" name="act" value="CrearForm" />
                    <input class="btn btn-danger btn-cons" name="enviar" type="submit" value="Generar" class="button"/>
            </form>
        </div>
        <?php 	
			 }
		?>
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
			 $uno .= '	jAlert("Formularios Creados","Mensaje");';
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
