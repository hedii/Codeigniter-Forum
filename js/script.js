jQuery(document).ready(function($) {

	/**
	 * WYSIWYG Editor
	 *
	 * Tiny WISWYG rich text editor
	 */
	$('#wysiwyg_heading').click(function() {
		document.execCommand('formatBlock', false, '<h4>');
	});
	$('#wysiwyg_bold').click(function() {
		document.execCommand('bold', false, null);
	});
	$('#wysiwyg_italic').click(function() {
		document.execCommand('italic', false, null);
	});
	$('#wysiwyg_underline').click(function() {
		document.execCommand('underline', false, null);
	});
	$('#wysiwyg_code').click(function() {
		document.execCommand('FormatBlock', false, 'pre');
	});
	$('#wysiwyg_list').click(function() {
		document.execCommand('insertUnorderedList', false, 'newUL');
	});
	$('#wysiwyg_link').click(function() {
		var linkUrl = prompt('Enter a url link:', 'http://');
		document.execCommand('createLink', false, linkUrl);
	});
	$('#wysiwyg_unlink').click(function() {
		document.execCommand('Unlink', false, null);
	});
	
	// image upload
	$('#wysiwyg_image').click(function() {

	});
	
	// live copy of editor content to hidden textarea
	$("#wysiwygEditor").bind('keyup keydown mouseup mousedown mousemove mouseleave click change', function() {
		var value = $("#wysiwygEditor").html().replace(/\n/g, '<br/>');
		$('#reply_post_content').html(value); // new post
		$('#new_topic_content').html(value);  // new topic
		return;
	});

}); // document.ready