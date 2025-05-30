<?php
/**
 * Templates part template.
 *
 * @var string $part
 * @var string $role
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
vc_include_template( 'pages/partials/vc-roles-parts/_part.tpl.php', [
	'part' => $part,
	'role' => $role,
	'params_prefix' => 'vc_roles[' . $role . '][' . $part . ']',
	'controller' => vc_role_access()->who( $role )->part( $part ),
	'options' => [
		[ true, esc_html__( 'All', 'js_composer' ) ],
		[ 'add', esc_html__( 'Apply templates only', 'js_composer' ) ],
		[ false, esc_html__( 'Disabled', 'js_composer' ) ],
	],
	'main_label' => esc_html__( 'Templates', 'js_composer' ),
	'description' => esc_html__( 'Control access rights to templates and predefined templates. Note: "Apply templates only" restricts users from saving new templates and deleting existing.', 'js_composer' ),
] );
