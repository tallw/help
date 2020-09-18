(function($) {	  
	gera_pdf = function() {	
		

		var html_table = document.getElementById("table_rel_sla").value;

		//alert(html_table);
		
		if(html_table != "") {
			$.ajax({
				url:"rel_quantitativo_pdf.php",
				data:{c_id:datas},
				type:'POST', 
				success:function(response) {
					alert("pdf gerado");
				}
			});
		} else {
			alert("Erro de html");
		}
    		
    };	

})(jQuery); 