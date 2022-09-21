<?php

/**
 * Post Meta Class
 * Register custom meta box for posts
 * @since 1.0
 * @package breaking-news
 *
 */
class BN_Post_Metabox
{

	/**
	 * Store HTML container id, field id and option id
	 * @var $container_id, HTML container id
	 * @var $field_id, HTML input field id used in rendering HTML and saving post
	 * @var $option_id, option id that contains current featured post id
	 */
	public static $container_id;
	public static $field_id;
	public static $option_id;

	/**
	 * Register Metaboxes update them on save_post
	 * @since 1.0
	 */
	static function init()
	{

		self::$container_id = 'bn_metabox';
		self::$field_id = 'bn_metabox_field';
		self::$option_id = 'bn_current_active_post';

		add_action('add_meta_boxes', [__CLASS__, 'add_metaboxes']);
		add_action('save_post', [__CLASS__, 'save_post'], 10, 2);
	}


	/**
	 * Add Meta Boxes
	 * Required WordPress 4.4+
	 * @since 1.0
	 */
	static function add_metaboxes()
	{
		add_meta_box(self::$container_id, esc_html__('Featured Post', 'breaking-news'), [__CLASS__, 'display_metaboxes'], ['post'], 'side', 'default', [
			'__block_editor_compatible_meta_box' => true,
			'__back_compat_meta_box'             => false,
		]);
	}


	/**
	 * Display Meta Boxes HTML
	 * @since 1.0
	 */
	static function display_metaboxes($post)
	{
		// Current current featured post and check if it's the same post we're in
		$current_post_meta = get_option(self::$option_id);
		$checked = $current_post_meta == get_the_ID() ? 'checked' : '';
?>
		<p>
			<label class="component-label" for="<?php echo esc_attr(self::$field_id); ?>">
				<input value="breaking-news-checked" class="widefat" <?php echo esc_attr($checked); ?> name="<?php echo esc_attr(self::$field_id); ?>[]" id="<?php echo esc_attr(self::$field_id); ?>" type="checkbox" />
				<?php echo esc_html__('Make this post a featured post', 'breaking-news'); ?>
			</label>
		</p>
<?php

	}

	/**
	 * Save meta box value on post_save
	 * @since 1.0
	 */
	static function save_post($post_id, $post)
	{
		/**
		 * REturn if auto save is currently active or current user can't edit post
		 */
		if ((defined('DOING_AUTOSAVE' && DOING_AUTOSAVE)) ||
			!current_user_can('edit_posts')
		) {
			return;
		}

		if (isset($_POST[self::$field_id])) {
			// Update option fo
			update_option(self::$option_id, $post_id);
		} else {
			update_option(self::$option_id, '');
		}
	}
}
