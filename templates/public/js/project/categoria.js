$(document).ready(function() { 
$(document).on('click', '#newform, #close', function(){ 
var content = $("#idForm"), button = $("#newform"), table = $("#divtable"); 
	if (content.is(':hidden')) { 
		button.html('Ocultar Formulario'); 
		table.slideUp(1000); 
		content.slideDown(1500); 
	}else{ 
		button.html('Mostrar Formulario'); 
		table.slideDown(1000); 
		content.slideUp(1000); 
	} 
 }); 
	 var tCategoria = $('#table').dataTable({ 
		'fnServerData': function ( sUrl, aoData, fnCallback, oSettings ) { 
			oSettings.jqXHR = $.ajax( { 
				'url':  sUrl, 
				'data': aoData, 
				'success': function (json) { 
					$(oSettings.oInstance).trigger('xhr', oSettings); 
					fnCallback( json ); 
				}, 
				'dataType': 'json', 
				'cache': false, 
				'type': oSettings.sServerMethod, 
				'error': function (xhr, error, thrown) { 
					if ( error == 'parsererror' ) { 
						oSettings.oApi._fnLog( oSettings, 1, 'DataTables warning: JSON data from '+ 
							'server could not be parsed. This is caused by a JSON formatting error.' ); 
						console.log(xhr.responseText); 
					} 
				} 
			}); 
		}, 
		'bProcessing' 		: true, 
		'bServerSide' 		: true, 
		'sAjaxSource' : 'categoria', 
		'fnServerParams': function (aoData) { 
			aoData.push({'name': 'act', 'value': 'listar'}); 
		}, 
		'bSearchable' : true, 
		'sScrollY' : $(window).height() * 0.99 - 377, 
		'sDom' : 'frtiSHF', 
		'bDeferRender' : true, 
		'bJQueryUI' : true, 
		'sPaginationType' : 'full_numbers', 
		'sServerMethod' : 'POST', 
		'aoColumns' : [{ 
			'bVisible' : false 
		}, 
		null, 
		null, 
		{ 
			'bSortable' : false, 
			'bSearchable': false, 
			'mData' : null, 
			'mRender' : function( data, type, full ) { 
				return '<div style="display:block;" aling="center">' 
				+ '<a href="javascript:;" class="edit sepV_a btn btn-warning" rel="'+full[0]+'" title="Edit">Editar</a>' 
				+ ' <a href="javascript:;" title="Delete" class="delete btn btn-danger" rel="'+full[0]+'">Eliminar</a>' 
			} 
		}] 
	 });
 
	  $(document).on('click', '.delete', function(){ 
		var id = $(this).attr('rel'); 
if (id == '') { 
	 		toastr['error']('A ocurrido un error al cargar el Javascript de la Pagina, comuniquese con el equipo de it@latinoaustralia.com'); 
	 			 return false; 
} 
bootbox.confirm('Estas seguro? que quieres eliminar el banner', function(result) { 
		if (result) { 
	 	 $.post('categoria', {act:'delete',id:id}, function(obj){ 
	 	 toastr[obj.type](obj.msg); 
	 	 	 tCategoria.fnClearTable(true); 
  },'json'); 
} 
}); 
}); 

 
	  $(document).on('click', '.edit', function(){ 
		var cod = $(this).attr('rel'); 
		$.post('categoria', { act: 'edit', m: cod }, function (obj) {	
		}, 'json'); 
	  }); 

 
	var v = $('#categoria').validate({ 
			rules : { 
	 tienda_idtienda: {required: true}, 
	 nombre: {required: true}, 
					}, 
					errorElement: "div", 
					submitHandler : function(form) {
						$(form).ajaxSubmit({
							dataType : 'json',
							type: 'post',							url: 'categoria',							success : function(obj,statusText, xhr, $form) { 
								tCategoria.fnClearTable(true); 
								toastr[obj.type](obj.msg);var content = $("#divform"), button = $("#newform"), table = $("#divtable");table.slideDown(2000); content.slideUp(2000);								$('#categoria')[0].reset();
							} 
				}); 
			  } 
	});

 
});