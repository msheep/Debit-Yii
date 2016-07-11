(function($){
	$(function(){
		$("#userview-search-form").submit(function(){
			$form = $(this);
			$.ajax({
				url : $form.attr('action'),
				data : $form.serialize(),
				type : $form.attr('method'),
				success : function(res){
					if(res){
						$("#info").html(res);
					}else{
						alert('无此用户!');
					}
				},
				error : function(error){
					
				}
			});
			return false;
		});
	})
})(jQuery)