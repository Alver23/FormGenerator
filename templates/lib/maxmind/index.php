<?PHP
function get_ip_info($ip) {
	ob_start();
	require_once(dirname(__FILE__) . "/geoipcity.inc.php");
	require_once(dirname(__FILE__) . "/geoipregionvars.php");
	$gi = geoip_open(dirname(__FILE__) . "/GeoLiteCity.dat", GEOIP_STANDARD);
	$record = geoip_record_by_addr($gi, $ip);
	if ($record === NULL) {
		return false;
	}
	if (strpos($ip, "190.145.78") !== false) {
		$array = array("COUNTRY_CODE" => "CO", "COUNTRY_NAME" => "Colombia",
				"REGION" => "Valle del Cauca", "CITY" => "Cali",
				"LATITUD" => str_replace(",", ".", "3.412224"),
				"LONGITUD" => str_replace(",", ".", "-76.547563"));
	} else {
		$array = array("COUNTRY_CODE" => $record->country_code,
				"COUNTRY_NAME" => $record->country_name,
				"REGION" => @$GEOIP_REGION_NAME[$record->country_code][$record
						->region], "CITY" => $record->city,
				"LATITUD" => str_replace(",", ".", $record->latitude),
				"LONGITUD" => str_replace(",", ".", $record->longitude));
	}
	/*
	echo "<pre>";
	print $record->country_code . " " . $record->country_code3 . " " . $record->country_name . "\n";
	print $record->region . " " . $GEOIP_REGION_NAME[$record->country_code][$record->region] . "\n";
	print $record->city . "\n";
	print $record->postal_code . "\n";
	print $record->latitude . "\n";
	print $record->longitude . "\n";
	print $record->metro_code . "\n";
	print $record->area_code . "\n";
	echo "</pre>";
	 */
	geoip_close($gi);
	ob_end_clean();
	return $array;
}
