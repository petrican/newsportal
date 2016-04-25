

function DeleteArticle(id, url){

	var r = confirm("Are you sure?");
	if (r == true) {
    	$.ajax({
    		cache: false,
    		url: url,
    		method: 'POST',
    		data: { id: id },
    		success: function(data){
    			if(data.success){
    				$('#article-'+id).slideUp();
    			} else {
    				alert('Error deleting article');
    			}
    		},
    		dataType: 'json'
    	})
    }
}