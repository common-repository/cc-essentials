(function(){
	tinymce.create( "tinymce.plugins.cceShortcodes", {

		init: function ( d, e ) {
			d.addCommand("ccePopup", function( a, params){
				var popup = params.identifier;

				// load thickbox
				tb_show("Insert CC Shortcodes", ajaxurl + "?action=popup&popup=" + popup + "&width=" + 920 );
			});
		},

		createControl: function( d, e ){
			var ed = tinymce.activeEditor;

			if( d === "cceShortcodes" ){
				d = e.createMenuButton( "cceShortcodes", {
					title: ed.getLang('cce.insert'),
					icons: false
				});

				var a = this;
				d.onRenderMenu.add( function( c, b ) {

					c = b.addMenu( { title:ed.getLang('cce.media_elements') } );
						a.addWithPopup( c, ed.getLang('cce.image'), "image" );
						a.addWithPopup( c, ed.getLang('cce.video'), "video" );

					b.addSeparator();

					a.addWithPopup( b, ed.getLang('cce.button'), "button" );
					a.addWithPopup( b, ed.getLang('cce.columns'), "columns" );
					a.addWithPopup( b, ed.getLang('cce.dropcap'), "dropcap" );
					a.addWithPopup( b, ed.getLang('cce.tabs'), "tabs" );
					a.addWithPopup( b, ed.getLang('cce.toggle'), "toggle" );
					a.addWithPopup( b, ed.getLang('cce.icon'), "icon" );
					a.addWithPopup( b, ed.getLang('cce.map'), "map" );

				});

				return d;

			}
			return null;
		},

		addWithPopup: function (d, e, a){
			d.add({
				title: e,
				onclick: function() {
					tinyMCE.activeEditor.execCommand( "ccePopup", false, {
						title: e,
						identifier: a
					})
				}
			});
		},

		addImmediate:function(d,e,a){
			d.add({
				title:e,
				onclick:function(){
					tinyMCE.activeEditor.execCommand( "mceInsertContent",false,a)
				}
			})
		}

	});

	tinymce.PluginManager.add( "cceShortcodes", tinymce.plugins.cceShortcodes);

})();
