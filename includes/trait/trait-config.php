<?php

/**
 * Prefix and Configuration Trait
 * stores global prefixes and configurations used in instantiating classes
 * @since 1.0
 * @package breaking-news
 */
trait BN_Config
{
	/**
	 * Database And Options Prefixes
	 * used to register database options
	 * @var db_prefix main database prefix used to register plugin options
	 * @var page_slug main dashboard page slug
	 * @var assets_prefix used in assets id, for example prefix_frontend_style
	 * @var option_id used to register active_post option in database, used with db_prefix
	 * @var meta_key_suffix used as a suffix in meta_key
	 * @var meta_box_container_id used as an id for registering custom metabox container
	 * @var meta_box_field_id used as an id for custom meta box field id
	 */
	private $db_prefix = 'bn';
	public $page_slug = 'bn-settings';
	public $assets_prefix = 'bn_frontend';
	public $option_id = 'bn_current_active_post';
	public $meta_key_suffix = '_featured';
	public $meta_box_container_id = 'bn_metabox';
	public $meta_box_field_id = 'bn_metabox_field';
}
