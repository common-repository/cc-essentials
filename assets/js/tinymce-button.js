(function(tinymce) {
	tinymce.PluginManager.add('cce_mce_hr_button', function( editor, url ) {
		editor.addButton('cce_mce_hr_button', {
			icon: 'hr',
			tooltip: 'Horizontal line',
			onclick: function() {
				editor.windowManager.open( {
					title: 'Insert Horizontal Line',
					body: [
						{
							type: 'listbox',
							name: 'hr',
							label: 'Style',
							values: [
								{
									text: 'Plain',
									value: 'cce-divider--plain'
								},
								{
									text: 'Strong',
									value: 'cce-divider--strong'
								},
								{
									text: 'Double',
									value: 'cce-divider--double'
								},
								{
									text: 'Dashed',
									value: 'cce-divider--dashed'
								},
								{
									text: 'Dotted',
									value: 'cce-divider--dotted'
								}
							]
						}
					],
					onsubmit: function( e ) {
						editor.insertContent( '<hr class="cce-divider ' + e.data.hr + '" />');
					}
				});
			}
		});
	});
})(tinymce);
