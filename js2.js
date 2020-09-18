$(document).ready(function() {
	$("#motivo").change(function() {

		var motivo_id = $(this).val();
		//alert(motivo_id);
		if(motivo_id != "") {
			$.ajax({
				url:"busca_sub_motivos.php",
				data:{c_id:motivo_id},
				type:'POST', 
				success:function(response) {
					var resp = $.trim(response);
					$("#sub_motivo").html(resp);
				}
			});
		} else {
			$("#sub_motivo").html("<option value=''></option>");
		}
	});
});