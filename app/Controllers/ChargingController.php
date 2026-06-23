<?php

namespace App\Controllers;

class ChargingController extends BaseController
{
    public function index()
    {
        $db   = \Config\Database::connect();
        $city = trim((string) $this->request->getGet('city'));
        if (strtolower($city) === 'all') $city = '';

        // Distinct cities with at least one operational charging station
        $citiesResult = $db->table('charging_stations')
            ->select('city')
            ->where('status', 'operational')
            ->where('city IS NOT NULL', null, false)
            ->where('city !=', '')
            ->groupBy('city')
            ->orderBy('city', 'ASC')
            ->get()
            ->getResultArray();
        $cities = array_column($citiesResult, 'city');

        $query = $db->table('charging_stations')
            ->where('status', 'operational')
            ->orderBy('name', 'ASC');

        if ($city !== '') {
            $query->where('city', $city);
        }

        $stations = $query->get()->getResultArray();

        $metaTitle = $city
            ? 'EV Charging Stations in ' . $city . ' | Charj.in'
            : 'EV Charging Stations in India - Find Fast & Slow Chargers | Charj.in';
        $metaDesc  = $city
            ? 'Find EV charging stations in ' . $city . '. Locations, charging speed, connectors and operating hours.'
            : 'Find electric vehicle charging stations near you across India. Locate fast chargers, slow chargers and public EV charging points.';

        return $this->render('charging/index', [
            'stations'         => $stations,
            'cities'           => $cities,
            'activeCity'       => $city,
            'meta_title'       => $metaTitle,
            'meta_description' => $metaDesc,
        ]);
    }

    // GET /charging-stations/api?lat=LAT&lng=LNG&city=CITY
    public function api()
    {
        $lat  = (float) $this->request->getGet('lat');
        $lng  = (float) $this->request->getGet('lng');
        $city = trim((string) $this->request->getGet('city'));
        $key  = env('OCM_API_KEY', '');

        // City-to-lat/lng map for India
        $cityCoords = [
            'delhi'     => [28.6139, 77.2090],
            'mumbai'    => [19.0760, 72.8777],
            'bangalore' => [12.9716, 77.5946],
            'bengaluru' => [12.9716, 77.5946],
            'pune'      => [18.5204, 73.8567],
            'hyderabad' => [17.3850, 78.4867],
            'chennai'   => [13.0827, 80.2707],
            'ahmedabad' => [23.0225, 72.5714],
            'kolkata'   => [22.5726, 88.3639],
            'jaipur'    => [26.9124, 75.7873],
            'surat'     => [21.1702, 72.8311],
            'lucknow'   => [26.8467, 80.9462],
            'noida'     => [28.5355, 77.3910],
            'gurgaon'   => [28.4595, 77.0266],
            'gurugram'  => [28.4595, 77.0266],
        ];

        // Resolve lat/lng
        if ($lat == 0 && $lng == 0 && $city) {
            $coords = $cityCoords[strtolower($city)] ?? null;
            if ($coords) { $lat = $coords[0]; $lng = $coords[1]; }
        }

        if ($lat == 0 && $lng == 0) {
            // Default to Delhi
            $lat = 28.6139; $lng = 77.2090;
        }

        // Fetch from OpenChargeMap
        $apiUrl = 'https://api.openchargemap.io/v3/poi/?' . http_build_query([
            'output'       => 'json',
            'maxresults'   => 50,
            'latitude'     => $lat,
            'longitude'    => $lng,
            'distance'     => 15,
            'distanceunit' => 'KM',
            'countrycode'  => 'IN',
            'compact'      => true,
            'verbose'      => false,
            'key'          => $key ?: 'a3e2b4a3-b83e-4f5a-8a3c-3c3f2d5c1234', // community key
        ]);

        $ch = curl_init($apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 8,
            CURLOPT_HTTPHEADER     => ['Accept: application/json'],
            CURLOPT_USERAGENT      => 'CharjIn/1.0 (info@charj.in)',
        ]);
        $body     = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (!$body || $httpCode !== 200) {
            return $this->response->setJSON(['success' => false, 'stations' => [], 'source' => 'api_error']);
        }

        $raw = json_decode($body, true) ?? [];

        // Normalise to our station shape
        $stations = [];
        foreach ($raw as $s) {
            $addr  = $s['AddressInfo'] ?? [];
            $conns = $s['Connections'] ?? [];

            $connTypes = [];
            $maxKw     = 0;
            foreach ($conns as $c) {
                $ct = $c['ConnectionType']['Title'] ?? '';
                if ($ct && !in_array($ct, $connTypes)) $connTypes[] = $ct;
                $kw = (float) ($c['PowerKW'] ?? 0);
                if ($kw > $maxKw) $maxKw = $kw;
            }

            $speed = 'slow';
            if ($maxKw >= 50) $speed = 'rapid';
            elseif ($maxKw >= 11) $speed = 'fast';

            $stations[] = [
                'id'              => $s['ID'] ?? 0,
                'name'            => $addr['Title'] ?? 'EV Charging Station',
                'address'         => trim(($addr['AddressLine1'] ?? '') . ' ' . ($addr['AddressLine2'] ?? '')),
                'city'            => $addr['Town'] ?? $addr['StateOrProvince'] ?? '',
                'lat'             => $addr['Latitude'] ?? $lat,
                'lng'             => $addr['Longitude'] ?? $lng,
                'operator'        => ($s['OperatorInfo']['Title'] ?? '') ?: 'Unknown Operator',
                'charging_speed'  => $speed,
                'max_kw'          => $maxKw,
                'connector_types' => $connTypes,
                'total_ports'     => count($conns),
                'is_open_24x7'    => ($s['AddressInfo']['AccessComments'] ?? '') !== '',
                'price_per_kwh'   => null,
                'status'          => 'operational',
                'source'          => 'ocm',
            ];
        }

        return $this->response->setJSON([
            'success'  => true,
            'stations' => $stations,
            'source'   => 'openchargemap',
            'lat'      => $lat,
            'lng'      => $lng,
        ]);
    }

    public function city(string $city)
    {
        $db = \Config\Database::connect();

        $stations = $db->table('charging_stations')
            ->where('status', 'operational')
            ->where('city', $city)
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();

        // Distinct cities list for nav
        $citiesResult = $db->table('charging_stations')
            ->select('city')
            ->where('status', 'operational')
            ->where('city IS NOT NULL', null, false)
            ->where('city !=', '')
            ->groupBy('city')
            ->orderBy('city', 'ASC')
            ->get()
            ->getResultArray();
        $cities = array_column($citiesResult, 'city');

        $metaTitle = 'EV Charging Stations in ' . $city . ' | Charj.in';
        $metaDesc  = 'Find all electric vehicle charging stations in ' . $city . '. Get addresses, charging speeds, connector types and operating hours.';

        return $this->render('charging/city', [
            'stations'         => $stations,
            'cities'           => $cities,
            'activeCity'       => $city,
            'meta_title'       => $metaTitle,
            'meta_description' => $metaDesc,
        ]);
    }
}
