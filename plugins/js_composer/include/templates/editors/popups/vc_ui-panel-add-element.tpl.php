<?php
/**
 * Add element panel template.
 *
 * @var WPBakeryShortCode_VC_Column $box
 * @var array $header_tabs_template_variables
 * @var Vc_Add_Element_Box $box
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>
<div class="vc_ui-font-open-sans vc_ui-panel-window vc_media-xs vc_ui-panel"
	data-vc-panel=".vc_ui-panel-header-header" data-vc-ui-element="panel-add-element" id="vc_ui-panel-add-element">
	<div class="vc_ui-panel-window-inner">
		<?php
		vc_include_template( 'editors/popups/vc_ui-header.tpl.php', [
			'title' => esc_html__( 'Add Element', 'js_composer' ),
			'controls' => [ 'close' ],
			'header_css_class' => 'vc_ui-add-element-header-container',
			'header_tabs_template' => 'editors/partials/add_element_tabs.tpl.php',
			'box' => $box,
			'search_template' => 'editors/partials/add_element_search.tpl.php',
			'header_tabs_template_variables' => $header_tabs_template_variables,
		] )
		?>
		<div class="vc_ui-panel-content-container">
			<div class="vc_add-element-container">
				<div class="wpb-elements-list vc_filter-all" data-vc-ui-filter="*"
					data-vc-ui-element="panel-add-element-list">
					<ul class="wpb-content-layouts-container">
						<li class="vc_add-element-deprecated-warning">
							<div class="wpb_element_wrapper">
								<?php
								// @codingStandardsIgnoreLine
								print vc_message_warning( esc_html__( 'Elements within this list are deprecated and are no longer supported in newer versions of WPBakery Page Builder.', 'js_composer' ) );
								?>
							</div>
						</li>
						<li>
							<?php
							// @codingStandardsIgnoreLine
							print $box->getControls();
							?>
						</li>
						<?php if ( $box->isShowEmptyMessage() && true !== $box->getPartState() ) : ?>
							<li class="vc_add-element-access-warning">
								<div class="wpb_element_wrapper">
									<?php
									// @codingStandardsIgnoreLine
									print vc_message_warning( esc_html__( 'Your user role have restricted access to content elements. If required, contact your site administrator to change WPBakery Page Builder Role Manager settings for your user role.', 'js_composer' ) );
									?>
								</div>
							</li>
						<?php endif; ?>
					</ul>
					<div class="vc_clearfix"></div>
					<?php if ( vc_user_access()->part( 'presets' )->checkStateAny( true, null )->get() ) : ?>
						<div class="vc_align_center">
							<span class="vc_general vc_ui-button vc_ui-button-action vc_ui-button-shape-rounded vc_ui-button-fw" data-vc-manage-elements style="display:none;"><?php esc_html_e( 'Manage elements', 'js_composer' ); ?></span>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
