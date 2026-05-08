<?php
session_start();
$load_addons = 'vip';
$boom_access = 0;

require('../../../../system/database.php');
require('../../../../system/function.php');
require('../../../../system/function_all.php');
require('../../../../system/settings.php');
require('../../../../system/redis.php');
require('../addons_function.php');
require('cashapp_setting.php');

$ref = isset($_SESSION['cashapp_ref']) ? $_SESSION['cashapp_ref'] : $setting['domain'];

if(!isset($_COOKIE[BOOM_PREFIX . 'userid']) || !isset($_COOKIE[BOOM_PREFIX . 'utk'])){
	header('location: ' . $ref);
	die();
}

$mysqli = @new mysqli(BOOM_DHOST, BOOM_DUSER, BOOM_DPASS, BOOM_DNAME);
if(mysqli_connect_errno()){
	header('location: ' . $ref);
	die();
}

$pass = escape($_COOKIE[BOOM_PREFIX . 'utk']);
$ident = escape($_COOKIE[BOOM_PREFIX . 'userid']);
$get_data = $mysqli->query("SELECT boom_setting.*, boom_users.*, boom_addons.* FROM boom_users, boom_setting, boom_addons WHERE boom_users.user_id = '$ident' AND boom_users.user_password = '$pass' AND boom_setting.id = '1' AND boom_addons.addons = '$load_addons'");
if($get_data->num_rows > 0){
	$data = $get_data->fetch_assoc();
	$boom_access = 1;
}
else {
	header('location: ' . $ref);
	die();
}

$addons = addonsDetails($load_addons);
require(addonsLang($load_addons));

if(!boomLogged() || !cashAppReady()){
	header('location: ' . $ref);
	die();
}

$plan = isset($_GET['plan']) ? escape($_GET['plan']) : 0;
if(!vipValidPlan($plan) || (int) $plan < 6){
	header('location: ' . $ref);
	die();
}

$expectedAmount = (int) round(((float) vipPrice($plan)) * 100);
$expectedCurrency = strtoupper($addons['custom7']);

$orderId = '';
if(isset($_GET['orderId'])){
	$orderId = escape($_GET['orderId']);
}
if($orderId == '' && isset($_GET['transactionId'])){
	$orderId = escape($_GET['transactionId']);
}
if($orderId == '' && isset($_GET['payment_id'])){
	$orderId = escape($_GET['payment_id']);
}

if($orderId == ''){
	header('location: ' . $ref);
	die();
}

$existing = $mysqli->query("SELECT id FROM vip_transaction WHERE order_id = '$orderId' LIMIT 1");
if($existing->num_rows > 0){
	header('location: ' . $ref);
	die();
}

$orderResponse = cashAppApiRequest('GET', '/v2/orders/' . $orderId);
if(!$orderResponse['ok'] || empty($orderResponse['body']['order'])){
	header('location: ' . $ref);
	die();
}

$order = $orderResponse['body']['order'];
$status = isset($order['state']) ? strtolower($order['state']) : 'failed';
$money = isset($order['total_money']) ? $order['total_money'] : array();
$amount = isset($money['amount']) ? (int) $money['amount'] : 0;
$currency = isset($money['currency']) ? strtoupper($money['currency']) : '';

if($status === 'completed' && $amount === $expectedAmount && $currency === $expectedCurrency){
	recordVip($plan);
}
else {
	vipFail($plan);
	$status = 'failed';
}

$invoice = isset($order['reference_id']) && $order['reference_id'] != '' ? $order['reference_id'] : ('cashapp_' . $orderId);
$email = '';
if(isset($data['user_email'])){
	$email = $data['user_email'];
}

$sale = array(
	'user'=> $data['user_id'],
	'userp'=> '',
	'plan'=> $plan,
	'price'=> vipPrice($plan),
	'currency'=> $expectedCurrency,
	'gateway'=> 'cashapp',
	'invoice'=> $invoice,
	'order_id'=> $orderId,
	'email'=> $email,
	'vdate'=> time(),
	'status'=> $status,
);

vipTransaction($sale);

unset($_SESSION['cashapp_plan']);
unset($_SESSION['cashapp_idempotency']);

header('location: ' . $ref);
?>