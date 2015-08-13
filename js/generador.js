// JavaScript Document
$(document).ready(function() {	
	
	$("#database").bind("change", function () {                        
		if(this.value=="")
			return false;
			
		$.post("process.php", { act: "stab", d: this.value }, function (data) {		
			$("#rowtables span").remove();	
			$("#rowtables br").remove();					   
			$.each(data, function(i, val){
				$("#rowtables").append('<span><label>'+val.Tables+':</label></span><span><input name="table[]" id="table[]" type="checkbox" value="'+val.Tables+'" /></span><br>');
			});        
		}, "json");
	});
	 
	$("#formConf").validate({
	 rules: { 
		driver: {required: true},
		host: {required: true},
		
	 },
	 messages: {
		driver: {required: "Campo requerido"},
		host: {required: "Campo requerido"},
		
	 },
	});
	
	$(".fk").change(function(){
		var id = $(this).attr("data-id");
		$.post("process.php", {act: "loadField", t: $(this).val()}, function(data){
			var data = JSON.parse(data);
			$("#campos"+id).html();
			for(i=0; i<data.length;i++){
				$("#campos"+id).append('<option value="'+ data[i] +'">'+ data[i] +'</option>');
			}		
		});
	});
	
	$(".typeField").change(function(){
		var id = $(this).attr("data-id");
		if($(this).val()=="option"){
			$("#fk"+id).show();
			$("#campos"+id).show();
		}else{
			$("#fk"+id).hide();
			$("#campos"+id).hide();
		}
	});
	
});
