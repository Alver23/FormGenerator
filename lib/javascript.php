<?php
#Created by Daniel Felipe Lucumi Marin
#E-mail: dflm25@gmail.com
#MSN: dannycali@hotmail.com
#CALI - COLOMBIA
class Javascript{
	#Function: <ready Dom>
	function Domm_start() {
		@$jquery .= "$(document).ready(function() { \n";
		return $jquery;
	}
	
	#Function: Autocomplete
	function Autocomplete(){
		$jquery = "";
		$jquery .= "\t $('#MenuAutoCompletar').autocomplete({ \n";
		$jquery .= "\t	minChars: 0, \n";
		$jquery .= "\t	source: function(request, response) { \n";
		$jquery .= "\t		$.ajax({ \n";
		$jquery .= "\t			url: 'menues', \n";
		$jquery .= "\t			dataType: 'json', \n";
		$jquery .= "\t			type: 'POST', \n";
		$jquery .= "\t			data: { \n";
		$jquery .= "\t				act: 'autoComplete', \n";
		$jquery .= "\t				term : request.term, \n";
		$jquery .= "\t				nameText : 'menu', \n";
		$jquery .= "\t				limit: 20 \n";
		$jquery .= "\t			},";
		$jquery .= "\t			success: function(data) { \n";
		$jquery .= "\t				response($.map(data, function(item) { \n ";
		$jquery .= "\t					return { \n ";
		$jquery .= "\t						data:[ \n ";
		$jquery .= "\t							item.name, \n ";
		$jquery .= "\t							item.idMenu \n ";
		$jquery .= "\t						], \n ";
		$jquery .= "\t						value: item.name, \n ";
		$jquery .= "\t						result: item.idMenu \n ";
		$jquery .= "\t					} \n ";
		$jquery .= "\t				})); \n ";
		$jquery .= "\t			}, \n ";
		$jquery .= "\t		}); \n ";
		$jquery .= "\t	}, \n ";
		$jquery .= "\t	select: function( event, ui ){ \n ";
		$jquery .= "\t		$('#Menu_idMenu').val(ui.item.result) \n";
		$jquery .= "\t	} \n ";
		$jquery .= "\t});\n";
		return $jquery;
	}
	#Function: Autocomplete
	function form_validate($table, $sql, $opc){
		$cant = count($sql);
		$jquery = "";
		$jquery .= "	var v = $('#".$table."').validate({ \n";
		$jquery .= "\t		rules : { \n";
		for($x=1; $x<$cant; $x++){
			if($opc[$x]=="si"){
				$jquery .= "\t ".$sql[$x].": {required: true}, \n";
			}
		}
		$jquery .= "\t				}, \n";
		$jquery .= "\t				errorElement: \"div\", \n";
		$jquery .= "\t				submitHandler : function(form) {\n";
		$jquery .= "\t					$(form).ajaxSubmit({\n";
		$jquery .= "\t						dataType : 'json',\n";
		$jquery .= "\t						type: 'post',";
		$jquery .= "\t						url: '".$table."',";
		$jquery .= "\t						success : function(obj,statusText, xhr, $"."form) { \n";
		$jquery .= "\t							t".ucwords($table).".fnClearTable(true); \n";
		$jquery .= "\t							toastr[obj.type](obj.msg);";
		
		$jquery .= "var content = $(\"#divform\"), button = $(\"#newform\"), table = $(\"#divtable\");";
		$jquery .= "table.slideDown(2000); content.slideUp(2000);";
		
		$jquery .= "\t							$('#".$table."')[0].reset();\n";
		$jquery .= "\t						} \n";
		$jquery .= "\t			}); \n";
		$jquery .= "\t		  } \n";
		$jquery .= "	});\n";
		return $jquery;
	}
	
	#Agregar el delete
	function send_delete($table){	
		$jquery ="";
		$jquery .= "\t  $(document).on('click', '.delete', function(){ \n";
		$jquery .= "\t	var id = $(this).attr('rel'); \n";
		$jquery .= "if (id == '') { \n";
    	$jquery .= "\t \t	toastr['error']('A ocurrido un error al cargar el Javascript de la Pagina, comuniquese con el equipo de it@latinoaustralia.com'); \n";
    	$jquery .= "\t \t	\t return false; \n";
    	$jquery .= "} \n";
		
		$jquery .= "bootbox.confirm('Estas seguro? que quieres eliminar el banner', function(result) { \n";
		$jquery .= "\t	if (result) { \n";
		$jquery .= "\t \t \$.post('".$table."', {act:'delete',id:id}, function(obj){ \n";
		$jquery .= "\t \t toastr[obj.type](obj.msg); \n";
		$jquery .= "\t \t \t t".ucwords($table).".fnClearTable(true); \n";
		$jquery .= "  },'json'); \n";
		$jquery .= "} \n";
		$jquery .= "}); \n";
		$jquery .= "}); \n";
		return $jquery;
	}
	
	#Agregar el Edit
	function send_edit($table){	
		$jquery ="";
		$jquery .= "\t  $(document).on('click', '.edit', function(){ \n";
		$jquery .= "\t	var cod = $(this).attr('rel'); \n";
		$jquery .= "\t	$.post('".$table."', { act: 'edit', m: cod }, function (obj) {	\n";
		$jquery .= "\t	}, 'json'); \n";
		$jquery .= "\t  }); \n";
		return $jquery;
	}
	
	function mostrarForm($tabla){
		$form = "$(document).on('click', '#newform, #close', function(){ \n";
		$form .="var content = $(\"#idForm\"), button = $(\"#newform\"), table = $(\"#divtable\"); \n";
		//$form .="	$('#$tabla')[0].reset(); \n";
		//$form .="	Limipiar(); \n";
		$form .="	if (content.is(':hidden')) { \n";
		$form .="		button.html('Ocultar Formulario'); \n";
		$form .="		table.slideUp(1000); \n";
		$form .="		content.slideDown(1500); \n";
		$form .="	}else{ \n";
		$form .="		button.html('Mostrar Formulario'); \n";
		$form .="		table.slideDown(1000); \n";
		$form .="		content.slideUp(1000); \n";
		$form .="	} \n";
		$form .=" }); \n";
		return $form;
	}
	
	#Agregar el datatable
	function dataTable($table, $sql, $opcion){	
		$cant = count($sql);
		$jquery ="";
		// Utilizando el plugin Jquery DataTable para hacer el consultar AJAX
		$jquery .= "\t var t".ucwords($table)." = $('#table').dataTable({ \n";
		$jquery .= "\t	'fnServerData': function ( sUrl, aoData, fnCallback, oSettings ) { \n";
		$jquery .= "\t		oSettings.jqXHR = $.ajax( { \n";
		$jquery .= "\t			'url':  sUrl, \n";
		$jquery .= "\t			'data': aoData, \n";
		$jquery .= "\t			'success': function (json) { \n";
		$jquery .= "\t				$(oSettings.oInstance).trigger('xhr', oSettings); \n";
		$jquery .= "\t				fnCallback( json ); \n";
		$jquery .= "\t			}, \n";
		$jquery .= "\t			'dataType': 'json', \n";
		$jquery .= "\t			'cache': false, \n";
		$jquery .= "\t			'type': oSettings.sServerMethod, \n";
		$jquery .= "\t			'error': function (xhr, error, thrown) { \n";
		$jquery .= "\t				if ( error == 'parsererror' ) { \n";
		$jquery .= "\t					oSettings.oApi._fnLog( oSettings, 1, 'DataTables warning: JSON data from '+ \n";
		$jquery .= "\t						'server could not be parsed. This is caused by a JSON formatting error.' ); \n";
		$jquery .= "\t					console.log(xhr.responseText); \n";
		$jquery .= "\t				} \n";
		$jquery .= "\t			} \n";
		$jquery .= "\t		}); \n";
		$jquery .= "\t	}, \n";
		$jquery .= "\t	'bProcessing' 		: true, \n";
		$jquery .= "\t	'bServerSide' 		: true, \n";
		$jquery .= "\t	'sAjaxSource' : '".$table."', \n";
		$jquery .= "\t	'fnServerParams': function (aoData) { \n";
		$jquery .= "\t		aoData.push({'name': 'act', 'value': 'listar'}); \n";
		$jquery .= "\t	}, \n";
		$jquery .= "\t	'bSearchable' : true, \n";
		$jquery .= "\t	'sScrollY' : $(window).height() * 0.99 - 377, \n";
		$jquery .= "\t	'sDom' : 'frtiSHF', \n";
		$jquery .= "\t	'bDeferRender' : true, \n";
		$jquery .= "\t	'bJQueryUI' : true, \n";
		$jquery .= "\t	'sPaginationType' : 'full_numbers', \n";
		$jquery .= "\t	'sServerMethod' : 'POST', \n";
		$jquery .= "\t	'aoColumns' : [{ \n";
		$jquery .= "\t		'bVisible' : false \n";
		$jquery .= "\t	}, \n";
		for($x=1; $x<$cant;$x++){
			if($opcion[$x]=="si"){
				$jquery .= "\t	null, \n";
			}
		}
		$jquery .= "\t	{ \n";
		$jquery .= "\t		'bSortable' : false, \n";
		$jquery .= "\t		'bSearchable': false, \n";
		$jquery .= "\t		'mData' : null, \n";
		$jquery .= "\t		'mRender' : function( data, type, full ) { \n";
		$jquery .= "\t			return '<div style=\"display:block;\" aling=\"center\">' \n";
		$jquery .= "\t			+ '<a href=\"javascript:;\" class=\"edit sepV_a btn btn-warning\" rel=\"'+full[0]+'\" title=\"Edit\">Editar</a>' \n";
		$jquery .= "\t			+ ' <a href=\"javascript:;\" title=\"Delete\" class=\"delete btn btn-danger\" rel=\"'+full[0]+'\">Eliminar</a>' \n";
		$jquery .= "\t		} \n";
		$jquery .= "\t	}] \n";
		$jquery .= "\t });";
		return $jquery;
	}
	
	#Closing the Dom
	function form_end() {
		return "});";
	}
}
?>