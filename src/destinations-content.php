<?php
declare(strict_types=1);

/* =========================================================================
   Destination content pack (USA, Canada, UK, Italy, Spain, France)
   -------------------------------------------------------------------------
   Single source of truth is destinations-content.json (also consumed by the
   Node preview mirror). This loader decodes it and attaches a resolved city
   list to each country for template convenience.
   ========================================================================= */

$jsonPath = __DIR__ . '/destinations-content.json';
$data = is_file($jsonPath)
    ? json_decode((string) file_get_contents($jsonPath), true)
    : null;

if (!is_array($data) || !isset($data['countries'], $data['cities'])) {
    return ['countries' => [], 'cities' => []];
}

foreach ($data['countries'] as $slug => &$country) {
    $country['slug'] = $country['slug'] ?? $slug;
    $resolved = [];
    foreach ($country['city_slugs'] ?? [] as $citySlug) {
        if (isset($data['cities'][$citySlug])) {
            $resolved[] = $data['cities'][$citySlug];
        }
    }
    $country['cities'] = $resolved;
}
unset($country);

return $data;
