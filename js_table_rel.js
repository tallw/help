(function($) {	  
	AddTableRow = function() {	

		var data_in = document.getElementById("inicial").value;
		var data_fi = document.getElementById("final").value;

		var datas = data_in + "#" + data_fi;
 
		if(datas != "#") {
			$.ajax({
				url:"busca_os_rel.php",
				data:{c_id:datas},
				type:'POST', 
				success:function(response) {
					var resp = $.trim(response);
					$("#table_rel_sla").html(resp);
				}
			});
		} else {
			$("#table_rel_sla").html("<tr><td colspan='9'>Não existe movimentação nesse intervalo de datas</td></tr>");
		}
    		
    };	

})(jQuery); 

 