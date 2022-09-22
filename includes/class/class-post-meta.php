<?php

/**
 * Post Meta Class
 * Register custom meta box for posts
 * @since 1.0
 * @package breaking-news
 *
 */

/**
 * Import Config Trait
 */
require_once BN_DIR . '/includes/trait/trait-config.php';

class BN_Post_Metabox
{
	// Use Config
	use BN_Config;

	// Class instance
	private $instance = null;

	/**
	 * Get instance
	 * check if class instance already exists and return a new instance if not
	 */
	public function getInstance()
	{
		if (null === $this->instance) {
			$this->instance = new BN_Post_Metabox();
		}
		return $this->instance;
	}

	/**
	 * Register Metaboxes update them on save_post
	 * @since 1.0
	 */
	public function init()
	{
		add_action('add_meta_boxes', [$this, 'add_metaboxes']);
		add_action('save_post', [$this, 'save_post'], 10, 2);
	}


	/**
	 * Add Meta Boxes
	 * Required WordPress 4.4+
	 * @since 1.0
	 */
	public function add_metaboxes()
	{
		add_meta_box($this->meta_box_container_id, esc_html__('Featured Post', 'breaking-news'), [$this, 'display_metaboxes'], ['post'], 'side', 'default', [
			'__block_editor_compatible_meta_box' => true,
			'__back_compat_meta_box'             => false,
		]);
	}


	/**
	 * Display Meta Boxes HTML
	 * @since 1.0
	 */
	public function display_metaboxes($post)
	{
		// Current current featured post and check if it's the same post we're in
		$current_post_meta = get_option($this->option_id);
		$checked = intval($current_post_meta) === get_the_ID() ? 'checked' : '';
?>
		<p>
			<label class="component-label" for="<?php echo esc_attr($this->meta_box_field_id); ?>">
				<input value="breaking-news-checked" class="widefat" <?php echo esc_attr($checked); ?> name="<?php echo esc_attr($this->meta_box_field_id); ?>[]" id="<?php echo esc_attr($this->meta_box_field_id); ?>" type="checkbox" />
				<?php echo esc_html__('Make this post a featured post', 'breaking-news'); ?>
			</label>
		</p>
<?php

	}

	/**
	 * Save meta box value on post_save
	 * @since 1.0
	 */
	public function save_post($post_id, $post)
	{
		/**
		 * REturn if auto save is currently active or current user can't edit post
		 */
		if ((defined('DOING_AUTOSAVE' && DOING_AUTOSAVE)) ||
			!current_user_can('edit_posts')
		) {
			return;
		}

		if (isset($_POST[$this->meta_box_field_id])) {
			// Update option fo
			update_option($this->option_id, $post_id);
		} else {
			update_option($this->option_id, '');
		}
	}
}
