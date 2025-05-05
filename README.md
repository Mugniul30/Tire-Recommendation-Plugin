=== Tire Recommendation Plugin ===
Contributors: mugniul
Tags: tires, recommendation, vehicle, Google Sheets, WooCommerce
Requires at least: 5.2
Requires PHP: 7.2
Tested up to: 5.8
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==

This plugin fetches tire recommendations based on vehicle registration and postcode, using data from Google Sheets. It filters WooCommerce products based on vehicle data, displaying the relevant tire options for the user.

== Installation ==

1. Download and install the plugin from the WordPress dashboard, or upload the plugin folder to your WordPress installation's `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Use the `[tire_recommendation_form]` shortcode to display the tire recommendation form.

== Usage ==

1. Add the `[tire_recommendation_form]` shortcode to any page or post where you want to display the tire recommendation form.
2. Users will enter their vehicle registration and postcode to get a list of recommended tires based on their details.

== Google Sheets Implementation ==

This plugin fetches tire recommendation data from a Google Sheet. Below are the steps to set up the **Google Sheet** and connect it to the plugin:

### 1. Create a Google Sheets Spreadsheet

The plugin uses Google Sheets as a data source. To set it up:

1. **Create a new Google Sheets document**.
2. **Create the following columns in the first row**:
   - **Registration Number**: The vehicle's registration number (e.g., "ABC1234").
   - **Postcode**: The vehicle owner's postcode (e.g., "12345").
   - **Tire Sizes**: A comma-separated list of tire sizes (e.g., "205/55R16, 215/60R16").
   - **Brands**: A comma-separated list of tire brands (e.g., "Michelin, Goodyear").

   Example:
   | Registration Number | Postcode | Tire Sizes         | Brands            |
   |---------------------|----------|--------------------|-------------------|
   | ABC1234             | 12345    | 205/55R16, 215/60R16 | Michelin, Goodyear |
   | XYZ5678             | 67890    | 195/65R15, 205/55R16 | Pirelli, Bridgestone |

3. **Share the Google Sheet**:
   - Make sure the **Google Sheet is publicly accessible** to anyone with the link. This is essential for the plugin to fetch data.
   - You can do this by clicking the **Share** button on the Google Sheet and selecting **"Anyone with the link can view"**.

### 2. Obtain Google API Credentials

The plugin uses the **Google Sheets API** to fetch data. Follow these steps to obtain the credentials:

1. Go to the [Google Developer Console](https://console.developers.google.com/).
2. **Create a new project** (e.g., "Tire Recommendation Plugin").
3. **Enable the Google Sheets API**:
   - In the **APIs & Services > Library**, search for "Google Sheets API" and enable it for your project.
4. **Create Service Account Credentials**:
   - Go to **APIs & Services > Credentials** and click on **Create Credentials**.
   - Select **Service Account** and follow the prompts.
   - After creating the service account, download the **JSON file** with your credentials.
   - **Rename the file** to `credentials.json` and place it in your plugin’s root directory.
5. **Update the `GoogleSheets` class**:
   - In the `includes/class-trs-google-sheets.php` file, the plugin reads the `credentials.json` file to authenticate with the Google Sheets API:
   
     ```php
     $client->setAuthConfig(TRS_PLUGIN_DIR . '/credentials.json');
     ```

   Ensure this path is correct, and that the `credentials.json` file is in the plugin directory.

### 3. Set Up the Google Sheet Data

Once your Google Sheets API is connected and the plugin is set up:

- Add tire recommendations to the Google Sheets document. Ensure that each entry contains the vehicle registration number, postcode, tire sizes, and brands.
- You can add as many rows as necessary to store tire recommendations for different vehicles and postcodes.

### 4. Configuring the Plugin

1. The **Google Sheet ID** and **sheet range** are set in the plugin file `includes/class-trs-google-sheets.php`:
   - **Sheet ID**: The unique ID of the Google Sheet (found in the URL).
   - **Sheet Range**: The range to query (e.g., `Sheet1!A:D`).

2. **Test the integration** by entering a **vehicle registration number** and **postcode** on the frontend form. The plugin will retrieve the corresponding tire sizes and brands and display them as WooCommerce products.

== FAQ ==

= How do I add my own vehicle data? =

You can modify the data directly in the **Google Sheets** document used by the plugin. Please refer to the Google Sheets documentation for help on how to add new rows and data.

= Can I change the design of the form? =

Yes, the form is styled using Tailwind CSS. You can modify the CSS or add custom styles.

== Changelog ==

= 1.0.0 =
* Initial release.
