<?php
if (! defined('WPINC')) {
    die;
}

/**
 * Class Page Settings
 */
class Codetot_Page_Settings
{
    /**
     * Singleton instance
     *
     * @var Codetot_Page_Settings
     */
    private static $instance;

    /**
     * Get singleton instance.
     *
     * @return Codetot_Page_Settings
     */
    final public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_page_settings_metabox_fields'));
        add_filter('body_class', array($this, 'page_body_class'));
    }

    public function add_meta_boxes()
    {
        add_meta_box(
            'page-settings',
            __('[CT] Page Settings', 'ct-bones'),
            array($this, 'render_page_settings_metabox'),
            array('page'),
            'side',
            'high'
        );
    }

	/**
	 * Render a field in Admin UI
	 *
	 * @param object $post
	 * @return void
	 */
    public function render_page_settings_metabox($post)
    {
		$warning_message = '';
        $page_css_class_value = get_post_meta($post->ID, 'page_css_class', true);
		$old_page_class = function_exists('rwmb_meta') ? rwmb_meta('codetot_page_class') : '';

		// Request to update current page with new page setting
		if (!empty($old_page_class) && empty($page_css_class_value)) {
			$warning_message = __('Please save this page once to update page CSS class. Old setting and Metabox will be remove completely in next version.', 'ct-bones');
			$page_css_class_value = $old_page_class;
		}
		?>
		<div class="components-base-control">
			<?php if (!empty($warning_message)) : ?>
				<p style="color: red; margin-bottom: 10px;"><?php echo $warning_message; ?></p>
			<?php endif; ?>
			<div class="components-base-control__field">
				<label clas="components-base-control__label" for="page_css_class" style="display: block; margin-bottom: 8px;"><?php _e('Page CSS Class', 'ct-bones'); ?></label>
				<input class="components-text-control__input" name="page_css_class" id="page_css_class" value="<?php echo esc_html($page_css_class_value); ?>">
			</div>
		</div>
		<?php
    }

	/**
	 * Save field value
	 *
	 * @param string $post_id
	 * @return void
	 */
    public function save_page_settings_metabox_fields($post_id)
    {
        if (isset($_POST['page_css_class'])) {
            update_post_meta(
                $post_id,
                'page_css_class',
                esc_html($_POST['page_css_class'])
            );
        }
    }

	/**
	 * Add body class
	 *
	 * @param [type] $classes
	 * @return void
	 */
    public function page_body_class($classes)
    {
        if (is_page()) {
            global $post;

            $page_class = get_post_meta($post->ID, 'page_css_class');

            if (!empty($page_class)) {
                $classes[] = esc_attr($page_class[0]);
            }
        }

        return $classes;
    }
}

Codetot_Page_Settings::instance();
