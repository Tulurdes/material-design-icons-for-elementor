<div class="wrap">
	<h2 class="page-title"><?php echo get_admin_page_title() ?></h2>
	<div id="md-icons-dashboard">
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
					<cx-vui-component-wrapper
						label="<?php _e( 'Icon Styles', 'md-icons' ); ?>"
						:wrapper-css="[ 'vertical-fullwidth' ]"
					>
						<cx-vui-checkbox
							name="icon_styles"
							return-type="array"
							:prevent-wrap="true"
							:options-list="[
								{
									value: 'filled',
									label: '<?php _e( 'Filled', 'md-icons' ); ?>',
								},
								{
									value: 'outlined',
									label: '<?php _e( 'Outlined', 'md-icons' ); ?>',
								}
							]"
							v-model="iconStyles"
						></cx-vui-checkbox>
					</cx-vui-component-wrapper>
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
				</cx-vui-tabs-panel>

			</cx-vui-tabs>
	</div>
</div>