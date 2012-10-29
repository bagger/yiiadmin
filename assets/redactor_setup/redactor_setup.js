$(function(){

$('.vTextArea').redactor({
	//css: 'docstyle.css',
	//css: 'wym.css', 
	autoresize: true, 
	fixed: true,
	lang: 'ru',
	overlay: true,
	//xhtml: true,
	autoformat: false,

	// http://redactorjs.com/docs/images/
	imageUpload: glob_imageUpload,
	//imageUploadCallback: function(obj, json) { … },

	// http://redactorjs.com/docs/files/
	//fileUpload: '/file_upload.php',
	//fileUploadCallback: function(obj, json) { … },

	//callback: function(obj) { … },
	//keyupCallback: function(obj, event) { … },
	//keydownCallback: function(obj, event) { … },
	//execCommandCallback: function(obj, command) { … },
	buttons: [
                'html', '|', 
                //'formatting', '|', 
                'bold', 'italic', 'deleted', '|',
                'unorderedlist', 'orderedlist', 'outdent', 'indent', '|',
                'image', 'video', 'file', 'table', 'link', '|',
                'fontcolor', 'backcolor', '|', 
                'alignleft', 'aligncenter', 'alignright', 'justify', '|',
                //'horizontalrule', 'fullscreen'            
	]
}); 

});
