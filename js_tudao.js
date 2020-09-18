$(document).ready(function() {
	$("#escola").change(function() {

		//alert("aa");

		var escola_id = $(this).val();
		//alert(motivo_id);
		if(escola_id != "") {
			$.ajax({
				url:"gera_timeline_tudao.php",
				data:{c_id:escola_id},
				type:'POST', 
				success:function(response) {
					var resp = $.trim(response);
					$("#vt6").html(resp);
				}
			});
		} else {
			$("#vt6").html("<h4>Não existe histórico da escola selecionada...</h4>");
		}
	});
});