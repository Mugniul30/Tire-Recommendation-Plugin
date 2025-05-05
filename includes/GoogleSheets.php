<?php
namespace TRS;

use Google_Client;
use Google_Service_Sheets;

class GoogleSheets
{

    private $sheet_id    = '1JrdtufiBEkZHzK-SrWDiGo7jx-hoR8ktHwjO4joLTyI';
    private $sheet_range = 'Sheet1!A:D';

    public function get_tire_data($registration, $postcode)
    {
        $client = new Google_Client();
        $client->setApplicationName('Tire Recommendation');
        $client->setScopes([Google_Service_Sheets::SPREADSHEETS_READONLY]);
        $client->setAuthConfig(TIRE_RECOMMENDATION_PLUGIN_PATH . '/credentials.json');
        $service = new Google_Service_Sheets($client);

        $response = $service->spreadsheets_values->get($this->sheet_id, $this->sheet_range);
        $rows     = $response->getValues();

        foreach ($rows as $row) {
            if (count($row) < 4) {
                continue;
            }

            if (strtolower(trim($row[0])) === strtolower($registration) && strtolower(trim($row[1])) === strtolower($postcode)) {
                return [
                    'sizes'  => $row[2],
                    'brands' => $row[3],
                ];
            }
        }

        return false;
    }
}
