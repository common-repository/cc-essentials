/**
 * Table of Contents
 *
 * 1. jQuery Live Query
 * 2. jQuery Appendo
 * 3. base64.js
 * 4. custom
 *
 */

// 1. jQuery Live Query
(function(a){a.extend(a.fn,{livequery:function(e,d,c){var b=this,f;if(a.isFunction(e)){c=d,d=e,e=undefined}a.each(a.livequery.queries,function(g,h){if(b.selector==h.selector&&b.context==h.context&&e==h.type&&(!d||d.$lqguid==h.fn.$lqguid)&&(!c||c.$lqguid==h.fn2.$lqguid)){return(f=h)&&false}});f=f||new a.livequery(this.selector,this.context,e,d,c);f.stopped=false;f.run();return this},expire:function(e,d,c){var b=this;if(a.isFunction(e)){c=d,d=e,e=undefined}a.each(a.livequery.queries,function(f,g){if(b.selector==g.selector&&b.context==g.context&&(!e||e==g.type)&&(!d||d.$lqguid==g.fn.$lqguid)&&(!c||c.$lqguid==g.fn2.$lqguid)&&!this.stopped){a.livequery.stop(g.id)}});return this}});a.livequery=function(b,d,f,e,c){this.selector=b;this.context=d;this.type=f;this.fn=e;this.fn2=c;this.elements=[];this.stopped=false;this.id=a.livequery.queries.push(this)-1;e.$lqguid=e.$lqguid||a.livequery.guid++;if(c){c.$lqguid=c.$lqguid||a.livequery.guid++}return this};a.livequery.prototype={stop:function(){var b=this;if(this.type){this.elements.unbind(this.type,this.fn)}else{if(this.fn2){this.elements.each(function(c,d){b.fn2.apply(d)})}}this.elements=[];this.stopped=true},run:function(){if(this.stopped){return}var d=this;var e=this.elements,c=a(this.selector,this.context),b=c.not(e);this.elements=c;if(this.type){b.bind(this.type,this.fn);if(e.length>0){a.each(e,function(f,g){if(a.inArray(g,c)<0){a.event.remove(g,d.type,d.fn)}})}}else{b.each(function(){d.fn.apply(this)});if(this.fn2&&e.length>0){a.each(e,function(f,g){if(a.inArray(g,c)<0){d.fn2.apply(g)}})}}}};a.extend(a.livequery,{guid:0,queries:[],queue:[],running:false,timeout:null,checkQueue:function(){if(a.livequery.running&&a.livequery.queue.length){var b=a.livequery.queue.length;while(b--){a.livequery.queries[a.livequery.queue.shift()].run()}}},pause:function(){a.livequery.running=false},play:function(){a.livequery.running=true;a.livequery.run()},registerPlugin:function(){a.each(arguments,function(c,d){if(!a.fn[d]){return}var b=a.fn[d];a.fn[d]=function(){var e=b.apply(this,arguments);a.livequery.run();return e}})},run:function(b){if(b!=undefined){if(a.inArray(b,a.livequery.queue)<0){a.livequery.queue.push(b)}}else{a.each(a.livequery.queries,function(c){if(a.inArray(c,a.livequery.queue)<0){a.livequery.queue.push(c)}})}if(a.livequery.timeout){clearTimeout(a.livequery.timeout)}a.livequery.timeout=setTimeout(a.livequery.checkQueue,20)},stop:function(b){if(b!=undefined){a.livequery.queries[b].stop()}else{a.each(a.livequery.queries,function(c){a.livequery.queries[c].stop()})}}});a.livequery.registerPlugin("append","prepend","after","before","wrap","attr","removeAttr","addClass","removeClass","toggleClass","empty","remove","html");a(function(){a.livequery.play()})})(jQuery);

// 2. jQuery.appendo
/**
 * Appendo Plugin for jQuery v1.01
 * Creates interface to create duplicate clones of last table row (usually for forms)
 * (c) 2008 Kelly Hallman. Free software released under MIT License.
 * See http://deepliquid.com/content/Appendo.html for more info
 */

// Attach appendo as a jQuery plugin
jQuery.fn.appendo = function(opt) {
    this.each(function() { jQuery.appendo.init(this,opt); });
    return this;
};

// appendo namespace
jQuery.appendo = function() {

    // Create a closure so that we can refer to "this" correctly down the line
    var myself = this;

    // Global Options
    // These can be set with inline Javascript like so:
    // jQuery.appendo.opt.maxRows = 5;
    // $.appendo.opt.allowDelete = false;
    // (no need, in fact you shouldn't, wrap in jQuery(document).ready() etc)
    this.opt = { };

    this.init = function(obj,opt) {

        // Extend the defaults with global options and options given, if any
        var options = jQuery.extend({
                labelAdd:       'Add Row',
                labelDel:       'Remove',
                allowDelete:    true,
                // copyHandlers does not seem to work
                // it's been removed from the docs for now...
                copyHandlers:   false,
                focusFirst:     true,
                onAdd:          function() { return true; },
                onDel:      function() { return true; },
                maxRows:        0,
                wrapClass:      'appendoButtons',
                wrapStyle:      { padding: '.4em .2em .5em' },
                buttonStyle:    { marginRight: '.5em' },
                subSelect:      'tr:last'
            },
            myself.opt,
            opt
        );

        // Store clone of last table row
        var $cpy = jQuery(obj).find(options.subSelect).clone(options.copyHandlers);
        // We consider this starting off with 1 row
        var rows = 1;
        // Create two button objects
        var $add_btn = jQuery('#form-child-add').click(clicked_add),
            $del_btn = new_button(options.labelDel).click(clicked_del).hide()
        ;

        // Append a row to table instance
        function add_row() {
            var $dup = $cpy.clone(options.copyHandlers);
            $dup.appendTo(obj);
            update_buttons(1);
            if (typeof(options.onAdd) == "function") options.onAdd($dup);
            if (!!options.focusFirst) $dup.find('input:first').focus();
        };

        // Remove last row from table instance
        function del_row() {
            var $row = jQuery(obj).find(options.subSelect);
            if ((typeof(options.onDel) != "function") || options.onDel($row))
            {
                $row.remove();
                update_buttons(-1);
            }
        };

        // Updates the button states after rows change
        function update_buttons(rowdelta) {
            // Update rows if a delta is provided
            rows = rows + (rowdelta || 0);
            // Disable the add button if maxRows is set and we have that many rows
            // $add_btn.attr('disabled',(!options.maxRows || (rows < options.maxRows))?false:true);
            // Show remove button if we've added rows and allowDelete is set
            (options.allowDelete && (rows > 1))? $del_btn.show(): $del_btn.hide();
        };

        // Returns (jQuery) button objects with label
        function new_button(label) {
            return jQuery('<button />')
                .css(options.buttonStyle)
                .html(label);
        };

        // This function can be returned to kill a received event
        function nothing(e) {
            e.stopPropagation();
            e.preventDefault();
            return false;
        };

        // Handles a click on the add button
        function clicked_add(e) {
            if (!options.maxRows || (rows < options.maxRows)) add_row();
            return nothing(e);
        };

        // Handles a click event on the remove button
        function clicked_del(e) {
            if (rows > 1) del_row();
            return nothing(e);
        };

        // Update the buttons
        update_buttons();

    };
    return this;
}();

// 3. base64.js
function base64_decode(h){var d="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";var c,b,a,m,l,k,j,n,g=0,o=0,e="",f=[];if(!h){return h}h+="";do{m=d.indexOf(h.charAt(g++));l=d.indexOf(h.charAt(g++));k=d.indexOf(h.charAt(g++));j=d.indexOf(h.charAt(g++));n=m<<18|l<<12|k<<6|j;c=n>>16&255;b=n>>8&255;a=n&255;if(k==64){f[o++]=String.fromCharCode(c)}else{if(j==64){f[o++]=String.fromCharCode(c,b)}else{f[o++]=String.fromCharCode(c,b,a)}}}while(g<h.length);e=f.join("");e=this.utf8_decode(e);return e}function base64_encode(h){var d="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";var c,b,a,m,l,k,j,n,g=0,o=0,f="",e=[];if(!h){return h}h=this.utf8_encode(h+"");do{c=h.charCodeAt(g++);b=h.charCodeAt(g++);a=h.charCodeAt(g++);n=c<<16|b<<8|a;m=n>>18&63;l=n>>12&63;k=n>>6&63;j=n&63;e[o++]=d.charAt(m)+d.charAt(l)+d.charAt(k)+d.charAt(j)}while(g<h.length);f=e.join("");switch(h.length%3){case 1:f=f.slice(0,-2)+"==";break;case 2:f=f.slice(0,-1)+"=";break}return f}function utf8_decode(a){var c=[],e=0,g=0,f=0,d=0,b=0;a+="";while(e<a.length){f=a.charCodeAt(e);if(f<128){c[g++]=String.fromCharCode(f);e++}else{if(f>191&&f<224){d=a.charCodeAt(e+1);c[g++]=String.fromCharCode(((f&31)<<6)|(d&63));e+=2}else{d=a.charCodeAt(e+1);b=a.charCodeAt(e+2);c[g++]=String.fromCharCode(((f&15)<<12)|((d&63)<<6)|(b&63));e+=3}}}return c.join("")}function utf8_encode(a){var h=(a+"");var i="",b,e,c=0;b=e=0;c=h.length;for(var d=0;d<c;d++){var g=h.charCodeAt(d);var f=null;if(g<128){e++}else{if(g>127&&g<2048){f=String.fromCharCode((g>>6)|192)+String.fromCharCode((g&63)|128)}else{f=String.fromCharCode((g>>12)|224)+String.fromCharCode(((g>>6)&63)|128)+String.fromCharCode((g&63)|128)}}if(f!==null){if(e>b){i+=h.slice(b,e)}i+=f;b=e=d+1}}if(e>b){i+=h.slice(b,c)}return i};

// 4. location-picker http://logicify.github.io/jquery-locationpicker-plugin/
// Pre-requisite: http://maps.google.com/maps/api/js?sensor=false&libraries=places
!function(t){function n(t,n){var o=new google.maps.Map(t,n),e=new google.maps.Marker({position:new google.maps.LatLng(54.19335,-3.92695),map:o,title:"Drag Me",draggable:n.draggable,icon:void 0!==n.markerIcon?n.markerIcon:void 0});return{map:o,marker:e,circle:null,location:e.position,radius:n.radius,locationName:n.locationName,addressComponents:{formatted_address:null,addressLine1:null,addressLine2:null,streetName:null,streetNumber:null,city:null,district:null,state:null,stateOrProvince:null},settings:n.settings,domContainer:t,geodecoder:new google.maps.Geocoder}}function o(t){return void 0!=e(t)}function e(n){return t(n).data("locationpicker")}function i(t,n){if(t){var o=s.locationFromLatLng(n.location);t.latitudeInput&&t.latitudeInput.val(o.latitude).change(),t.longitudeInput&&t.longitudeInput.val(o.longitude).change(),t.radiusInput&&t.radiusInput.val(n.radius).change(),t.locationNameInput&&t.locationNameInput.val(n.locationName).change()}}function a(n,o){if(n){if(n.radiusInput&&n.radiusInput.on("change",function(n){n.originalEvent&&(o.radius=t(this).val(),s.setPosition(o,o.location,function(t){t.settings.onchanged.apply(o.domContainer,[s.locationFromLatLng(t.location),t.radius,!1])}))}),n.locationNameInput&&o.settings.enableAutocomplete){var e=!1;o.autocomplete=new google.maps.places.Autocomplete(n.locationNameInput.get(0)),google.maps.event.addListener(o.autocomplete,"place_changed",function(){e=!1;var t=o.autocomplete.getPlace();return t.geometry?void s.setPosition(o,t.geometry.location,function(t){i(n,t),t.settings.onchanged.apply(o.domContainer,[s.locationFromLatLng(t.location),t.radius,!1])}):void o.settings.onlocationnotfound(t.name)}),o.settings.enableAutocompleteBlur&&(n.locationNameInput.on("change",function(t){t.originalEvent&&(e=!0)}),n.locationNameInput.on("blur",function(a){a.originalEvent&&setTimeout(function(){var a=t(n.locationNameInput).val();a.length>5&&e&&(e=!1,o.geodecoder.geocode({address:a},function(t,e){e==google.maps.GeocoderStatus.OK&&t&&t.length&&s.setPosition(o,t[0].geometry.location,function(t){i(n,t),t.settings.onchanged.apply(o.domContainer,[s.locationFromLatLng(t.location),t.radius,!1])})}))},1e3)}))}n.latitudeInput&&n.latitudeInput.on("change",function(n){n.originalEvent&&s.setPosition(o,new google.maps.LatLng(t(this).val(),o.location.lng()),function(t){t.settings.onchanged.apply(o.domContainer,[s.locationFromLatLng(t.location),t.radius,!1])})}),n.longitudeInput&&n.longitudeInput.on("change",function(n){n.originalEvent&&s.setPosition(o,new google.maps.LatLng(o.location.lat(),t(this).val()),function(t){t.settings.onchanged.apply(o.domContainer,[s.locationFromLatLng(t.location),t.radius,!1])})})}}function l(t){google.maps.event.trigger(t.map,"resize"),setTimeout(function(){t.map.setCenter(t.marker.position)},300)}function r(n,o,e){var i=t.extend({},t.fn.locationpicker.defaults,e),l=i.location.latitude,r=i.location.longitude,c=i.radius,u=n.settings.location.latitude,d=n.settings.location.longitude,g=n.settings.radius;(l!=u||r!=d||c!=g)&&(n.settings.location.latitude=l,n.settings.location.longitude=r,n.radius=c,s.setPosition(n,new google.maps.LatLng(n.settings.location.latitude,n.settings.location.longitude),function(t){a(n.settings.inputBinding,n),t.settings.oninitialized(o)}))}var s={drawCircle:function(n,o,e,i){return null!=n.circle&&n.circle.setMap(null),e>0?(e*=1,i=t.extend({strokeColor:"#0000FF",strokeOpacity:.35,strokeWeight:2,fillColor:"#0000FF",fillOpacity:.2},i),i.map=n.map,i.radius=e,i.center=o,n.circle=new google.maps.Circle(i),n.circle):null},setPosition:function(t,n,o){t.location=n,t.marker.setPosition(n),t.map.panTo(n),this.drawCircle(t,n,t.radius,{}),t.settings.enableReverseGeocode?t.geodecoder.geocode({latLng:t.location},function(n,e){e==google.maps.GeocoderStatus.OK&&n.length>0&&(t.locationName=n[0].formatted_address,t.addressComponents=s.address_component_from_google_geocode(n[0].address_components)),o&&o.call(this,t)}):o&&o.call(this,t)},locationFromLatLng:function(t){return{latitude:t.lat(),longitude:t.lng()}},address_component_from_google_geocode:function(t){for(var n={},o=t.length-1;o>=0;o--){var e=t[o];e.types.indexOf("postal_code")>=0?n.postalCode=e.short_name:e.types.indexOf("street_number")>=0?n.streetNumber=e.short_name:e.types.indexOf("route")>=0?n.streetName=e.short_name:e.types.indexOf("locality")>=0?n.city=e.short_name:e.types.indexOf("sublocality")>=0?n.district=e.short_name:e.types.indexOf("administrative_area_level_1")>=0?n.stateOrProvince=e.short_name:e.types.indexOf("country")>=0&&(n.country=e.short_name)}return n.addressLine1=[n.streetNumber,n.streetName].join(" ").trim(),n.addressLine2="",n}};t.fn.locationpicker=function(c,u){if("string"==typeof c){var d=this.get(0);if(!o(d))return;var g=e(d);switch(c){case"location":if(void 0==u){var m=s.locationFromLatLng(g.location);return m.radius=g.radius,m.name=g.locationName,m}u.radius&&(g.radius=u.radius),s.setPosition(g,new google.maps.LatLng(u.latitude,u.longitude),function(t){i(t.settings.inputBinding,t)});break;case"subscribe":if(void 0==u)return null;var p=u.event,f=u.callback;if(!p||!f)return console.error('LocationPicker: Invalid arguments for method "subscribe"'),null;google.maps.event.addListener(g.map,p,f);break;case"map":if(void 0==u){var v=s.locationFromLatLng(g.location);return v.formattedAddress=g.locationName,v.addressComponents=g.addressComponents,{map:g.map,marker:g.marker,location:v}}return null;case"autosize":return l(g),this}return null}return this.each(function(){var l=t(this);if(o(this))return void r(e(this),t(this),c);var u=t.extend({},t.fn.locationpicker.defaults,c),d=new n(this,{zoom:u.zoom,center:new google.maps.LatLng(u.location.latitude,u.location.longitude),mapTypeId:google.maps.MapTypeId.ROADMAP,mapTypeControl:!1,disableDoubleClickZoom:!1,scrollwheel:u.scrollwheel,streetViewControl:!1,radius:u.radius,locationName:u.locationName,settings:u,draggable:u.draggable,markerIcon:u.markerIcon});l.data("locationpicker",d),google.maps.event.addListener(d.marker,"dragend",function(){s.setPosition(d,d.marker.position,function(t){var n=s.locationFromLatLng(d.location);t.settings.onchanged.apply(d.domContainer,[n,t.radius,!0]),i(d.settings.inputBinding,d)})}),s.setPosition(d,new google.maps.LatLng(u.location.latitude,u.location.longitude),function(t){i(u.inputBinding,d),a(u.inputBinding,d),t.settings.oninitialized(l)})})},t.fn.locationpicker.defaults={location:{latitude:40.778462,longitude:-73.968201},locationName:"",radius:500,zoom:15,scrollwheel:!0,inputBinding:{latitudeInput:null,longitudeInput:null,radiusInput:null,locationNameInput:null},enableAutocomplete:!1,enableAutocompleteBlur:!1,enableReverseGeocode:!0,draggable:!0,onchanged:function(){},onlocationnotfound:function(){},oninitialized:function(){},markerIcon:void 0}}(jQuery);

// 5. Custom
var FontAwesomeIcons;

( function( $ ) {
    'use strict';

    FontAwesomeIcons = function() {
        var html = '';

        $.each( stIconObj['fontawesome'], function( cat, icons ) {
            html += '<span class="icon-category">' + cat + '</span>';

            icons.map(function(icon){
              html += '<i class="fa ' + icon.id + '" data-icon-id="' + icon.id.replace( 'fa-', '' ) + '" title="' + icon.name + '"></i>';
            });
        });

        return html;
    };

})( jQuery );

jQuery(document).ready(function($) {
	
    var cces = {
        loadVals: function() {
            var shortcode = $('#_cce_shortcode').text(),
                uShortcode = shortcode;

            // prepare shortcode with new parameters
            $('.cce-input').each(function() {
                var input = $(this),
                    id = input.attr('id'),
                    id = id.replace('cce_', ''),       // gets rid of the cce_ prefix
                    re = new RegExp("{{"+id+"}}","g");

                uShortcode = uShortcode.replace(re, input.val());
            });

            // adds the filled-in shortcode as hidden input
            $('#_cce_ushortcode').remove();
            $('#cce-sc-form > ul').prepend('<div id="_cce_ushortcode" class="hidden">' + uShortcode + '</div>');
        },

        cLoadVals: function() {
            var shortcode = $('#_cce_cshortcode').text(),
                pShortcode = '';
                shortcodes = '';

            // prepare shortcode with new parameters
            $('.child-clone-row').each(function() {
                var row = $(this),
                    rShortcode = shortcode;

                $('.cce-cinput', this).each(function() {
                    var input = $(this),
                        id = input.attr('id'),
                        id = id.replace('cce_', '')        // gets rid of the cce_ prefix
                        re = new RegExp("{{"+id+"}}","g");

                    rShortcode = rShortcode.replace(re, input.val());
                });

                shortcodes = shortcodes + rShortcode + "\n";
            });

            // adds the filled-in shortcode as hidden input
            $('#_cce_cshortcodes').remove();
            $('.child-clone-rows').prepend('<div id="_cce_cshortcodes" class="hidden">' + shortcodes + '</div>');

            // add to parent shortcode
            this.loadVals();
            pShortcode = $('#_cce_ushortcode').text().replace('{{child_shortcode}}', shortcodes);

            // add updated parent shortcode
            $('#_cce_ushortcode').remove();
            $('#cce-sc-form > ul').prepend('<div id="_cce_ushortcode" class="hidden">' + pShortcode + '</div>');
        },

        children: function() {
            // assign the cloning plugin
            $('.child-clone-rows').appendo({
                subSelect: '> div.child-clone-row:last-child',
                allowDelete: false,
                focusFirst: false
            });

            // remove button
            $('.child-clone-row-remove').live('click', function() {
                var btn = $(this),
                    row = btn.parent();

                if( $('.child-clone-row').size() > 1 ) {
                    row.remove();
                }else{
                    alert('You need a minimum of one row');
                }

                return false;
            });

            // assign jUI sortable
            $( ".child-clone-rows" ).sortable({
                placeholder: "sortable-placeholder",
                items: '.child-clone-row'
            });
        },

        resizeTB: function() {
            var ajaxCont = $('#TB_ajaxContent'),
                tbWindow = $('#TB_window'),
                tbTitle  = $('#TB_title'),
                ccePopup = $('#cce-popup');
                f = 920 < jQuery(window).width() ? 920 : jQuery(window).width();
                b = ( ccePopup.outerHeight() + tbTitle.outerHeight() ) > jQuery(window).height() ? jQuery(window).height() - 100 : ccePopup.outerHeight() + tbTitle.outerHeight();     

            tbWindow.css({
                height: b,
                maxHeight: b,
                width: f,
                marginLeft: -(f/2)
            });

            ajaxCont.css({
                paddingTop: 0,
                paddingLeft: 0,
                paddingRight: 0,
                paddingBottom: 0,
                height: ( ccePopup.outerHeight() + tbTitle.outerHeight() ) > jQuery(window).height() ? b-tbTitle.outerHeight() : b,
                overflow: 'auto',
                width: f
            });

            $('#cce-popup').addClass('no_preview');
            
        },

        media: function(){
            var cce_media_frame,
                frame_title,
                insertButton = $('.cce-open-media');

            if ( insertButton.data('type') === "image" ) {
                frame_title = CCShortcodes.media_frame_image_title;
            } else if ( insertButton.data('type') === "video" ) {
                frame_title = CCShortcodes.media_frame_video_title;
            }

            insertButton.on('click', function(e){
                e.preventDefault();

                if(cce_media_frame){
                    cce_media_frame.open();
                    return;
                }

                cce_media_frame = wp.media.frames.cce_media_frame = wp.media({
                    className: 'media-frame cce-media-frame',
                    frame: 'select',
                    multiple: false,
                    title: frame_title,
                    library: {
                        type: insertButton.data('type')
                    },
                    button: {
                        text: insertButton.data('text')
                    }
                });

                cce_media_frame.on('select', function(){
                    var media_attachment = cce_media_frame.state().get('selection').first().toJSON();
                    $('#'+insertButton.data('name')).val(media_attachment.url);
                    $('.cce-input').trigger('change');
                });

                cce_media_frame.open();

            });
        },
        
        map: function(){
	        var cce_location = $('.cce-form-location');
	        
	        cce_location.each(function(){
		        
		        var cce_mappicker = $('#picker-'+$(this).data('key')),
	        		cce_lat = $('#lat-'+$(this).data('key')),
					cce_long = $('#long-'+$(this).data('key'));
					cce_input = $('#'+$(this).data('key'))
					
				$(cce_mappicker).locationpicker({	
					radius: 0,
					zoom: 6,
					enableAutocomplete: true,
					inputBinding: {
				        latitudeInput: $(cce_lat),
				        longitudeInput: $(cce_long),
				        locationNameInput: $($(this))
				    },
				    oninitialized: function(component) {
					    var location = $(component).locationpicker('map').location;
					    $(cce_input).val(location);
					},
				    onchanged: function(currentLocation, isMarkerDropped) {
					    $(cce_input).val(currentLocation.latitude+','+currentLocation.longitude);
					}
				});
		        
	        });
        },

        load: function() {
            var cces = this,
                tbWindow = $('#TB_window'),
                popup = $('#cce-popup'),
                tbWindowTitle = $('#TB_title #TB_ajaxWindowTitle');
                popupTitle = $('#_cce_popup_title').text();
                form = $('#cce-sc-form', popup),
                shortcode = $('#_cce_shortcode', form).text(),
                popupType = $('#_cce_popup', form).text(),
                uShortcode = '',
                iconSelector = $('.cce-all-icons').find('i');

            // resize TB
            cces.resizeTB();
            $(window).resize(function() { cces.resizeTB() });

            tbWindow.css({
                border: "none",
            });
            
            tbWindowTitle.text(popupTitle);

            // initialise
            cces.loadVals();
            cces.children();
            cces.cLoadVals();
            cces.media();
            cces.map();

            // update on children value change
            $('.cce-cinput', form).live('change', function() {
                cces.cLoadVals();
            });

            // update on value change
            $('.cce-input', form).live('change', function() {
                cces.loadVals();
                cces.cLoadVals();
            });
            
            // update on colorpicker value change
            $('.cce-input.cce-form-color', form).on('blur', function() {
                cces.loadVals();
            });

            var iconContainer = $('.cce-all-icons');
            iconContainer.append( FontAwesomeIcons() );

            iconContainer.on( 'click', 'i', function(e) {
                iconContainer.find('i').removeClass('active-icon');
                $(this).addClass('active-icon');
                $('#cce_icon').val( $(this).data('icon-id') );
                $('.cce-input').trigger('change');
            } );

            // when insert is clicked
            $('.cce-insert').click(function() {
	            
	            // fire blur event for color input just to be fail-safe before proceeding
	            $('.cce-input.cce-form-color').blur();
                
                if(window.tinyMCE) {
                    var version = tinyMCE.majorVersion;

                    if ( version === '3' ) {
                        window.tinyMCE.execInstanceCommand( window.tinyMCE.activeEditor.id, 'mceInsertContent', false, $('#_cce_ushortcode', form).html());
                        tb_remove();
                    } else if ( version === '4' ) {
                        window.tinyMCE.activeEditor.insertContent( $('#_cce_ushortcode', form).html() );
                        tb_remove();
                    }

                }
            });
        }
    }

    // run
    $('#cce-popup').livequery( function() {
        cces.load();
    } );
});
