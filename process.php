<?php require "config/config.php"; 
$database = new conDatabase();
$javascript = new Javascript();
$sw = 0;
if($_POST){	

	//crear tablas de validacion de campos
	if(@$_POST['act']=="cForm"){
		foreach($_POST['table'] as $row){
			//Listar campos de las tablas
			$array[$row] = $database->ListarCampos($row);
			
			//validar las referencias de tablas
			$res = $database->referencia($row);
		}
		$rs = $database->listarTables(DATABASE);
		$sw = 1;
	}
	
	//Listamos los campos de la tabla con la que se relaciona 
	if(@$_POST['act']=="loadField"){
		die(json_encode($database->referencia($_POST['t'])));
	}
	
	//Generar Html y Javascript
	if($_POST['act']=="CrearForm"){
		$form = new form();  $Javascript = new Javascript();
		$print = ""; $jquery = "";
		
		$print .= $form->extendLayout($_POST['tabla']);
		
		//Si queremos definir el datatable
		if($_POST['grilla']=="si"){
			$print .= $form->datatable($_POST['campoName'], $_POST['mostrarData']);
		}
		
		//Iniciamos el formulario
		$print .= $form->form_start($_POST['tabla'],"",'POST');
		$cant = count($_POST['campoName']);
		//Analizamos el tipo para crear el campo del form
		for($x=0; $x<$cant; $x++){
		  #Generamos las el Html para las vistas
		  if($_POST['utilizar'][$x]=="si"){
		  	switch($_POST['type'][$x]){
				  case "option": //Cargamos el select option con la tabla relacionada
						if(!empty($_POST['TablaR'][$x])){
							$rSelect = $database->loadSelect($_POST['TablaR'][$x]); 
						}
						$print .= $form->form_select($_POST['campoName'][$x],$_POST['campoName'][$x],"1","[Select ".$_POST['campoName'][$x]."]",",","");
					break;
				  case "oculto":
						$print .= $form->form_hidden($_POST['campoName'][$x],"");
					break;
				  case 'file':
						$print .= $form->form_file($_POST['campoName'][$x],$_POST['campoName'][$x],"","Files of type: .jpg");
					break;
				  case 'area':
						$print .= $form->form_textarea($_POST['campoName'][$x],$_POST['campoName'][$x],"5","30");
				    break;
				  case 'check':
				  		if($_POST['obligatorio']=="Si"){$check = "checked"; }else{ $check = "";}
						$print .= $form->form_checkbox($_POST['campoName'][$x],$_POST['campoName'][$x], $check,"1");
				    break;
				  case 'radio':
					
				    break;
				  case 'texto':
				  		$Long = 25;	$cadena = ""; $aut = 0;
						if($_POST['obligatorio'][$x]=="Si"){ $cadena = "requerido";	}
						if($_POST['autocompletar'][$x]=="Si"){ $cadena = "autocompletar"; $aut = 1;}
				  		$print .= $form->form_text($_POST['campoName'][$x], $_POST['campoName'][$x], $Long, "","class=\"".$_POST['obligatorio'][$x]."\"","",$aut);	
				  break;	
			}
		  }
		}#EndFor
			#Finalizamos el form
			$print .= $form->form_go("Guardar","Limpiar");
			$print .= $form->form_end();
			if(!file_exists("templates/views/")){
				if(!mkdir("templates/views/", 0777, true)){
					die('Fallo al crear las carpetas...');
				}
			}
			#Generar Archivo cshtml
			if (file_exists("templates/views/".$_POST['tabla']."Form.php")) {
				unlink("templates/views/".$_POST['tabla']."Form.php");
			}			
			$fp = fopen("templates/views/".$_POST['tabla']."Form.php","w+");
			fwrite($fp, $print);
			fclose($fp);
			
			
			#Iniciamos el Dom
			$jquery .= $Javascript->Domm_start();
			$jquery .= $Javascript->mostrarForm($_POST['tabla']);
			
			#creamos Datatable Javascript
			if(@$_POST['grilla']=="si"){	
				$jquery .= $Javascript->dataTable($_POST['tabla'], $_POST['campoName'], $_POST['mostrarData']);
				$jquery .="\n \n";
			}
		  	
			#Generamos el Eliminar
			if(@$_POST['eliminar']=="si"){
				$jquery .= $Javascript->send_delete($_POST['tabla']);
				$jquery .="\n \n";
			}
			
			#Generamos el Editar
			if(@$_POST['editar']=="si"){
				$jquery .= $Javascript->send_edit($_POST['tabla']);
				$jquery .="\n \n";
			}
			
			#Generamos el Guardar
			$jquery .= $Javascript->form_validate($_POST['tabla'], $_POST['campoName'], $_POST['obligatorio']);
			$jquery .= "\n \n";
						
			#Finalizamos el Dom
			$jquery .= $Javascript->form_end();
			
			#crear archivo Javascript
			if(!file_exists("templates/js/")){
				if(!mkdir("templates/js/", 0777, true)){
					die('Fallo al crear las carpetas...');
				}
			}
			if (file_exists("templates/js/".$_POST['tabla'].".js")){
				unlink("templates/js/".$_POST['tabla'].".js");
			}
			$fp = fopen("templates/js/".$_POST['tabla'].".js","w");
			fwrite($fp,$jquery);
			fclose($fp);
			
			//Creacion de controlador basico
			$rstIndex = $database->showIndex($_POST['tabla']);
			
			$Controller = new Controller();
			$control = $Controller->open_php($_POST['tabla']);
			
			$control .= $Controller->valPost();
			$control .= $Controller->saveController($_POST['tabla'], $rstIndex[0]);
			$control .= $Controller->listarController($_POST['campoName'], $_POST['mostrarData'], $_POST['tabla']);
			$control .= $Controller->deleteController($_POST['tabla']);
			$control .= $Controller->editController($_POST['tabla']);
			$control .= $Controller->elsePost();
			$control .= $Controller->renderTemplate($_POST['tabla']);
			$control .= $Controller->endPost();
			
			$control .= $Controller->close_php();
			
			if(!file_exists("templates/controller/")){
				if(!mkdir("templates/controller/", 0777, true)){
					die('Fallo al crear las carpetas...');
				}
			}
			if (file_exists("templates/controller/".$_POST['tabla']."Controller.php")) {
				unlink("templates/controller/".$_POST['tabla']."Controller.php");
			}
			$fp = fopen("templates/controller/".$_POST['tabla']."Controller.php","x");
			fwrite($fp,$control);
			fclose($fp);
			
			//cargamos extension del modelo
			$Model = new Model();
			$modelos = $Model->openModel($_POST['tabla']);
			$modelos .= $Model->modelPaginate($_POST['tabla'], $_POST['campoName'], $_POST['mostrarData']);
			$modelos .= $Model->closeModel();
			
			if(!file_exists("templates/lib/")){
				if(!mkdir("templates/lib/", 0777, true)){
					die('Fallo al crear las carpetas...');
				}
			}
			if (file_exists("templates/lib/".$_POST['tabla']."Model.php")) {
				unlink("templates/lib/".$_POST['tabla']."Model.php");
			}
			$fp = fopen("templates/lib/".$_POST['tabla']."Model.php","x");
			fwrite($fp,$modelos);
			fclose($fp);
			
			die(json_encode("Formularios y Javascript creados correctamente"));
	}
}
if($sw==0){
	$rs = $database->listarTables(DATABASE);
}