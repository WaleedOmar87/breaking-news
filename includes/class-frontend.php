<?php

/**
 * Frontend Breaking News
 * register front-end html for the plugin
 * @since 1.0
 * @package breaking-news
 */
class BN_Frontend
{
	/**
	 * Prefix and Metakey
	 */
	private $prefix;
	private $metakey;
	private $settings_prefix;
	private $option_id;

	/**
	 * Constructor
	 * update prefix and meta key, and init register front-end assets and HTML
	 * @since 1.0
	 */
	public function __construct()
	{
		$this->prefix = 'bn_frontend';
		$this->metakey = '_featured';
		$this->settings_prefix = 'bn';
		$this->option_id = 'bn_current_active_post';

		$this->register_frontend_assets();
		$this->register_frontend_html();
	}

	/**
	 * Register Frontend Assets
	 * register front-end css and javascript code
	 * @since 1.0
	 */
	private function register_frontend_assets()
	{
		add_action('wp_enqueue_scripts', function () {

			// Get section style settings and print them as css variables
			$section_background_color = get_option($this->settings_prefix . '_section_background_color');
			$section_text_color = get_option($this->settings_prefix . '_section_text_color');

			// Register front-end CSS
			wp_register_style($this->prefix . '_style', BN_PATH . '/public/src/css/frontend.css');
			// Add inline css
			wp_add_inline_style(
				$this->prefix . '_style',
				wp_sprintf(
					':root { --bn-background-color: %1$s; --bn-text-color:%2$s; }',
					esc_attr($section_background_color),
					esc_attr($section_text_color)
				)
			);

			// Enqueue front-end assets
			wp_enqueue_style($this->prefix . '_style');
			wp_enqueue_script($this->prefix . '_script', BN_PATH . '/public/src/javascript/frontend.js');
		});
	}


	/**
	 * Register Frontend HTML
	 * render HTML contains the current featured post data
	 * @since 1.0
	 */
	private function register_frontend_html()
	{
		// add a container after the wp_footer hook to display the featured post data
		add_action('wp_footer', function () {

			$post_data = $this->get_featured_post_data();

			// Get breaking news section settings
			$section_title = get_option($this->settings_prefix . '_section_title');
			$section_position = get_option($this->settings_prefix . '_section_position', 'top');

			// Get featured post data
			if (!empty($post_data)) :
?>
				<div id="<?php echo esc_attr($this->prefix); ?>" class="<?php echo wp_sprintf('%1$s %2$s', esc_attr($this->prefix), esc_attr($section_position)); ?>">
					<div class="post-container">
						<h4 class="featured-section-title"><?php echo esc_html($section_title); ?></h4>
						<div class="post">
							<a href="<?php echo esc_attr($post_data['url']); ?>"><?php echo $post_data['title']; ?></a>
						</div>
						<span class="close">
							<svg fill="#40C057" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30" width="20px" height="20px">
								<path d="M 7 4 C 6.744125 4 6.4879687 4.0974687 6.2929688 4.2929688 L 4.2929688 6.2929688 C 3.9019687 6.6839688 3.9019687 7.3170313 4.2929688 7.7070312 L 11.585938 15 L 4.2929688 22.292969 C 3.9019687 22.683969 3.9019687 23.317031 4.2929688 23.707031 L 6.2929688 25.707031 C 6.6839688 26.098031 7.3170313 26.098031 7.7070312 25.707031 L 15 18.414062 L 22.292969 25.707031 C 22.682969 26.098031 23.317031 26.098031 23.707031 25.707031 L 25.707031 23.707031 C 26.098031 23.316031 26.098031 22.682969 25.707031 22.292969 L 18.414062 15 L 25.707031 7.7070312 C 26.098031 7.3170312 26.098031 6.6829688 25.707031 6.2929688 L 23.707031 4.2929688 C 23.316031 3.9019687 22.682969 3.9019687 22.292969 4.2929688 L 15 11.585938 L 7.7070312 4.2929688 C 7.5115312 4.0974687 7.255875 4 7 4 z" />
							</svg></span>
					</div>
				</div>
<?php
			endif;
		});
	}

	/**
	 * Get featured post data
	 *	retrieve the current featured post and return it's url and title
	 * @since 1.0
	 */
	private function get_featured_post_data()
	{
		// Store featured post data
		$featured_post_data = [];

		// Get the rows that has active post meta from postmeta table
		$active_post_id = intval(get_option($this->option_id));
		if ('' !== $active_post_id) {
			$featured_post_data['url'] = get_permalink($active_post_id);
			$featured_post_data['title'] = get_the_title($active_post_id);
		}
		return $featured_post_data;
	}
}
