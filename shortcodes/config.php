<?php
/**
 * Configuration data for CCE Shortcodes popup in the post-editor.
 *
 * @package    CC Essentials
 * @subpackage Shortcodes
 * @author     Jal Desai
 */

global $cce;

/* ===================================================================
/*	Content
/* =================================================================== */

/* Accordion toggle */

$cce_shortcodes['accordion'] = array(
	'no_preview' => true,
	'params' => array(
		'title' => array(
			'type'  => 'text',
			'label' => __( 'Toggle title', 'cc-essentials' ),
			'desc'  => __( 'Add a title for the toggle content.', 'cc-essentials' ),
			'std'   => 'Title',
		),
		'content' => array(
			'std'   => 'Content',
			'type'  => 'textarea',
			'label' => __( 'Toggle content', 'cc-essentials' ),
			'desc'  => __( 'Add the toggle content (HTML is allowed).', 'cc-essentials' ),
		),
		'state' => array(
			'type'    => 'select',
			'label'   => __( 'Initial state', 'cc-essentials' ),
			'desc'    => __( 'Choose whether the toggle is open or closed on page load.', 'cc-essentials' ),
			'options' => array(
				'open'   => __( 'Open', 'cc-essentials' ),
				'closed' => __( 'Closed', 'cc-essentials' )
			)
		),
	),
	'shortcode'   => '[cce_toggle title="{{title}}" state="{{state}}"]{{content}}[/cce_toggle]',
	'popup_title' => __( 'Accordion toggle settings', 'cc-essentials' )
);


/* Button */

$cce_shortcodes['button'] = array(
	'no_preview' => true,
	'params' => array(
		'content' => array(
			'std'   => 'Button',
			'type'  => 'text',
			'label' => __( 'Button text', 'cc-essentials' ),
			'desc'  => __( 'The text that appears on the button.', 'cc-essentials' ),
		),
		'url' => array(
			'std'   => '',
			'type'  => 'text',
			'label' => __( 'URL', 'cc-essentials' ),
			'desc'  => __( 'Link to navigate to upon clicking the button. For e.g. http://google.com', 'cc-essentials' )
		),
		'target' => array(
			'type'    => 'select',
			'label'   => __( 'Open in a new tab/window?', 'cc-essentials' ),
			'desc'    => __( 'Should the link be opened in a new window or tab?', 'cc-essentials' ),
			'std'     => '_self',
			'options' => array(
				'_self'  => __( 'No, open link in the same tab/window', 'cc-essentials' ),
				'_blank' => __( 'Yes, open link in a new tab/window', 'cc-essentials' )
			)
		),
		'color' => array(
			'type'    => 'select',
			'label'   => __( 'Color', 'cc-essentials' ),
			'desc'    => __( 'Pick a background color for the button.', 'cc-essentials' ),
			'std'     => 'Blue',
			'options' => array(
				'red'		=> __( 'Red', 'cc-essentials' ),
				'green'		=> __( 'Green', 'cc-essentials' ),
				'yellow'	=> __( 'Yellow', 'cc-essentials' ),
				'blue'		=> __( 'Blue', 'cc-essentials' ),
				'black'		=> __( 'Black', 'cc-essentials' ),
				'white'		=> __( 'White', 'cc-essentials' ),
				'gray'		=> __( 'Gray', 'cc-essentials' ),
				'primary'	=> __( 'As per theme', 'cc-essentials')
			)
		),
		'size' => array(
			'type'    => 'select',
			'label'   => __( 'Size', 'cc-essentials' ),
			'desc'    => __( 'Choose the size of the button.', 'cc-essentials' ),
			'std'     => 'medium',
			'options' => array(
				'small'  => __( 'Small', 'cc-essentials' ),
				'medium' => __( 'Medium', 'cc-essentials' ),
				'large'  => __( 'Large', 'cc-essentials' )
			)
		),
		'shape' => array(
			'type'    => 'select',
			'label'   => __( 'Shape', 'cc-essentials' ),
			'desc'    => __( 'Choose the shape of the button.', 'cc-essentials' ),
			'std'	  => __( 'rounded', 'cc-essentials'),
			'options' => array(
				'rounded'	=> __( 'Rounded', 'cc-essentials' ),
				'square'	=> __( 'Square', 'cc-essentials' ),
				'oval'		=> __( 'Oval', 'cc-essentials' )
			)
		),
		'icon' => array(
			'std'   => '',
			'type'  => 'icons',
			'label' => __( 'Icon', 'cc-essentials' ),
			'desc'  => __( 'Choose an icon (optional).', 'cc-essentials' )
		),
		'icon_order' => array(
			'type'    => 'select',
			'label'   => __( 'Icon placement', 'cc-essentials' ),
			'desc'    => __( 'Choose whether the icon be displayed before or after the button text.', 'cc-essentials' ),
			'std'     => 'before',
			'options' => array(
				'before' => __( 'Before text', 'cc-essentials' ),
				'after'  => __( 'After text', 'cc-essentials' )
			)
		),
		'alignment' => array(
			'type'    => 'select',
			'label'   => __( 'Alignment', 'cc-essentials' ),
			'desc'    => __( 'Choose the alignment of the button.', 'cc-essentials' ),
			'std'	  => __( 'none', 'cc-essentials'),
			'options' => array(
				'none'		=> __( 'None', 'cc-essentials' ),
				'left'		=> __( 'Left', 'cc-essentials' ),
				'right'		=> __( 'Right', 'cc-essentials' )
			)
		)
	),
	'shortcode'   => '[cce_button url="{{url}}" target="{{target}}" color="{{color}}" size="{{size}}"  shape="{{shape}}" icon="{{icon}}" icon_order="{{icon_order}}" alignment="{{alignment}}"]{{content}}[/cce_button]',
	'popup_title' => __( 'Button settings', 'cc-essentials' )
);


/* Icon box */

$cce_shortcodes['iconbox'] = array(
	'no_preview' => true,
	'params' => array(
		'icon' => array(
			'std'   => '',
			'type'  => 'icons',
			'label' => __( 'Icon', 'cc-essentials' ),
			'desc'  => __( 'Choose an icon.', 'cc-essentials' )
		),
		'icon_size' => array(
			'type'    => 'select',
			'label'   => __( 'Icon size', 'cc-essentials' ),
			'desc'    => __( 'Select the appropriate size of the icon.', 'cc-essentials' ),
			'std'     => '48px',
			'options' => array(
				'32px'  => __( '32px', 'cc-essentials' ),
				'48px'  => __( '48px', 'cc-essentials' ),
				'64px'  => __( '64px', 'cc-essentials' ),
				'72px'  => __( '72px', 'cc-essentials' ),
				'96px'  => __( '96px', 'cc-essentials' ),
				'128px' => __( '128px', 'cc-essentials' ),
				'164px' => __( '164px', 'cc-essentials' ),
				'192px' => __( '192px', 'cc-essentials' ),
			)
		),
		'icon_position' => array(
			'type'    => 'select',
			'label'   => __( 'Icon position', 'cc-essentials' ),
			'desc'    => __( 'Choose the position of the icon.', 'cc-essentials' ),
			'std'     => 'top',
			'options' => array(
				'left'=> __( 'Left', 'cc-essentials' ),
				'top' => __( 'Top', 'cc-essentials' )
			)
		),
		'title' => array(
			'std'   => '',
			'type'  => 'text',
			'label' => __( 'Title', 'cc-essentials' ),
			'desc'  => __( 'Give a title to the icon-box.', 'cc-essentials' )
		),
		'content' => array(
			'std'   => '',
			'type'  => 'textarea',
			'label' => __( 'Content', 'cc-essentials' ),
			'desc'  => __( 'Enter the content of the icon-box.', 'cc-essentials' )
		)
		
	),
	'shortcode'   => '[cce_iconbox icon="{{icon}}" icon_size="{{icon_size}}" icon_position="{{icon_position}}" title="{{title}}"]{{content}}[/cce_iconbox]',
	'popup_title' => __( 'Icon-box settings', 'cc-essentials' ),
);


/* Notification */

$cce_shortcodes['notification'] = array(
	'no_preview' => true,
	'params' => array(
		'type' => array(
			'type'    => 'select',
			'label'   => __( 'Notification type', 'cc-essentials' ),
			'desc'    => __( 'Choose the type of notification.', 'cc-essentials' ),
			'std'     => 'info',
			'options' => array(
				'info'		=> __( 'Info', 'cc-essentials' ),
				'success' 	=> __( 'Success', 'cc-essentials' ),
				'warning' 	=> __( 'Warning', 'cc-essentials' ),
				'danger' 	=> __( 'Error', 'cc-essentials' )
			)
		),
		'title' => array(
			'std'   => '',
			'type'  => 'text',
			'label' => __( 'Notification title (optional)', 'cc-essentials' ),
			'desc'  => __( 'The title of the notification message.', 'cc-essentials' )
		),
		'content' => array(
			'std'   => '',
			'type'  => 'textarea',
			'label' => __( 'Notification message', 'cc-essentials' ),
			'desc'  => __( 'The content of the notification message.', 'cc-essentials' )
		),
		'dismissible' => array(
			'type'    => 'select',
			'label'   => __( 'Show close button?', 'cc-essentials' ),
			'desc'    => __( 'Should the notification box show the close (X) button?', 'cc-essentials' ),
			'std'     => 'no',
			'options' => array(
				'no'  => __( 'No', 'cc-essentials' ),
				'yes' => __( 'Yes', 'cc-essentials' )
			)
		)		
	),
	'shortcode'   => '[cce_notification type="{{type}}" title="{{title}}" dismissible="{{dismissible}}"]{{content}}[/cce_notification]',
	'popup_title' => __( 'Notification settings', 'cc-essentials' ),
);


/* Tabbed content */

$cce_shortcodes['tabs'] = array(
	'no_preview'  => true,
	'params' => array(
		'position' => array(
			'type'    => 'select',
			'label'   => __( 'Position of tabs', 'cc-essentials' ),
			'desc'    => __( 'Select the placement direction of the tabs.', 'cc-essentials' ),
			'std'	  => __( 'top', 'cc-essentials' ),
			'options' => array(
				'top' => __( 'Top', 'cc-essentials' ),
				'left' => __( 'Left', 'cc-essentials' )
			)
		)
	),
	'child_shortcode' => array(
		'params' => array(
			'title' => array(
				'std'   => 'Title',
				'type'  => 'text',
				'label' => __( 'Tab title', 'cc-essentials' ),
				'desc'  => __( 'Set a title of this tab.', 'cc-essentials' ),
			),
			'content' => array(
				'std'     => 'Tab content',
				'type'    => 'textarea',
				'label'   => __( 'Tab Content', 'cc-essentials' ),
				'desc'    => __( 'Add content to this tab.', 'cc-essentials' )
			)
		),
		'shortcode'    => '[cce_tab title="{{title}}"]{{content}}[/cce_tab]',
		'clone_button' => __( 'Add a tab', 'cc-essentials' )
	),
	'shortcode'   => '[cce_tabs position="{{position}}"]{{child_shortcode}}[/cce_tabs]',
	'popup_title' => __( 'Tabbed content settings', 'cc-essentials' )
);


/* Testimonial / quote */

$cce_shortcodes['testimonial'] = array(
	'no_preview' => true,
	'params' => array(
		'name' => array(
			'std'   => '',
			'type'  => 'text',
			'label' => __( 'Author’s name', 'cc-essentials' ),
			'desc'  => __( 'Enter the author&rsquo;s name.', 'cc-essentials' )
		),
		'content' => array(
			'std'   => '',
			'type'  => 'textarea',
			'label' => __( 'Testimonial / quote', 'cc-essentials' ),
			'desc'  => __( 'The actual testimonial/quotation.', 'cc-essentials' )
		),
		'image' => array(
			'std'   => '',
			'type'  => 'image',
			'label' => __( 'Author’s image (optional)', 'cc-essentials' ),
			'desc'  => __( 'Link to the JPG/PNG image file.', 'cc-essentials' )
		),
		'subtitle' => array(
			'std'   => '',
			'type'  => 'text',
			'label' => __( 'Subtitle (optional)', 'cc-essentials' ),
			'desc'  => __( 'A small description about the author. For eg. Founder & CEO, Google.', 'cc-essentials' )
		),
		'url' => array(
			'std'   => '',
			'type'  => 'text',
			'label' => __( 'URL (optional)', 'cc-essentials' ),
			'desc'  => __( 'Link to author&rsquo;s site.', 'cc-essentials' )
		)		
	),
	'shortcode'   => '[cce_testimonial name="{{name}}" image="{{image}}" subtitle="{{subtitle}}" url="{{url}}"]{{content}}[/cce_testimonial]',
	'popup_title' => __( 'Testimonial / quote settings', 'cc-essentials' ),
);


/* ===================================================================
/*	Layout columns
/* =================================================================== */

$cce_shortcodes['layout'] = array(
	'params'      => array(),
	'no_preview'  => true,
	'child_shortcode' => array(
		'params' => array(
			'column' => array(
				'type'    => 'select',
				'label'   => __( 'Column type', 'cc-essentials' ),
				'desc'    => __( 'Choose the type of the column based on its width.', 'cc-essentials' ),
				'options' => array(
					'cce_one_third'         => __( 'One Third', 'cc-essentials' ),
					'cce_one_third_last'    => __( 'One Third Last', 'cc-essentials' ),
					'cce_two_third'         => __( 'Two Thirds', 'cc-essentials' ),
					'cce_two_third_last'    => __( 'Two Thirds Last', 'cc-essentials' ),
					'cce_one_half'          => __( 'One Half', 'cc-essentials' ),
					'cce_one_half_last'     => __( 'One Half Last', 'cc-essentials' ),
					'cce_one_fourth'        => __( 'One Fourth', 'cc-essentials' ),
					'cce_one_fourth_last'   => __( 'One Fourth Last', 'cc-essentials' ),
					'cce_three_fourth'      => __( 'Three Fourth', 'cc-essentials' ),
					'cce_three_fourth_last' => __( 'Three Fourth Last', 'cc-essentials' )
				)
			),
			'content' => array(
				'std'   => '',
				'type'  => 'textarea',
				'label' => __( 'Column content', 'cc-essentials' ),
				'desc'  => __( 'Add content for this column.', 'cc-essentials' ),
			)
		),
		'shortcode'    => '[{{column}}]{{content}}[/{{column}}] ',
		'clone_button' => __( 'Add column', 'cc-essentials' )
	),
	'shortcode'   => '[cce_columns]{{child_shortcode}}[/cce_columns]', // as there is no wrapper shortcode
	'popup_title' => __( 'Layout columns', 'cc-essentials' ),
);


/* ===================================================================
/*	Typography related
/* =================================================================== */

/* Dropcap */

$cce_shortcodes['dropcap'] = array(
	'no_preview' => true,
	'params' => array(
		'content' => array(
			'std'   => 'A',
			'type'  => 'text',
			'label' => __( 'Dropcap letter', 'cc-essentials' ),
			'desc'  => __( 'Specify the letter to be dropcapped. Usually this is the first letter of a paragraph.', 'cc-essentials' )
		),
		'bgcolor' => array(
			'std'	  => '',
			'type'    => 'color',
			'label'   => __( 'Background color', 'cc-essentials' ),
			'desc'    => __( 'Select the background color for the dropcap letter.', 'cc-essentials' )
		),
		'color' => array(
			'std'	  => '',
			'type'    => 'color',
			'label'   => __( 'Text color', 'cc-essentials' ),
			'desc'    => __( 'Select the foreground (text) color for the dropcap letter.', 'cc-essentials' )
		),
		'shape' => array(
			'type'    => 'select',
			'label'   => __( 'Shape', 'cc-essentials' ),
			'desc'    => __( 'Select the shape of the background.', 'cc-essentials' ),
			'std'	  => 'round',
			'options' => array(
				'round'	=> __( 'Round', 'cc-essentials' ),
				'square'	=> __( 'Square', 'cc-essentials' )
			)
		),
		'size' => array(
			'type'  => 'select',
			'label' => __( 'Font size', 'cc-essentials' ),
			'desc'  => __( 'Choose a font-size for the dropcap letter.', 'cc-essentials' ),
			'std'   => '3rem',
			'options' => array(
				'2rem'	=> __( 'Big', 'cc-essentials' ),
				'3rem'	=> __( 'Large', 'cc-essentials' ),
				'4.5rem'=> __( 'Huge', 'cc-essentials' ),
				'6.2rem'=> __( 'Massive', 'cc-essentials' ),
				'9rem'	=> __( 'Are you crazy? :O', 'cc-essentials' )
			)
		),
	),
	'shortcode'   => '[cce_dropcap bgcolor="{{bgcolor}}" color="{{color}}" shape="{{shape}}" size="{{size}}"]{{content}}[/cce_dropcap]',
	'popup_title' => __( 'Dropcap settings', 'cc-essentials' )
);


/* Lead paragraph */

$cce_shortcodes['leadparagraph'] = array(
	'no_preview' => true,
	'params' => array(
		'content' => array(
			'std'   => '',
			'type'  => 'textarea',
			'label' => __( 'Paragraph content', 'cc-essentials' ),
			'desc'  => __( 'Write the content of the paragraph here.', 'cc-essentials' )
		),
		'size' => array(
			'type'    => 'select',
			'label'   => __( 'Font size', 'cc-essentials' ),
			'desc'    => __( 'Select how large the font-size of this paragraph should be compared to the rest of the content.', 'cc-essentials' ),
			'std'	  => '160%',
			'options' => array(
				'125%'	=> __( '25% larger', 'cc-essentials' ),
				'150%'	=> __( '50% larger', 'cc-essentials' ),
				'160%'	=> __( '60% larger', 'cc-essentials' ),
				'175%'	=> __( '75% larger', 'cc-essentials' ),
				'200%'	=> __( '100% larger', 'cc-essentials' )
			)
		),
		'color' => array(
			'std'	  => '',
			'type'    => 'color',
			'label'   => __( 'Text color (optional)', 'cc-essentials' ),
			'desc'    => __( 'Select the text-color for the paragraph.', 'cc-essentials' )
		)		
	),
	'shortcode'   => '[cce_leadparagraph size="{{size}}" color="{{color}}"]{{content}}[/cce_leadparagraph]',
	'popup_title' => __( 'Lead paragraph settings', 'cc-essentials' )
);

/* Label */

$cce_shortcodes['label'] = array(
	'no_preview' => true,
	'params' => array(
		'content' => array(
			'std'   => '',
			'type'  => 'text',
			'label' => __( 'Label text', 'cc-essentials' ),
			'desc'  => __( 'Enter text to be shown as label.', 'cc-essentials' )
		),
		'color' => array(
			'type'    => 'select',
			'label'   => __( 'Color', 'cc-essentials' ),
			'desc'    => __( 'Choose the color of the label.', 'cc-essentials' ),
			'std'	  => 'blue',
			'options' => array(
				'red'		=> __( 'Red', 'cc-essentials' ),
				'green'		=> __( 'Green', 'cc-essentials' ),
				'yellow'	=> __( 'Yellow', 'cc-essentials' ),
				'black'		=> __( 'Black', 'cc-essentials' ),
				'blue'		=> __( 'Blue', 'cc-essentials' ),
				'gray'		=> __( 'Gray', 'cc-essentials' )
			)
		),
		'shape' => array(
			'type'    => 'select',
			'label'   => __( 'Shape', 'cc-essentials' ),
			'desc'    => __( 'Choose the shape of the label.', 'cc-essentials' ),
			'std'	  => __( 'rounded', 'cc-essentials'),
			'options' => array(
				'rounded'	=> __( 'Rounded', 'cc-essentials' ),
				'square'	=> __( 'Square', 'cc-essentials' ),
				'oval'		=> __( 'Oval', 'cc-essentials' )
			)
		)		
	),
	'shortcode'   => '[cce_label shape="{{shape}}" color="{{color}}"]{{content}}[/cce_label]',
	'popup_title' => __( 'Label settings', 'cc-essentials' )
);


/* ===================================================================
/*	Misc. elements
/* =================================================================== */

/* Image shortcode */

$cce_shortcodes['image'] = array(
	'no_preview' => true,
	'params' => array(
		'src' => array(
			'std'   => '',
			'type'  => 'image',
			'label' => __( 'Image', 'cc-essentials' ),
			'desc'  => __( 'Choose an image.', 'cc-essentials' )
		),
		'effect' => array(
			'type'    => 'select',
			'label'   => __( 'Effect', 'cc-essentials' ),
			'desc'    => __( 'This uses CSS3 filters which may not work on older browsers.', 'cc-essentials' ),
			'std'     => 'no-filter',
			'options' => array(
				'no-filter'  => __( 'No filter', 'cc-essentials' ),
				'grayscale'  => __( 'Grayscale', 'cc-essentials' ),
				'sepia'      => __( 'Sepia', 'cc-essentials' ),
				'blur'       => __( 'Blur', 'cc-essentials' ),
				'hue-rotate' => __( 'Hue Rotate', 'cc-essentials' ),
				'contrast'   => __( 'Contrast', 'cc-essentials' ),
				'brightness' => __( 'Brightness', 'cc-essentials' ),
				'invert'     => __( 'Invert', 'cc-essentials' ),
			)
		),
		'alignment' => array(
			'type'    => 'select',
			'label'   => __( 'Alignment', 'cc-essentials' ),
			'desc'    => __( 'Choose the alignment of the image.', 'cc-essentials' ),
			'std'     => 'none',
			'options' => array(
				'none'   => __( 'None', 'cc-essentials' ),
				'left'   => __( 'Left', 'cc-essentials' ),
				'center' => __( 'Center', 'cc-essentials' ),
				'right'  => __( 'Right', 'cc-essentials' )
			)
		),
		'url' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'URL (Optional)', 'cc-essentials' ),
			'desc' => __( 'Link to navigate to upon clicking the image. For e.g. http://google.com', 'cc-essentials' )
		)
	),
	'shortcode'   => '[cce_image src="{{src}}" effect="{{effect}}" alignment="{{alignment}}" url="{{url}}"]',
	'popup_title' => __( 'Insert an image', 'cc-essentials' )
);


/* Video shortcode */

$cce_shortcodes['video'] = array(
	'no_preview' => true,
	'params' => array(
		'src' => array(
			'std'   => '',
			'type'  => 'video',
			'label' => __( 'Video', 'cc-essentials' ),
			'desc'  => sprintf( __( 'Upload a video or choose an existing video from the Media Library. You can also paste a video link from sites like Youtube, Vimeo, TED, etc. Full list of supported sites can be found <a target="_blank" href="%1$s">here</a>.', 'cc-essentials' ), esc_url( 'http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F' ) )
		)
	),
	'shortcode' => '[cce_video src="{{src}}"]',
	'popup_title' => __( 'Insert a video', 'cc-essentials' )
);


/* Horizontal divider */

$cce_shortcodes['hdivider'] = array(
	'no_preview' => true,
	'params' => array(
		'type' => array(
			'type'    => 'select',
			'label'   => __( 'Type', 'cc-essentials' ),
			'desc'    => __( 'Select the type of the horizontal divider.', 'cc-essentials' ),
			'std'	  => __( 'single', 'cc-essentials'),
			'options' => array(
				'single'		=> __( 'Single', 'cc-essentials' ),
				'double'		=> __( 'Double', 'cc-essentials' ),
				'single-dashed'	=> __( 'Single-dashed', 'cc-essentials' ),
				'double-dashed'	=> __( 'Double-dashed', 'cc-essentials' ),
				'single-dotted'	=> __( 'Single-dotted', 'cc-essentials' ),
				'double-dotted'	=> __( 'Double-dotted', 'cc-essentials' )
			)
		),
		'length' => array(
			'type'    => 'select',
			'label'   => __( 'Length', 'cc-essentials' ),
			'desc'    => __( 'Select the length of the horizontal divider.', 'cc-essentials' ),
			'std'	  => __( 'long', 'cc-essentials'),
			'options' => array(
				'long'	=> __( 'Long', 'cc-essentials' ),
				'short'	=> __( 'Short', 'cc-essentials' )
			)
		)
	),
	'shortcode' => '[cce_hdivider type="{{type}}" length="{{length}}"]',
	'popup_title' => __( 'Horizontal divider settings', 'cc-essentials' )
);

/* Google map shortcode */

$cce_shortcodes['map'] = array(
	'no_preview' => true,
	'params' => array(
		'latlong' => array(
			'std'   => '',
			'type'  => 'map',
			'label' => __( 'Latitude / Longitude', 'cc-essentials' ),
			'desc'  => sprintf(__( 'Drag and drop the map marker to select the exact location. You&#8217;ll need a Google Maps API key for maps to work. %sGet your free API key%s and %senter it here%s.', 'cc-essentials' ), '<a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">', '</a>', '<a href="admin.php?page=cce&tab=maps">', '</a>')
		),
		'width' => array(
			'std'   => '100%',
			'type'  => 'text',
			'label' => __( 'Width', 'cc-essentials' ),
			'desc'  => __( 'Enter the width of the map in px, em or %. Default is <i>100%</i>.', 'cc-essentials' )
		),
		'height' => array(
			'std'   => '350px',
			'type'  => 'text',
			'label' => __( 'Height', 'cc-essentials' ),
			'desc'  => __( 'Enter the height of the map in px, em or %. Default is <i>350px</i>.', 'cc-essentials' )
		),
		'zoom' => array(
			'std'   => '15',
			'type'  => 'text',
			'label' => __( 'Zoom level', 'cc-essentials' ),
			'desc'  => __( 'Enter the map zoom level between 0-21. Default is <i>15</i>.', 'cc-essentials' )
		),
		'style' => array(
			'std'     => 'none',
			'type'    => 'select',
			'label'   => __( 'Map style', 'cc-essentials' ),
			'desc'    => __( 'Choose a style for the map.', 'cc-essentials' ),
			'options' => array(
				'none'             => __( 'None', 'cc-essentials' ),
				'pale_dawn'        => __( 'Pale Dawn', 'cc-essentials' ),
				'subtle_grayscale' => __( 'Subtle Grayscale', 'cc-essentials' ),
				'bright_bubbly'    => __( 'Bright & Bubbly', 'cc-essentials' ),
				'greyscale'        => __( 'Grayscale', 'cc-essentials' ),
				'mixed'            => __( 'Mixed', 'cc-essentials' )
			)
		),
	),
	'shortcode'   => '[cce_map location="{{latlong}}" width="{{width}}" height="{{height}}" zoom="{{zoom}}" style="{{style}}"]',
	'popup_title' => __( 'Insert a Google map', 'cc-essentials' )
);