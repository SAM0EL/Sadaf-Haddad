<?php
/**
 * Blog posts widget.
 *
 * @package vogue
 * @since 1.0.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* Load the widget */
add_action( 'widgets_init', array( 'Presscore_Inc_Widgets_BlogPosts', 'presscore_register_widget' ) );

class Presscore_Inc_Widgets_BlogPosts extends WP_Widget {

	/* Widget defaults */
	public static $widget_defaults = array(
		'title' => '',
		'order' => 'DESC',
		'orderby' => 'date',
		'select' => 'all',
		'show' => 6,
		'cats' => array(),
		'round_images' => true,
		'thumbnails' => true,
		'show_excerpts' => false,
		'images_dimensions' => array( 'w' => 40, 'h' => 40 )
	);

	/* Widget setup  */
	function __construct() {
		/* Widget settings. */
		$widget_ops = array( 'description' => _x( 'Blog posts', 'widget', 'the7mk2' ) );

		/* Create the widget. */
		parent::__construct(
			'presscore-blog-posts',
			DT_WIDGET_PREFIX . _x( 'Blog posts', 'widget', 'the7mk2' ),
			$widget_ops
		);
	}

	/**
	 * Display the widget.
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance The settings for the particular instance of the widget.
	 */
	public function widget( $args, $instance ) {
		extract( $args );

		$instance = wp_parse_args( (array) $instance, self::$widget_defaults );

		/* Our variables from the widget settings. */
		$title = apply_filters( 'widget_title', $instance['title'] );
		$terms = empty( $instance['cats'] ) ? [ 0 ] : (array) $instance['cats'];

		$html = '';
		if ( $terms ) {
			$attachments_data = presscore_get_related_posts(
				[
					'exclude_current' => false,
					'cats'            => $terms,
					'select'          => $instance['select'],
					'post_type'       => 'post',
					'taxonomy'        => 'category',
					'field'           => 'term_id',
					'args'            => [
						'posts_per_page' => $instance['show'],
						'orderby'        => $instance['orderby'],
						'order'          => $instance['order'],
					],
				]
			);

			if ( $attachments_data ) {
				$posts_list = $this->get_posts_html_list(
					$attachments_data,
					[
						'show_images'      => (bool) $instance['thumbnails'],
						'show_excerpts'    => (bool) $instance['show_excerpts'],
						'image_dimensions' => $instance['images_dimensions'],
					]
				);
				foreach ( $posts_list as $p ) {
					$html .= sprintf( '<li>%s</li>', $p );
				}

				$html = '<ul class="recent-posts' . ( $instance['round_images'] ? ' round-images' : '' ) . '">' . $html . '</ul>';
			}
		}

		echo $before_widget;

		if ( $title ) {
			echo $before_title . esc_html( $title ) . $after_title;
		}

		echo $html;

		echo $after_widget;
	}

	/* Update the widget settings  */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] 		= strip_tags($new_instance['title']);
		$instance['order']    	= esc_attr($new_instance['order']);
		$instance['orderby']   	= esc_attr($new_instance['orderby']);
		$instance['show']     	= intval($new_instance['show']);

		$instance['select']   	= in_array( $new_instance['select'], array('all', 'only', 'except') ) ? $new_instance['select'] : 'all';
		$instance['cats']    	= (array) $new_instance['cats'];
		if ( empty($instance['cats']) ) { $instance['select'] = 'all'; }

		$instance['thumbnails'] = absint($new_instance['thumbnails']);
		$instance['round_images'] = absint($new_instance['round_images']);
		$instance['show_excerpts'] = absint($new_instance['show_excerpts']);
		$instance['images_dimensions'] = array_map( 'absint', $new_instance['images_dimensions'] );

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$instance = wp_parse_args( (array) $instance, self::$widget_defaults );

		$terms = get_terms( 'category', array(
			'hide_empty'    => 1,
			'hierarchical'  => false
		) );

		$orderby_list = array(
			'ID'        => _x( 'Order by ID', 'widget', 'the7mk2' ),
			'author'    => _x( 'Order by author', 'widget', 'the7mk2' ),
			'title'     => _x( 'Order by title', 'widget', 'the7mk2' ),
			'date'      => _x( 'Order by date', 'widget', 'the7mk2' ),
			'modified'  => _x( 'Order by modified', 'widget', 'the7mk2' ),
			'rand'      => _x( 'Order by rand', 'widget', 'the7mk2' ),
			'menu_order'=> _x( 'Order by menu', 'widget', 'the7mk2' )
		);

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _ex('Title:', 'widget',  'the7mk2'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>

		<p>
			<strong><?php _ex('Category:', 'admin', 'the7mk2'); ?></strong><br />
			<?php if( !is_wp_error($terms) ): ?>

				<div class="dt-widget-switcher">

					<label><input type="radio" name="<?php echo $this->get_field_name( 'select' ); ?>" value="all" <?php checked($instance['select'], 'all'); ?> /><?php _ex('All', 'widget', 'the7mk2'); ?></label>
					<label><input type="radio" name="<?php echo $this->get_field_name( 'select' ); ?>" value="only" <?php checked($instance['select'], 'only'); ?> /><?php _ex('Only', 'widget', 'the7mk2'); ?></label>
					<label><input type="radio" name="<?php echo $this->get_field_name( 'select' ); ?>" value="except" <?php checked($instance['select'], 'except'); ?> /><?php _ex('Except', 'widget', 'the7mk2'); ?></label>

				</div>

				<div class="hide-if-js">

					<?php foreach( $terms as $term ): ?>

					<input id="<?php echo $this->get_field_id($term->term_id); ?>" type="checkbox" name="<?php echo $this->get_field_name('cats'); ?>[]" value="<?php echo $term->term_id; ?>" <?php checked( in_array($term->term_id, $instance['cats']) ); ?> />
					<label for="<?php echo $this->get_field_id($term->term_id); ?>"><?php echo $term->name; ?></label><br />

					<?php endforeach; ?>

				</div>

			<?php endif; ?>

		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'show' ); ?>"><?php _ex('Number of posts:', 'widget', 'the7mk2'); ?></label>
			<input id="<?php echo $this->get_field_id( 'show' ); ?>" name="<?php echo $this->get_field_name( 'show' ); ?>" value="<?php echo esc_attr($instance['show']); ?>" size="2" maxlength="2" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php _ex('Sort by:', 'widget', 'the7mk2'); ?></label>
			<select id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>">
				<?php foreach( $orderby_list as $value=>$name ): ?>
				<option value="<?php echo $value; ?>" <?php selected( $instance['orderby'], $value ); ?>><?php echo $name; ?></option>
				<?php endforeach; ?>
			</select>
		</p>

		</p>
			<label>
			<input name="<?php echo $this->get_field_name( 'order' ); ?>" value="ASC" type="radio" <?php checked( $instance['order'], 'ASC' ); ?> /><?php _ex('Ascending', 'widget', 'the7mk2'); ?>
			</label>
			<label>
			<input name="<?php echo $this->get_field_name( 'order' ); ?>" value="DESC" type="radio" <?php checked( $instance['order'], 'DESC' ); ?> /><?php _ex('Descending', 'widget', 'the7mk2'); ?>
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'thumbnails' ); ?>"><?php _ex('Show featured images', 'widget', 'the7mk2'); ?></label>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'thumbnails' ); ?>" name="<?php echo $this->get_field_name( 'thumbnails' ); ?>" value="1" <?php checked($instance['thumbnails']); ?> />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'round_images' ); ?>"><?php _ex('Make images round', 'widget', 'the7mk2'); ?></label>
			<input type="checkbox" for="<?php echo $this->get_field_id( 'round_images' ); ?>" name="<?php echo $this->get_field_name( 'round_images' ); ?>" value="1" <?php checked($instance['round_images']); ?> />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'show_excerpts' ); ?>"><?php _ex('Show excerpts', 'widget', 'the7mk2'); ?></label>
			<input type="checkbox" for="<?php echo $this->get_field_id( 'show_excerpts' ); ?>" name="<?php echo $this->get_field_name( 'show_excerpts' ); ?>" value="1" <?php checked($instance['show_excerpts']); ?> />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'images_dimensions' ); ?>"><?php _ex('Featured images proportions:', 'widget', 'the7mk2'); ?>
			<input type="text" size="3" name="<?php echo $this->get_field_name( 'images_dimensions' ); ?>[w]" value="<?php echo esc_attr( $instance['images_dimensions']['w'] ); ?>" /><span>x</span>
			<input type="text" size="3" name="<?php echo $this->get_field_name( 'images_dimensions' ); ?>[h]" value="<?php echo esc_attr( $instance['images_dimensions']['h'] ); ?>" /><span><?php _ex( 'in px', 'widget', 'the7mk2' ); ?></span>
		</p>

		<div style="clear: both;"></div>
	<?php
	}

	/**
	 * Just a quick copy of presscore_get_posts_small_list.
	 *
	 * @see presscore_get_posts_small_list
	 *
	 * @param array $attachments_data Attachnets data.
	 * @param array $options List options.
	 *
	 * @return array
	 */
	protected function get_posts_html_list( $attachments_data, $options = [] ) {
		if ( empty( $attachments_data ) ) {
			return [];
		}

		$options = wp_parse_args(
			$options,
			[
				'links_rel'        => '',
				'show_images'      => true,
				'show_excerpts'    => false,
				'image_dimensions' => [
					'w' => 60,
					'h' => 60,
				],
			]
		);

		$image_args = [
			'img_class' => '',
			'class'     => 'alignleft post-rollover',
			'custom'    => $options['links_rel'],
			'options'   => [
				'w' => $options['image_dimensions']['w'],
				'h' => $options['image_dimensions']['h'],
				'z' => true,
			],
			'echo'      => false,
		];

		$articles = [];

		presscore_remove_masonry_lazy_load_attrs();

		foreach ( $attachments_data as $data ) {
			$current_post = null;
			if ( isset( $data['parent_id'] ) ) {
				$current_post = get_post( $data['parent_id'] );
			}

			$permalink = esc_url( $data['permalink'] );

			$attachment_args = [
				'href'     => $permalink,
				'img_meta' => [ $data['full'], $data['width'], $data['height'] ],
				'img_id'   => empty( $data['ID'] ) ? 0 : $data['ID'],
				'echo'     => false,
				'custom'   => 'aria-label="' . esc_attr__( 'Post image', 'the7mk2' ) . '"',
				'wrap'     => '<a %CLASS% %HREF% %CUSTOM%><img %IMG_CLASS% %SRC% %SIZE% %ALT% /></a>',
			];

			// Show something if there is no title.
			if ( empty( $data['title'] ) ) {
				$data['title'] = __( 'No title', 'the7mk2' );
			}

			$class = '';
			if ( ! empty( $data['parent_id'] ) ) {
				$class = 'post-format-standard';

				if ( empty( $data['ID'] ) ) {
					$attachment_args['wrap']     = '<a class="' . esc_attr( $image_args['class'] . ' no-avatar' ) . '" %HREF% %TITLE% style="width:' . (int) $options['image_dimensions']['w'] . 'px; height: ' . (int) $options['image_dimensions']['h'] . 'px;" %CUSTOM%></a>';
					$attachment_args['img_meta'] = [ '', 0, 0 ];
					$attachment_args['options']  = false;
				}
			}

			$article = '<article class="' . esc_attr( $class ) . '">';

			if ( $options['show_images'] ) {
				$article .= sprintf(
					'<div class="mini-post-img">%s</div>',
					dt_get_thumb_img( array_merge( $image_args, $attachment_args ) )
				);
			}

			$article .= '<div class="post-content">';
			$article .= '<a href="' . $permalink . '">' . esc_html( apply_filters( 'post_title', $data['title'] ) ) . '</a>';

			if ( $options['show_excerpts'] ) {
				$article .= '<p>' . esc_html( $data['description'] ) . '</p>';
			} else {
				$article .= '<br />';
			}

			$article .= '<time datetime="' . get_the_date( 'c', $current_post ) . '">';
			$article .= get_the_date( get_option( 'date_format' ), $current_post );
			$article .= '</time>';

			$article .= '</div>';
			$article .= '</article>';

			$articles[] = $article;
		}

		presscore_add_masonry_lazy_load_attrs();

		return $articles;
	}

	public static function presscore_register_widget() {
		register_widget( __CLASS__ );
	}
}
