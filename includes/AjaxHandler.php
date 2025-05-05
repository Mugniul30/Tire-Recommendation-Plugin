<?php
namespace TRS;

use WP_Query;

class AjaxHandler
{

    private $google_sheets;

    public function __construct(GoogleSheets $google_sheets)
    {
        $this->google_sheets = $google_sheets;

        add_action('wp_ajax_trs_fetch_products', [$this, 'handle_ajax_request']);
        add_action('wp_ajax_nopriv_trs_fetch_products', [$this, 'handle_ajax_request']);
    }

    public function handle_ajax_request()
    {
        $registration = sanitize_text_field($_POST['registration']);
        $postcode     = sanitize_text_field($_POST['postcode']);
        $match        = $this->google_sheets->get_tire_data($registration, $postcode);

        if (! $match) {
            wp_send_json_error('Car data not found. Please browse products manually.');
        }

        $sizes  = array_map('trim', explode(',', $match['sizes']));
        $brands = array_map('trim', explode(',', $match['brands']));

        $args = [
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'tax_query'      => [
                'relation' => 'AND',
                [
                    'taxonomy' => 'pa_size',
                    'field'    => 'slug',
                    'terms'    => array_map('sanitize_title', $sizes),
                ],
                [
                    'taxonomy' => 'product_brand',
                    'field'    => 'slug',
                    'terms'    => array_map('sanitize_title', $brands),
                ],
            ],
        ];

        $query = new WP_Query($args);

        if (! $query->have_posts()) {
            wp_send_json_error('No matching tires found.');
        }

        ob_start();
        while ($query->have_posts()) {
            $query->the_post();
            global $product;

            // Output product details
        }
        wp_reset_postdata();

        $output = ob_get_clean();
        wp_send_json_success($output);
    }
}
