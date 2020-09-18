$(document).ready(function() {
	$("#departamento").change(function() {
		var dp_id = $(this).val();
		if(dp_id != "") {
			$.ajax({
				url:"busca_motivos.php",
				data:{c_id:dp_id},
				type:'POST', 
				success:function(response) {
					var resp = $.trim(response);
					$("#motivo").html(resp);
				}
			});
		} else {
			$("#motivo").html("<option value=''></option>");
		}
	});
});


