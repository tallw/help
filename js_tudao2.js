$(document).ready(function() {
	$("#escola").change(function() {

		//alert("aa");

		var escola_id = $(this).val();
		//alert(motivo_id);
		if(escola_id != "") {
			$.ajax({
				url:"gera_dados_escola.php",
				data:{c_id:escola_id},
				type:'POST', 
				success:function(response) {
					var resp = $.trim(response);
					$("#vt7").html(resp);
				}
			});
		} else {
			$("#vt7").html("<table class='table table-striped table-bordered table-hover'><tr><td><b>Atenção: </b></td><td colspan='3'>Escolha uma Escola...</td></tr></table>");
		}
	});
});

