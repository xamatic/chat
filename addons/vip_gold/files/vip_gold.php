<?php
$vipGoldEnabled = (int)($addons['custom6'] ?? 0) > 0;
$vipGoldMenuLabel = $lang['vip_tier_gold'] ?? ($lang['vip_buy'] ?? 'Gold VIP');
if($vipGoldEnabled){
?>
<script data-cfasync="false">
openVipGold = function(vipTab){
	var openTab = vipTab || 'gold';
	$.post('addons/vip_gold/system/box/vip_box.php', { 
		vip_tab: openTab,
		token: utk,
		}, function(response) {
			showModal(response, 540);
	});
}
$(document).ready(function(){
	boomAddCss('addons/vip_gold/files/vip_gold.css');
	appLeftMenu('crown', <?php echo json_encode($vipGoldMenuLabel); ?>, 'openVipGold(\'gold\');', 'vip_addons_menu');
});
</script>
<?php } ?>
