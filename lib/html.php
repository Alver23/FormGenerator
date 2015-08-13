<?php
#Created by Michel Gomes Ank
#E-mail: michel@lafanet.com.br
#MSN: mitheus@bol.com.br
#ICQ: 530377777
class form{
	
	function form_start($name,$action,$method,$add=''){
		$form = "<div class=\"row\" id=\"idForm\" style=\"display:none;\">";
		$form .= "<div class=\"col-xs-12 col-sm-12 col-md-12 col-lg-12\">";
		$form .= "<section class=\"panel\">";
		$form .= "<div class=\"panel-body\" id=\"divform\">";
		$form .= "<form name=\"".$name."\" id=\"".$name."\" action=\"".$action."\" method=\"".$method."\" ".$add.">\n";
		 return $form;
	}
	
	
	function extendLayout($name){
			$query = "<?php \n";
			$query .= "\$this->extend('layout'); \n";
			$query .= "\$this->set('title', @\$title); \n";
			$query .= "\$this->javascripts->add('public/js/project/$name.js');";
			$query .= "?>\n";
		 return $query;
	}
	
	#Function: <input text>
	function form_text($name_txt, $name, $length, $value='',$add='',$dps='',$aut='') {
	  $cam = "<div class='row'> \n";
	  $cam .= "\t<div class='col-md-12'> \n";
		if($aut==1){
			$cam .= "\t\t<label for=\"auto_".$name."\">".$name_txt.": </label> \n";
			$cam .= "\t\t<input type=\"text\" name=\"auto_".$name."\" id=\"auto_".$name."\" size=\"".$length."\" value=\"".$value."\" class='form-control' /> ".$dps."\n";
			$cam .= "\t\t<input type=\"hidden\" name=\"".$name_txt."\" id=\"".$name_txt."\" value=\"".$value."\" ".$add." /> \n";
		}else{	
			$cam .= "\t \t<label id=\"".$name."\">".$name_txt.": </label> \n";
			$cam .= "\t \t<input type=\"text\" name=\"".$name."\" id=\"".$name."\" size=\"".$length."\" value=\"".$value."\" class='form-control' /> ".$dps."\n";
		}
	  $cam .= "\t</div> \n";	
	  $cam .= "</div> \n";
	  return $cam;
	}
	
	#Function: <selects>
	function form_select($name_txt,$name,$size,$opt_name,$opt_value,$selected='',$add='') {
		$select = "\t<span>".$name_txt.": </span>\n";
		$select .= "\t<select name=\"".$name."\" ".$add.">\n";
		$opt_name = explode(",",$opt_name);
		$opt_value = explode(",",$opt_value);
		$qts = count($opt_name);
		for($i=0;$i<$qts;$i++) {
			if($opt_value[$i] == $selected) {
				$select .= "\t\t<option selected value=\"".$opt_value[$i]."\">".$opt_name[$i]."</option>\n";
			}else{
				$select .= "\t\t<option value=\"".$opt_value[$i]."\">".$opt_name[$i]."</option>\n";
			}
		}
		$select .= "\t</select><br />\n";
		return $select;
	}
	
	#Function: <input checkbox...>
	function form_checkbox($name_txt,$name,$checked='',$value='1',$add='') {
		if($checked) $checked = "checked";
		return "\t<span>".$name_txt.": </span>".$this->form_hidden($name)."<input type=\"checkbox\" name=\"".$name."\" ".$checked." value=\"".$value."\" class=\"checkbox\" ".$add." /><br />\n";
	}
	
	#Function: <textarea...>
	function form_textarea($name_txt,$name,$rows,$cols,$value='',$add='') {
		return "\t<span>".$name_txt.":<br /><textarea name=\"".$name."\" id=\"".$name."\" rows=\"".$rows."\" cols=\"".$cols."\">".$value."</textarea></span><br />\n";
	}
	
	#Function: <input file>
	function form_file($name_txt,$name,$add='',$dps='') {
		return "\t<span>".$name_txt."</span><input type=\"file\" name=\"".$name."\" ".$add." /> ".$dps."<br />";
	}
	
	#Function: <input hidden...>
	function form_hidden($name, $value='', $add='') {
		return "\t<input type=\"hidden\" name=\"".$name."\" id=\"".$name."\" value=\"".$value."\" ".$add." /><br />\n";
	}
	
	#Function: Submit/Reset
	function form_go($submit,$reset=''){
		$saida = "<span><br>";
		$saida .= "<input type=\"submit\" name=\"submit\" value=\"".$submit."\" class=\"btn btn-primary\" />";
		if($reset) {
			$saida .= "&nbsp;&nbsp;&nbsp;<input type=\"reset\" name=\"reset\" value=\"".$reset."\" class=\"btn btn-info\"/>";
		}
		return $saida .= "\t<input type=\"hidden\" name='act' value='save' />\n";
		$saida .= "</span>\n";
		return $saida;
	}
	
	#Closing the form
	function form_end() {
		$form = "</form> \n";
		$form .= "</div> \n";
		$form .= "</section> \n";
		$form .= "</div> \n";
		$form .= "</div> \n";
		return $form;
	}
	
	#Crear tabla para el Datatable
	function datatable($campos, $opt) {
		$cant = count($campos); 
		$salida = "";
		$salida .= "<div class='row'> \n";
		$salida .= "<div class='col-lg-12'> \n";
		$salida .= "<section class='panel'> \n";
		$salida .= "<button class='btn btn-success' id='newform'>Mostrar Formulario</button> \n"; 
		$salida .= "</section> \n";
		$salida .= "</div> \n";
		$salida .= "</div> \n";
		
		$salida .= "<div class='row' id='divtable'> \n";
		$salida .= "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'> \n";
		$salida .= "<section class='panel'> \n";
		$salida .= "<header class='panel-heading'>Mantenimiento </header> <div class='panel-body' id='gallery'> \n";
		
		$salida .= "<table class='table table-bordered table-striped' id='table'> \n";
        $salida .= "\t <thead> \n";
        $salida .= "\t   <tr> \n";
		for($x=0; $x<=$cant-1; $x++){
			if($opt[$x]=="si"){
			  $salida .= "\t \t <th class='essential'>".$campos[$x]."</th> \n";
			}
		}
		$salida .= "\t \t <th class='essential'>Actions</th> \n";
        $salida .= "\t    </tr> \n";
        $salida .= "\t    </thead> \n";
        $salida .= "\t   <tbody> \n";  
        $salida .= "\t   </tbody> \n";
        $salida .= "</table> \n";
		
		$salida .= "</div> \n";
		$salida .= "</section> \n";
		$salida .=  "</div> \n";
		$salida .= "</div> \n";
		
		return $salida;
	}
}
?>