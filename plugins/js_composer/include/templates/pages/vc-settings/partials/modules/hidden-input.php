<?php
/**
 * Hidden input for modules
 *
 * @var array $hidden_value
 * @var string $option_name
 */

?>

<input type="hidden" value="<?php echo esc_attr( wp_json_encode( $hidden_value ) ); ?>" id="wpb_js_modules" name="<?php echo esc_attr( $option_name ); ?>">
