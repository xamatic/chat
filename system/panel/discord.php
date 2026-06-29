<?php
require('../config_session.php');

ob_start();
?>
<div class="left_keep">
	<div class="pad10">
		<div class="discord_embed_wrap" style="width:100%;height:calc(100vh - 180px);min-height:520px;border-radius:8px;overflow:hidden;">
			<widgetbot
				server="1447474462227431597"
				channel="1458310339329261812"
				shard="https://emerald.widgetbot.io"
				style="width:100%;height:100%;display:block;">
			</widgetbot>
			<script src="https://cdn.jsdelivr.net/npm/@widgetbot/html-embed" async defer></script>
		</div>
		<a class="discord_embed_link" href="https://discord.gg/c62QZzpypf" target="_blank" rel="noopener noreferrer">Join the discord server!</a>
	</div>
</div>
<?php
$res['content'] = ob_get_clean();
$res['title'] = 'Discord';
echo boomCode(1, $res);
?>