var d = new Date();
var thn = d.getFullYear();
$(function(){
	$('.datepicker-input').datepicker({
			dateFormat: js_date_format,
			showButtonPanel: true,
			changeMonth: true,
			changeYear: true,
			yearRange:'1980:'+thn
	});
	
	$('.datepicker-input-clear').button();
	
	$('.datepicker-input-clear').click(function(){
		$(this).parent().find('.datepicker-input').val("");
		return false;
	});
	
});