tinymce.PluginManager.add('cceShortcodes', function(editor, url) {

	editor.addCommand( 'ccePopup', function( ui, v ){
		var popup = v.identifier;

		// load thickbox
		tb_show( editor.getLang('cce.insert'), ajaxurl + "?action=popup&popup=" + popup + "&width=" + 920 );
	});

	var menu_items = [
        { text: editor.getLang('cce.content_related'), menu: [
	        { onclick: function(e){ addPopup('accordion') }, text: editor.getLang('cce.accordion') },
	        { onclick: function(e){ addPopup('button') }, text: editor.getLang('cce.button') },
	        { onclick: function(e){ addPopup('iconbox') }, text: editor.getLang('cce.iconbox') },
			{ onclick: function(e){ addPopup('notification') }, text: editor.getLang('cce.notification') },
			{ onclick: function(e){ addPopup('tabs') }, text: editor.getLang('cce.tabs') },
			{ onclick: function(e){ addPopup('testimonial') }, text: editor.getLang('cce.testimonial') }

        ] },
        
        { onclick: function(e){ addPopup('layout') }, text: editor.getLang('cce.layout') },
        
        { text: editor.getLang('cce.typography_related'), menu: [
	        { onclick: function(e){ addPopup('dropcap') }, text: editor.getLang('cce.dropcap') },
	        { onclick: function(e){ addPopup('leadparagraph') }, text: editor.getLang('cce.leadparagraph') },
	        { onclick: function(e){ addPopup('label') }, text: editor.getLang('cce.label') }

        ] },
        
        { text: editor.getLang('cce.misc_elements'), menu: [
	        { onclick: function(e){ addPopup('image') }, text: editor.getLang('cce.image') },
	        { onclick: function(e){ addPopup('video') }, text: editor.getLang('cce.video') },
	        { onclick: function(e){ addPopup('hdivider') }, text: editor.getLang('cce.hdivider') },
			{ onclick: function(e){ addPopup('map') }, text: editor.getLang('cce.map') }

        ] },
    ];

    editor.addButton('cceShortcodes', {
		icon: 'cce',
		text: false,
		tooltip: editor.getLang('cce.insert'),
		type: 'menubutton',
		menu: menu_items
	});

	function addPopup( shortcode ) {
		tinyMCE.activeEditor.execCommand( "ccePopup", false, {
			title: tinyMCE.activeEditor.getLang('cce.insert'),
			identifier: shortcode
		});
	}
});
