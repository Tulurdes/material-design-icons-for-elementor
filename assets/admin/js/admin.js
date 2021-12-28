(function( $ ) {
	'use strict';

	var dashboard = new Vue( {
		el: '#md-icons-dashboard',
		data: {
			iconStyles: window.MDIconsConfig.iconStyles ? window.MDIconsConfig.iconStyles : [],
			saving: false,
			result: false,
			successMessage: '',
			errorMessage: '',
			i18n: window.MDIconsConfig.i18n,

			iconsConfig: window.MDIconsConfig.iconsConfig,

			enqueueIconsCSS: [],

			iconPickerSettings: {
				base: '',
				prefix: '',
				icons: [],
			},

			shortcode: {
				iconStyle: '',
				icon: '',
				copied: false,
			},
			showCopyShortcode: undefined !== navigator.clipboard && undefined !== navigator.clipboard.writeText,

			ourPlugins: [],
		},
		mounted: function() {
			var self = this;
			this.$el.className = 'is-mounted';

			jQuery.ajax({
				url: window.MDIconsConfig.ourPluginsJson,
				type: 'GET',
				dataType: 'json',
			}).done( function( response ) {
				self.ourPlugins = response;
			});
		},
		computed: {
			iconStylesList: function() {
				var list = [];

				for ( var key in this.iconsConfig ) {
					list.push( {
						value: key,
						label: this.iconsConfig[ key ].shortLabel,
					} );
				}

				return list;
			},

			generatedShortcode: function() {

				var result = '[md_icon ';

				if ( ! this.shortcode.iconStyle ) {
					return result + ']';
				}

				result += ' style="' + this.shortcode.iconStyle + '"';
				result += ' icon="' + this.shortcode.icon + '"';
				result += ']';

				return result;
			},
		},
		methods: {
			saveSettings: function() {

				var self = this;

				self.saving = true;

				jQuery.ajax( {
					url: window.ajaxurl,
					type: 'POST',
					dataType: 'json',
					data: {
						action: 'md_icons_save_settings',
						settings: {
							icon_styles: self.iconStyles
						},
					},
				} ).done( function( response ) {

					self.saving = false;

					if ( response.success ) {
						self.result = 'success';
						self.successMessage = self.i18n.saved;

					} else {
						self.result = 'error';
						self.errorMessage = response.message ? response.message : self.i18n.error;
					}

					self.hideNotice();

				} ).fail( function( e, textStatus ) {
					self.result       = 'error';
					self.saving       = false;
					self.errorMessage = e.statusText;
					self.hideNotice();
				} );

			},
			hideNotice: function() {
				var self = this;
				setTimeout( function() {
					self.result       = false;
					self.errorMessage = '';
				}, 8000 );
			},
			changeShortcodeStyle: function() {
				var self = this;

				if ( ! self.shortcode.iconStyle ) {
					return;
				}

				var iconStyle = self.shortcode.iconStyle;

				self.iconPickerSettings.base   = self.iconsConfig[ iconStyle ].displayPrefix;
				self.iconPickerSettings.prefix = self.iconsConfig[ iconStyle ].prefix;

				if ( ! self.iconsConfig[ iconStyle ].icons ) {
					var jsonURL = self.iconsConfig[ iconStyle ].fetchJson;

					jQuery.getJSON( jsonURL, function( data ) {
						self.iconsConfig[ iconStyle ].icons = data.icons;
						self.iconPickerSettings.icons = data.icons;
					} );
				} else {
					self.iconPickerSettings.icons = self.iconsConfig[ iconStyle ].icons;
				}

				// Enqueue Icons CSS
				if ( Array.isArray( self.iconsConfig[ iconStyle ].enqueue ) ) {
					self.iconsConfig[ iconStyle ].enqueue.forEach( function( url ) {
						self.enqueueCSS( url );
					} );
				}

				if ( self.iconsConfig[ iconStyle ].url ) {
					self.enqueueCSS( self.iconsConfig[ iconStyle ].url );
				}
			},
			enqueueCSS: function( url ) {

				if ( -1 !== this.enqueueIconsCSS.indexOf( url ) ) {
					return;
				}

				this.enqueueIconsCSS.push( url );
			},
			copyToClipboard: function() {
				var self = this;

				navigator.clipboard.writeText( this.generatedShortcode ).then( function() {
					// clipboard successfully set
					self.shortcode.copied = true;
					setTimeout( function() {
						self.shortcode.copied = false;
					}, 2000 );
				}, function() {
					// clipboard write failed
				} );
			}
		}
	} );

})( jQuery );
