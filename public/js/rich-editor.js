// Initialize TinyMCE for product description
if (typeof tinymce !== 'undefined') {
	tinymce.init({
		selector: '#description',
		menubar: false,
		plugins: 'lists link image code',
		toolbar:
			'undo redo | bold italic underline | bullist numlist | link image | code',
		height: 300,
		branding: false, // Remove TinyMCE watermark
		statusbar: false // Remove status bar ("p" tag info)
	});
}
