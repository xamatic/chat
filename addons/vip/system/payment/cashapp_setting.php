<?php
function cashAppGetConfig() {
	$config = array('env' => '', 'token' => '', 'location' => '');

	$file = __DIR__ . '/cashapp_credentials.php';
	if(file_exists($file)) {
		require($file);
		if(isset($cashapp_config) && is_array($cashapp_config)) {
			$config = array_merge($config, $cashapp_config);
		}
	}

	$envValue = getenv('CASHAPP_ENV');
	if($envValue !== false && $envValue !== '') {
		$config['env'] = $envValue;
	}

	$tokenValue = getenv('CASHAPP_ACCESS_TOKEN');
	if($tokenValue !== false && $tokenValue !== '') {
		$config['token'] = $tokenValue;
	}

	$locationValue = getenv('CASHAPP_LOCATION_ID');
	if($locationValue !== false && $locationValue !== '') {
		$config['location'] = $locationValue;
	}

	return $config;
}

function cashAppApiBase(){
	$config = cashAppGetConfig();
	$env = strtolower(trim((string) $config['env']));
	if($env === 'sandbox'){
		return 'https://connect.squareupsandbox.com';
	}
	return 'https://connect.squareup.com';
}

function cashAppAccessToken(){
	$config = cashAppGetConfig();
	return trim((string) $config['token']);
}

function cashAppLocationId(){
	$config = cashAppGetConfig();
	return trim((string) $config['location']);
}

function cashAppReady(){
	return cashAppAccessToken() !== '' && cashAppLocationId() !== '';
}

function cashAppApiRequest($method, $endpoint, $payload = null){
	$token = cashAppAccessToken();
	if($token === ''){
		return array('ok' => false, 'status' => 0, 'body' => null);
	}

	$url = rtrim(cashAppApiBase(), '/') . '/' . ltrim($endpoint, '/');
	$headers = array(
		'Authorization: Bearer ' . $token,
		'Content-Type: application/json',
		'Accept: application/json',
		'Square-Version: 2024-09-19'
	);

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_TIMEOUT, 20);
	if($payload !== null){
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
	}

	$response = curl_exec($ch);
	$status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$err = curl_error($ch);
	curl_close($ch);

	if($response === false || $err){
		return array('ok' => false, 'status' => $status, 'body' => null);
	}

	$decoded = json_decode($response, true);
	if(!is_array($decoded)){
		$decoded = null;
	}

	return array(
		'ok' => ($status >= 200 && $status < 300),
		'status' => $status,
		'body' => $decoded
	);
}
