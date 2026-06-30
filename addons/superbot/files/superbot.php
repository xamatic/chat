<?php if(boomAllow($addons['addons_access'])){ ?>
<script data-cfasync="false">
var requestType = 'reg';
var superbot = <?php echo json_encode($addons['bot_name']); ?>;
var superLow = superbot.toLowerCase();
var superbotId = '<?php echo (int) $addons['bot_id']; ?>';
var checkbot = '';
var privBot = '';

superbotMentioned = function(message){
	var text = message.toLowerCase();
	return text.indexOf(superLow) !== -1 || text.indexOf('superbot') !== -1;
};
sendSuperbotMain = function(){
	$.post('addons/superbot/system/superbot_main.php', { 
		search: checkbot,
		name: superbot,
		type: requestType,
		}, function(response) {

	});
};
sendSuperbotPrivate = function(){
	$.post('addons/superbot/system/superbot_private.php', { 
		search: privBot,
		name: superbot,
		bid: currentPrivate,
		}, function(response) {

	});
};

$(document).ready(function(){

	$('#main_input').submit(function(event){
		checkbot = $('#content').val();
		if(superbotMentioned(checkbot)){
			setTimeout(sendSuperbotMain, 1000);
		}
		else {
			checkbot = '';
		}
	});
	$('#private_input').submit(function(event){
		privBot = $('#message_content').val();
		if(privBot == '' || /^\s+$/.test($('#message_content').val()) ){
			event.preventDefault();
		}
		else {
			if(currentPrivate == superbotId){
				setTimeout(sendSuperbotPrivate, 1000);
			}
			else {
				privBot = '';
			}
		}
	});
});
</script>
<?php } ?>
