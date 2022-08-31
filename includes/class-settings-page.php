<?php

/**
 * Settings Page Class
 * register custom settings page for the plugin
 * @since 1.0
 * @package breaking-news
 */
class BN_Settings_Page
{

	/**
	 * Settings prefix
	 */
	private $settings_prefix = 'bn';

	/**
	 * Page slug
	 */
	private $page_slug = 'bn-settings';

	/**
	 * Featured post option id
	 */
	private $option_id = 'bn_current_active_post';

	/**
	 * Init
	 * @var $settings_fields list of settings fields to be passed to the constructor function
	 * @example [field_id , field_type color_picker or text, field_title, field_description]
	 * if there's no $settings_fields passed a default list of fields will be registered
	 * @since 1.0
	 */
	public function __construct($settings_fields = false)
	{
		if (!$settings_fields) {
			$this->settings_fields = [
				[
					'id' => 'section_title',
					'type' => 'text',
					'title' => esc_html__('Breaking News Title', 'breaking_news'),
					'description' => esc_html__('Enter breaking news section title.', 'breaking_news')
				],
				[
					'id' => 'section_background_color',
					'type' => 'color_picker',
					'default_color' => '#ececec',
					'title' => esc_html__('Section Background Color', 'breaking_news'),
					'description' => esc_html__('Select background color for your breaking news section, you can reset to default color from the color picker.', 'breaking_news')
				],
				[
					'id' => 'section_position',
					'type' => 'select',
					'title' => esc_html__('Section Position', 'breaking_news'),
					'description' => esc_html__('Select section position, you can choose to display the breaking news section at the top of the page or the bottom of the page.', 'breaking_news'),
					'choices' => [
						'top' => esc_html__('Top', 'breaking-news'),
						'bottom' => esc_html__('Bottom', 'breaking-news')
					]
				],
				[
					'id' => 'section_text_color',
					'type' => 'color_picker',
					'default_color' => '#000',
					'title' => esc_html__('Section Text Color', 'breaking_news'),
					'description' => esc_html__('Select section text color for your breaking news section, you can reset to default color from the color picker.', 'breaking_news')
				],
				[
					'id' => 'current_active_post',
					'type' => 'number',
					'title' => esc_html__('Current Featured Post', 'breaking_news'),
					'description' => esc_html__('You can edit current featured post from post editor.', 'breaking_news')
				],
			];
		} else {
			$this->$settings_fields = $settings_fields;
		}
		$this->register_admin_menu();
	}

	/**
	 * Register Admin Menu and Admin Page Assets
	 * @since 1.0
	 */
	private function register_admin_menu()
	{
		add_action('admin_menu', function () {

			/**
			 * Register admin menu
			 */
			$menu = add_menu_page(
				esc_html__('Breaking News Settings', 'breaking_news'),
				esc_html__('Breaking News Settings', 'breaking_news'),
				'edit_theme_options',
				$this->settings_prefix . '_settings',
				[$this, 'display_settings_page']
			);

			/**
			 * Register settings page
			 */
			$this->register_settings_page();

			/**
			 * Load backend assets
			 */
			add_action('admin_print_styles-' . $menu, function () {
				wp_enqueue_style($this->settings_prefix . '_style', BN_PATH . '/public/src/css/backend.css');
			});
			add_action('admin_print_scripts-' . $menu, function () {
				$script_id = $this->settings_prefix . '_script';
				wp_register_script($script_id, BN_PATH . '/public/src/javascript/backend.js', ['wp-color-picker']);
				wp_enqueue_script($script_id);
				wp_enqueue_style('wp-color-picker');
			});
		});
	}

	/**
	 * Register Page Settings
	 * @uses $this->settings_fields, registered with constructor
	 * @since 1.0
	 */
	public function register_settings_page()
	{
		add_action('admin_init', function () {
			foreach ($this->settings_fields as $setting) {
				register_setting(
					$this->settings_prefix . '_plugin_options',
					$this->settings_prefix . '_' . $setting['id'],
					[$this, 'validate_input']
				);
			}
		});
	}

	/**
	 * Validate Page Settings Input
	 * this function will validate user input
	 * @since 1.0
	 */
	private function validate_input($input)
	{
		return $input;
	}

	/**
	 * Register Settings Page
	 * @since 1.0
	 */
	public function display_settings_page()
	{

?>
		<h1 style="font-weight: normal;"><?php echo esc_html__('Breaking News Settings', 'breaking-news'); ?></h1>
		<form method="post" action="options.php">
			<?php settings_fields($this->settings_prefix . '_plugin_options'); ?>
			<?php do_settings_sections($this->settings_prefix . '_plugin_options'); ?>
			<table class="form-table">
				<tbody>
					<?php
					foreach ($this->settings_fields  as $setting) :
						// Define current settings id, field type, HTML class and default color
						$setting_id = $this->settings_prefix . '_' . $setting['id'];
						$setting_class = $setting['type'] == 'color_picker' ? 'color-picker' : 'regular-text';
						$setting_default_color = isset($setting['default_color']) ? wp_sprintf('data-default-color="%1$s" ', $setting['default_color']) : '';
					?>
						<tr>
							<th scope="row">
								<label for="<?php echo esc_attr($setting_id) ?>"><?php echo $setting['title']; ?></label>
							</th>
							<td>
								<?php if ($setting['type'] == 'select') :  ?>
									<select name="<?php echo esc_attr($setting_id); ?>" id="<?php echo esc_attr($setting_id); ?>">
										<?php foreach ($setting['choices'] as $choice_id => $choice_name) : ?>
											<option <?php echo get_option($setting_id) == $choice_id ? 'selected' : ''; ?> value="<?php echo esc_attr($choice_id); ?>"><?php echo $choice_name; ?></option>
										<?php endforeach; ?>
									</select>
									<p class="description"><?php echo $setting['description']; ?></p>
								<?php elseif ($setting['id'] !== 'current_active_post') : ?>

									<input name="<?php echo esc_attr($setting_id); ?>" type="<?php echo esc_attr($setting['type']); ?>" id="<?php echo esc_attr($setting_id); ?>" aria-describedby="<?php echo esc_attr($setting['title']); ?>" value="<?php echo esc_attr(get_option($setting_id)); ?>" class="<?php echo $setting_class; ?>" <?php echo esc_attr($setting_default_color); ?>>
									<p class="description"><?php echo $setting['description']; ?></p>

								<?php else : ?>

									<?php $this->display_featured_post(); ?>
									<input type="hidden" name="<?php echo esc_attr($setting_id); ?>" value="<?php echo esc_attr(get_option($this->option_id)); ?>">
								<?php endif; ?>
							</td>
						</tr>
					<?php
					endforeach; ?>
				</tbody>
			</table>
			<?php submit_button(); ?>
		</form>
		<?php

	}


	/**
	 * Display current featured post
	 * @since 1.0
	 */
	public function display_featured_post()
	{
		// Get featured post id
		$current_active_post = intval(get_option($this->option_id));
		if ($current_active_post !== '') :
		?>
			<div class="active-post">
				<div class="content">
					<h4><?php echo get_the_title($current_active_post); ?></h4>
					<a target="_blank" class="button button-secondary" href="<?php echo esc_url(get_edit_post_link($current_active_post)); ?>"><?php echo esc_html__('Edit', 'breaking-news'); ?></a>
				</div>
			</div>
<?php
		endif;
	}
}
