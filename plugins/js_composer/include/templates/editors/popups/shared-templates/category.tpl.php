<?php
/**
 * Category template for shared templates.
 *
 * @var Vc_Shared_Templates $controller
 * @var array $templates
 *
 * phpcs:ignoreFile:Generic.PHP.DisallowAlternativePHPTags.MaybeASPShortOpenTagFound
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$custom_tag = 'script';
?>

<<?php echo esc_attr( $custom_tag ); ?>>
	window.vcTemplatesLibraryData = {
		templates: <?php echo wp_json_encode( $templates ); ?>
	};
</<?php echo esc_attr( $custom_tag ); ?>>
<div class="vc_ui-panel-popup vc_ui-hidden">
	<div class="vc_ui-panel-template-content vc_ui-panel-popup-item vc_ui-hidden">
		<button type="button" class="vc_general vc_ui-control-button vc_ui-panel-close-button">
			<i class="vc-composer-icon vc-c-icon-arrow_back"></i>
			<span class="vc_ui-control-button-text"><?php esc_html_e( 'Exit Template Library', 'js_composer' ); ?></span>
		</button>
		<?php
		if ( ! vc_license()->isActivated() ) :
			?>
			<div class="vc_ui-panel-message">
				<h3 class="vc_ui-panel-title"><?php esc_html_e( 'Activate WPBakery Page Builder', 'js_composer' ); ?></h3>
				<p class="vc_description"><?php esc_html_e( 'WPBakery Page Builder Template Library downloads are available for activated	versions only. Activate WPBakery Page Builder direct license to access Template Library and receive other benefits.', 'js_composer' ); ?></p>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=vc-updater' ) ); ?>" target="_blank" class="vc_general vc_ui-button vc_ui-button-size-sm vc_ui-button-shape-rounded vc_ui-button-action">
					<?php esc_html_e( 'Activate License', 'js_composer' ); ?>
				</a>
			</div>
		<?php endif; ?>
		<div class="vc_ui-panel-message vc_ui-panel-message--error vc_ui-hidden" id="vc_template-library-panel-error-message"></div>
		<div class="vc_ui-search-box vc_ui-panel-search-box">
			<div class="vc_ui-search-box-input vc_ui-panel-search">
				<input type="search" id="vc_template_lib_name_filter" data-vc-template-lib-name-filter="" placeholder="<?php esc_attr_e( 'Search template by name', 'js_composer' ); ?>">
				<label for="vc_template_lib_name_filter"><i class="vc-composer-icon vc-c-icon-search"></i></label>
			</div>
		</div>
		<div class="vc_ui-panel-template-grid" id="vc_template-library-template-grid">

		</div>
	</div>
	<div class="vc_ui-panel-template-preview vc_ui-panel-popup-item vc_ui-hidden">
		<div class="vc_ui-panel-template-preview-inner">
			<button type="button" class="vc_general vc_ui-control-button vc_ui-panel-back-button">
				<i class="vc-composer-icon vc-c-icon-arrow_back"></i>
			</button>
			<h3 class="vc_ui-panel-title"></h3>
			<?php if ( vc_license()->isActivated() ) : ?>
				<div class="vc_ui-panel-template-download vc_ui-hidden" id="vc_template-library-download">
					<button id="vc_template-library-download-btn" class="vc_general vc_ui-button vc_ui-button-size-sm vc_ui-button-shape-rounded vc_ui-button-action">
						<?php esc_html_e( 'Download Template', 'js_composer' ); ?>
					</button>
				</div>
			<?php endif; ?>
		</div>
		<div class="vc_ui-panel-preview-content">
			<img class="vc_ui-panel-preview-image" src="" alt="">
		</div>
	</div>
	<div class="vc_ui-panel-download vc_ui-panel-popup-item vc_ui-hidden">
		<div class="vc_ui-panel-loading-content">
			<div class="vc_preloader-box"></div>
			<h3 class="vc_ui-panel-title"><?php esc_html_e( 'Downloading template ... please wait!', 'js_composer' ); ?></h3>
			<p class="vc_description">
				<?php
				esc_html_e( 'Don\'t close this window until download is complete - you will be redirected back to Template Library automatically.', 'js_composer' );
				?>
			</p>
		</div>
	</div>
</div>

<div class="vc_ui-panel-loading vc_ui-hidden">
	<div class="vc_preloader-box"></div>
</div>
<div class="vc_ui-templates-content">
	<?php
	if ( vc_user_access()->part( 'templates' )->checkStateAny( true, null )->get() ) :
		?>
		<div class="vc_column vc_col-sm-12 vc_access-library-col" data-vc-hide-on-search="true">
			<h3 class="vc_ui-panel-title"><?php esc_html_e( 'Download Templates', 'js_composer' ); ?></h3>
			<p class="vc_description">
				<?php
				esc_html_e( 'Access WPBakery Page Builder Template Library for unique layout
		templates. Download chosen templates and discover new layouts with regular template
		updates from WPBakery Page Builder team.', 'js_composer' );
				?>
			</p>
			<button class="vc_general vc_ui-button vc_ui-button-size-md vc_ui-button-shape-rounded vc_ui-button-action vc_ui-access-library-btn">
				<?php esc_html_e( 'Access Library', 'js_composer' ); ?>
			</button>
		</div>
		<?php
	else :
		?>
		<div class="vc_column vc_col-sm-12 vc_access-library-col" data-vc-hide-on-search="true">
			<h3 class="vc_ui-panel-title"><?php esc_html_e( 'Template library', 'js_composer' ); ?></h3>
		</div>
		<?php
	endif;
	?>

	<div class="vc_column vc_col-sm-12">
		<div class="vc_ui-template-list vc_templates-list-shared_templates vc_ui-list-bar" id="vc_template-library-shared_templates">
		</div>
	</div>
</div>

<<?php echo esc_attr( $custom_tag ); ?> type="text/html" id="vc_template-item">
	<div class="vc_ui-template vc_templates-template-type-shared_templates"
			data-template_id="<%- post_id %>"
			data-template_name="<%- _.escape(vc_slugify(title)) %>"
			data-category="shared_templates"
			data-template_type="shared_templates"
			data-template_action="vc_delete_template"
			data-vc-content=".vc_ui-template-content">
		<div class="vc_ui-list-bar-item">
			<button type="button" class="vc_ui-list-bar-item-trigger" data-template-handler data-vc-ui-element="template-title" title="<?php esc_attr_e( 'Add template', 'js_composer' ); ?>"><%- title %></button>
			<div class="vc_ui-list-bar-item-actions">
				<button type="button" class="vc_general vc_ui-control-button" data-template-handler title="<?php esc_attr_e( 'Add template', 'js_composer' ); ?>">
					<i class="vc-composer-icon vc-c-icon-add"></i>
				</button>
				<?php
				if ( vc_user_access()->part( 'templates' )->checkStateAny( true, null )->get() ) :
					?>
					<button type="button" class="vc_general vc_ui-control-button" data-vc-ui-delete="template-title" title="<?php esc_attr_e( 'Delete template', 'js_composer' ); ?>">
						<i class="vc-composer-icon vc-c-icon-delete_empty"></i>
					</button>
					<?php
				endif;
				?>
				<button type="button" class="vc_general vc_ui-control-button" data-vc-preview-handler data-vc-container=".vc_ui-list-bar" data-vc-target="[data-template_id=&quot;<%- post_id %>&quot;]" title="<?php esc_attr_e( 'Preview template', 'js_composer' ); ?>">
					<i class="vc-composer-icon vc-c-icon-arrow_drop_down"></i>
				</button>
			</div>
		</div>
		<div class="vc_ui-template-content" data-js-content>
		</div>
	</div>
</<?php echo esc_attr( $custom_tag ); ?>>

<<?php echo esc_attr( $custom_tag ); ?> type="text/html" id="vc_template-grid-item">
	<div class="vc_ui-panel-template-item vc_ui-visible" data-template-id="<%- id %>">
		<span class="vc_ui-panel-template-item-content">
			<img src="<%- thumbnailUrl %>" alt=""/>
			<span class="vc_ui-panel-template-item-overlay">
				<a href="javascript:" class="vc_ui-panel-template-item-overlay-button vc_ui-panel-template-preview-button"
						data-preview-url="<%- previewUrl %>" data-title="<%- title %>" data-template-id="<%- id %>" data-template-version="<%- version %>"><i class="vc-composer-icon vc-c-icon-search"></i></a>
				<?php if ( vc_license()->isActivated() ) : ?>
					<% if (!downloaded) { %>
					<a href="javascript:" class="vc_ui-panel-template-item-overlay-button vc_ui-panel-template-download-button">
					<i class="vc-composer-icon vc-c-icon-arrow_downward"></i>
					</a>
					<% } else if (downloaded && downloaded.version < version) { %>
					<a href="javascript:" class="vc_ui-panel-template-item-overlay-button vc_ui-panel-template-update-button">
					<i class="vc-composer-icon vc-c-icon-sync"></i>
					</a>
					<% } %>
				<?php endif; ?>
			</span>
		</span>
		<span class="vc_ui-panel-template-item-name">
			<span><%- title %></span>
		</span>
		<%= status %>
	</div>
</<?php echo esc_attr( $custom_tag ); ?>>
