// JavaScript Document /admin/upload/file_list
var editor; 
	KindEditor.ready(function(K) {
		editor = K.create('textarea[id="content"]', {
			cssPath : '/admin/views/js/kindeditor/plugins/code/prettify.css',
			uploadJson : '/admin/upload/imgup',
			fileManagerJson : '/admin/upload/file_list_json',
			allowFileManager : true
		});
});