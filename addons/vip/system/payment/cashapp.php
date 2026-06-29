<?php
session_start();
$load_addons = 'vip';
require('../../../../system/config_addons.php');
require('cashapp_setting.php');

if(!isset($_POST['plan'])){
	echo 0;
	die();
}

$plan = escape($_POST['plan']);
$_SESSION['cashapp_ref'] = escape($_SERVER['HTTP_REFERER']);

if(boomAllow(2)){
	echo 0;
	die();
}
if(isGuest($data)){
	echo 0;
	die();
}
if(!vipValidPlan($plan)){
	echo 0;
	die();
}
if((int) $plan < 6){
	echo 0;
	die();
}
if(!cashAppReady()){
	echo 0;
	die();
}

$total = vipPrice($plan);
$name = vipPlanName($plan);
if($name == '' || $total <= 0){
	echo 0;
	die();
}

$amount = (int) round(((float) $total) * 100);
if($amount < 1){
	echo 0;
	die();
}

$idempotency = uniqid('vip_cashapp_', true);
$_SESSION['cashapp_plan'] = $plan;
$_SESSION['cashapp_idempotency'] = $idempotency;

$returnUrl = $setting['domain'] . '/addons/vip/system/payment/cashapp_transaction.php';
$payload = array(
	'idempotency_key' => $idempotency,
	'quick_pay' => array(
		'name' => $name,
		'price_money' => array(
			'amount' => $amount,
			'currency' => strtoupper($addons['custom7'])
		),
		'location_id' => cashAppLocationId()
	),
	'checkout_options' => array(
		'redirect_url' => $returnUrl . '?plan=' . $plan,
		'ask_for_shipping_address' => false
	)
);

$request = cashAppApiRequest('POST', '/v2/online-checkout/payment-links', $payload);
if(!$request['ok'] || empty($request['body']['payment_link']['url'])){
	echo 0;
	die();
}

echo $request['body']['payment_link']['url'];
?>