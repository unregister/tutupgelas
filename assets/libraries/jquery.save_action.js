$(document).ready(function(){
	$(pea_submit_id).click(function(){
		$('form'+pea_form_id).ajaxForm({
			url:pea_url_action,
			type: 'POST',
			dataType:'json',
			beforeSubmit:function(){
				$('.pea-loading-span').fadeIn(400);	
			},
			success:function(q){
				$('.pea-loading-span').fadeOut(400);	
				$('#peaFormAdd-response').html(q.msg);
			}
		}).submit();
		return false;
	});
});
