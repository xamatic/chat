<?php
require(__DIR__ . '/../../config_admin.php');

if(!boomAllow(100)){
	die();
}
$ai_provider = isset($setting['ai_provider']) ? $setting['ai_provider'] : 'auto';
$mistral_key = isset($setting['mistral_key']) ? $setting['mistral_key'] : '';
$mistral_model = isset($setting['mistral_model']) ? $setting['mistral_model'] : '';
$ai_provider_label = isset($lang['ai_provider']) ? $lang['ai_provider'] : 'AI provider';
$mistral_label = isset($lang['mistral']) ? $lang['mistral'] : 'Mistral';
$mistral_key_label = isset($lang['mistral_key']) ? $lang['mistral_key'] : 'Mistral API key';
$mistral_model_label = isset($lang['mistral_model']) ? $lang['mistral_model'] : 'Mistral model';
?>
<?php echo elementTitle($lang['ai']); ?>
<div class="page_full">
	<div>		
		<div class="tab_menu">
			<ul>
				<li class="tab_menu_item tab_selected" data="aitab" data-z="openai"><?php echo $lang['openai']; ?></li>
			</ul>
		</div>
	</div>
	<div class="page_element">
		<div id="aitab">
			<div id="openai" class="tab_zone">
				<div class="form_content">
					<div class="setting_element">
						<p class="label"><?php echo $ai_provider_label; ?></p>
						<select id="set_ai_provider">
							<option value="auto" <?php echo selCurrent($ai_provider, 'auto'); ?>>Auto</option>
							<option value="openai" <?php echo selCurrent($ai_provider, 'openai'); ?>><?php echo $lang['openai']; ?></option>
							<option value="mistral" <?php echo selCurrent($ai_provider, 'mistral'); ?>><?php echo $mistral_label; ?></option>
						</select>
					</div>
					<div class="setting_element">
						<p class="label"><?php echo $lang['openai_key']; ?> <?php echo createInfo('openai'); ?></p>
						<input id="set_openai_key" class="full_input" value="<?php echo $setting['openai_key']; ?>" type="text"/>
					</div>
					<div class="setting_element">
						<p class="label"><?php echo $mistral_key_label; ?></p>
						<input id="set_mistral_key" class="full_input" value="<?php echo $mistral_key; ?>" type="text"/>
					</div>
					<div class="setting_element">
						<p class="label"><?php echo $mistral_model_label; ?></p>
						<input id="set_mistral_model" class="full_input" value="<?php echo $mistral_model; ?>" type="text"/>
					</div>
				</div>
				<div class="setting_element ">
					<p class="label"><?php echo $lang['img_mod']; ?></p>
					<select id="set_img_mod">
						<?php echo onOff($setting['img_mod']); ?>
					</select>
				</div>
				<div class="form_control">
					<button onclick="saveAdminAi();" type="button" class="reg_button theme_btn"><?php echo $lang['save']; ?></button>
					<button onclick="openModCat();" type="button" class="reg_button default_btn"><?php echo $lang['edit_filter']; ?></button>
				</div>
			</div>
		</div>
	</div>
</div>
