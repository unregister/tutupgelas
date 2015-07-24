var roxyFileman = BASE_URL+'assets/filemanager/index.html';
$(function(){
	$( 'textarea.texteditor' ).ckeditor(
	{
		toolbar:'Standard',
		filebrowserBrowseUrl:roxyFileman, 
		filebrowserUploadUrl:roxyFileman,
		filebrowserImageBrowseUrl:roxyFileman+'?type=image',
		filebrowserImageUploadUrl:roxyFileman+'?type=image'
	}
	);
	$( 'textarea.mini-texteditor' ).ckeditor({toolbar:'Basic',width:700});
});