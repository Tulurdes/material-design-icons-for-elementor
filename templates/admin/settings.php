<div class="wrap">
	<h2 class="page-title"><?php echo get_admin_page_title() ?></h2>
	<div id="md-icons-dashboard">

		<div class="md-icons-main-section">
			<div class="cx-vui-panel">
				<cx-vui-tabs
					:in-panel="false"
					value="settings"
					layout="vertical"
				>
					<cx-vui-tabs-panel
						name="settings"
						label="<?php _e( 'Settings', 'md-icons' ); ?>"
						key="settings"
					>
						<cx-vui-checkbox
							label="<?php _e( 'Icon Styles', 'md-icons' ); ?>"
							name="icon_styles"
							return-type="array"
							:wrapper-css="[ 'vertical-fullwidth' ]"
							:options-list="iconStylesList"
							v-model="iconStyles"
						></cx-vui-checkbox>
						<cx-vui-component-wrapper
							:wrapper-css="[ 'vertical-fullwidth' ]"
						>
							<cx-vui-button
								button-style="accent"
								:loading="saving"
								@click="saveSettings"
							>
							<span
								slot="label"
								v-html="'<?php _e( 'Save', 'md-icons' ); ?>'"
							></span>
							</cx-vui-button>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<span
								class="cx-vui-inline-notice cx-vui-inline-notice--success"
								v-if="'success' === result"
								v-html="successMessage"
							></span>
							<span
								class="cx-vui-inline-notice cx-vui-inline-notice--error"
								v-if="'error' === result"
								v-html="errorMessage"
							></span>
						</cx-vui-component-wrapper>
						<div class="cx-vui-text">
							Enjoy using <i>Material Design Icons for Page Builders</i>? Consider making <a href="https://www.paypal.me/olenabartoshchak" target="_blank">a donation</a> to support the project’s continued development.
						</div>
						<div class="cx-vui-text">
							<strong>Bug Report:</strong> If you find a bug, you can <a href="https://github.com/photon-wp/material-design-icons-for-elementor/issues" target="_blank">submit a bug report</a>.
						</div>
					</cx-vui-tabs-panel>

					<cx-vui-tabs-panel
						name="shortcode_generator"
						label="<?php _e( 'Shortcode Generator', 'md-icons' ); ?>"
						key="shortcode_generator"
					>
						<div
							class="cx-vui-subtitle"
							v-html="'<?php _e( 'Generate shortcode', 'md-icons' ); ?>'"
						></div>
						<div
							class="cx-vui-text"
							v-html="'<?php _e( 'Generate shortcode to output an icon anywhere in the content', 'md-icons' ); ?>'"
						></div>
						<cx-vui-select
							name="shortcode_style"
							label="<?php _e( 'Icon Style', 'md-icons' ); ?>"
							:options-list="iconStylesList"
							:wrapper-css="[ 'equalwidth' ]"
							size="fullwidth"
							v-model="shortcode.iconStyle"
							@input="changeShortcodeStyle"
						></cx-vui-select>
						<link rel="stylesheet" v-for="url in enqueueIconsCSS" :href="url" type="text/css">
						<cx-vui-iconpicker
							name="shortcode_icon"
							label="<?php _e( 'Icon', 'md-icons' ); ?>"
							:icon-base="iconPickerSettings.base"
							:icon-prefix="iconPickerSettings.prefix"
							:icons="iconPickerSettings.icons"
							:wrapper-css="[ 'equalwidth' ]"
							:size="'fullwidth'"
							v-model="shortcode.icon"
							:conditions="[
							{
								input: shortcode.iconStyle,
								compare: 'not_equal',
								value: ''
							}
						]"
						></cx-vui-iconpicker>
						<code class="md-icons-generated-shortcode">
							{{ generatedShortcode }}
							<div
								class="md-icons-generated-shortcode__copy"
								role="button"
								v-if="showCopyShortcode"
								@click="copyToClipboard"
							>
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="black" width="24px" height="24px"><path d="M0 0h24v24H0z" fill="none"/><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg>
								<div
									class="cx-vui-tooltip"
									v-if="shortcode.copied"
								>
									<?php _e( 'Copied!', 'md-icons' ); ?>
								</div>
							</div>
						</code>
						<div class="cx-vui-text">
							Enjoy using <i>Material Design Icons for Page Builders</i>? Consider making <a href="https://www.paypal.me/olenabartoshchak" target="_blank">a donation</a> to support the project’s continued development.
						</div>
						<div class="cx-vui-text">
							<strong>Bug Report:</strong> If you find a bug, you can <a href="https://github.com/photon-wp/material-design-icons-for-elementor/issues" target="_blank">submit a bug report</a>.
						</div>
					</cx-vui-tabs-panel>
				</cx-vui-tabs>
			</div>

			<?php //$this->print_banner_html(); ?>
		</div>

		<div class="md-icons-promo-section">
			<h3><?php _e( 'Our other plugins', 'md-icons' ); ?></h3>
			<div class="md-icons-promo">
				<div class="md-icons-promo__item md-icons-banner" v-for="plugin in ourPlugins">
					<a :href="plugin.demo" target="_blank">
						<img :src="plugin.thumb" width="590" height="300" :alt="plugin.name">
					</a>
				</div>
			</div>
		</div>
	</div>

</div>