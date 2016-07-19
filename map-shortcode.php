<?php
/**
 * @package Map_Shortcode
 * @version 1.0
 */
/*
Plugin Name: Map Shortcode
Plugin URI: http://wordpress.org/plugins/map-shortcode/
Description: Plugin use Gmap3
Author: Eugen Bobrowski
Version: 1.0
Author URI: http://atf.li
*/


class Map_Shortcode
{

    protected static $instance;

    private function __construct()
    {
        add_shortcode('map', array($this, 'map_shortcode'));
    }

    public function enqueue_scrips () {

        wp_register_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyA4yQJAQZy8WDHkX94GHZoOLgqooX61iJk', array(), 3, true);
        if (apply_filters('gmap3_cdn', true)) wp_enqueue_script('gmap3', 'https://cdn.jsdelivr.net/gmap3/7.1.0/gmap3.min.js', array('jquery', 'google-maps'), '7.1.0');
        else wp_enqueue_script('gmap3', plugin_dir_url(__FILE__) . 'gmaip3/gmap3.min.js', array('jquery', 'google-maps'), '7.1.0');
    }

    public function inline_script()
    {

        ?>
        <script>
            (function ($) {
                var $map_shortcodes;
                $(document).ready(function () {
                    $map_shortcodes = $('.map-shortcode');
                    $map_shortcodes.each(function () {
                        var $this = $(this),
                            opts = {};
                        opts.address = $this.data('address');
                        opts.zoom = $this.data('zoom');
                        opts.mapTypeId = google.maps.MapTypeId.ROADMAP;

                        $this.gmap3(opts).marker({address: opts.address});

                    })
                });
            })(jQuery);
        </script>
        <?php
    }
    public function map_shortcode ($attr) {

//        add_action('wp_enqueue_scripts', array($this, 'enqueue_scrips'));
        $this->enqueue_scrips();
        add_action('wp_print_footer_scripts', array($this, 'inline_script'));

        $attr = wp_parse_args($attr, array(
            'zoom' => 6,
            'class' => ''
        ));

        if (isset($attr['address'])) $address = ' data-address="'.$attr['address'].'" ';
        else $address = '';

        $output = '<div class="map-shortcode '.$attr['class'].'" data-zoom="'.$attr['zoom'].'" '.$address.'></div>';



        return $output;
    }

    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}

Map_Shortcode::get_instance();