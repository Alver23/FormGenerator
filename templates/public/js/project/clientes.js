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
	 var tClientes = $('#table').dataTable({ 
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
		'sAjaxSource' : 'clientes', 
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
		null, 
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
		var cod = $(this).attr('rel'); 
		$.post('clientes', { act: 'delete', m: cod }, function (obj) {	
			tClientes.fnClearTable(true); 
				$('body').showMessage({ 
	 
				}); 
			}, 'json'); 
	  }); 

 
	  $(document).on('click', '.edit', function(){ 
		var cod = $(this).attr('rel'); 
		$.post('clientes', { act: 'edit', m: cod }, function (obj) {	
		}, 'json'); 
	  }); 

 
	var v = $('#clientes').validate({ 
			rules : { 
	 nombre: {required: true}, 
	 apellido: {required: true}, 
	 telefono: {required: true}, 
	 celular: {required: true}, 
	 email: {required: true}, 
					}, 
					errorElement: "div", 
					submitHandler : function(form) {
						$(form).ajaxSubmit({
							dataType : 'json',
							success : function(obj,statusText, xhr, $form) { 
								tClientes.fnClearTable(true); 
								$('body').showMessage({ 
								}); 
								$('#clientes')[0].reset();
							} 
				}); 
			  } 
	});

 
});