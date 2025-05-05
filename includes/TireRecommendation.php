<?php
    namespace TRS;

    use TRS\AjaxHandler;
    use TRS\GoogleSheets;

    class TireRecommendation
    {
        private $google_sheets;
        private $ajax_handler;

        public function __construct()
        {
            $this->google_sheets = new GoogleSheets();
            $this->ajax_handler  = new AjaxHandler($this->google_sheets);

            // Register Shortcode and Actions
            add_shortcode('tire_recommendation_form', [$this, 'render_form']);
            add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        }

        /**
         * Enqueue styles and scripts
         */
        public function enqueue_assets()
        {
            wp_enqueue_style('tailwindcss', 'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css', [], null);
            wp_enqueue_script('trs-script', TIRE_RECOMMENDATION_PLUGIN_ASSETS . '/script.js', ['jquery'], null, true);
            wp_localize_script('trs-script', 'trs_ajax', ['url' => admin_url('admin-ajax.php')]);
        }

        /**
         * Render the tire recommendation form
         */

        public function render_form()
        {
            ob_start();
        ?>
        <form id="trs-form" class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow-md space-y-4">
            <div>
                <label for="registration" class="block text-sm font-medium text-gray-700">Registration Number</label>
                <input type="text" name="registration" id="registration" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Enter registration" required>
            </div>
            <div>
                <label for="postcode" class="block text-sm font-medium text-gray-700">Postcode</label>
                <input type="text" name="postcode" id="postcode" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Enter postcode" required>
            </div>
            <button type="submit" class="w-full text-white px-4 py-2 rounded hover:opacity-90 transition" style="background-color: oklch(57.7% 0.245 27.325)">
                Find Tires
            </button>
        </form>

        <div id="trs-message" class="text-center mt-6 text-red-600 font-semibold hidden"></div>
        <div id="trs-results" class="mt-8 max-w-6xl mx-auto grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4"></div>
        <?php
            return ob_get_clean();
                }
        }
