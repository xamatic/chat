<?php
require('../config_session.php');

if(!canGoofyAdmin()){
	echo 0;
	die();
}
?>
<div class="modal_title goofy_admin_title">
	<div>
		<div class="goofy_admin_kicker">Global tools</div>
		<div>Goofy Event Panel</div>
	</div>
</div>
<div class="modal_content goofy_admin_panel">
	<div class="goofy_admin_grid">
		<div class="goofy_admin_card">
			<div class="goofy_admin_head">
				<i class="fa fa-bullhorn"></i>
				<div>
					<div class="goofy_admin_name">Announcement</div>
					<div class="goofy_admin_hint">Send a styled floating message.</div>
				</div>
			</div>
			<div class="setting_element">
				<textarea id="goofy_announce_text" class="small_textarea full_textarea" maxlength="300" placeholder="Announcement text"></textarea>
			</div>
			<div class="goofy_admin_fields">
				<div>
					<p class="label">Style</p>
					<select id="goofy_announce_style">
						<option value="cosmic">Cosmic</option>
						<option value="alert">Alert</option>
						<option value="soft">Soft</option>
					</select>
				</div>
				<div>
					<p class="label">Position</p>
					<select id="goofy_announce_position">
						<option value="top">Top</option>
						<option value="center">Center</option>
						<option value="bottom">Bottom</option>
					</select>
				</div>
				<div>
					<p class="label">Duration</p>
					<input id="goofy_announce_duration" class="full_input" type="number" min="5" max="120" value="20"/>
				</div>
				<div>
					<p class="label">Draggable</p>
					<select id="goofy_announce_drag"><?php echo onOff(0); ?></select>
				</div>
			</div>
			<div class="setting_element">
				<p class="label">Targets</p>
				<select id="goofy_announce_target_mode">
					<option value="all">All users</option>
					<option value="room">Current room</option>
					<option value="some">Some users</option>
				</select>
				<input id="goofy_announce_targets" class="full_input tmargin5" type="text" maxlength="300" placeholder="name1, name2"/>
			</div>
			<button class="reg_button theme_btn" onclick="sendGoofyAnnouncement();"><i class="fa fa-paper-plane"></i> Send Announcement</button>
		</div>

		<div class="goofy_admin_card">
			<div class="goofy_admin_head">
				<i class="fa fa-image"></i>
				<div>
					<div class="goofy_admin_name">Jump Event</div>
					<div class="goofy_admin_hint">Show an image with optional audio.</div>
				</div>
			</div>
			<div class="goofy_file_row">
				<div>
					<p class="label">Image</p>
					<input id="goofy_jump_image" type="file" accept="image/png,image/jpeg,image/gif,image/webp"/>
				</div>
				<div>
					<p class="label">Optional MP3</p>
					<input id="goofy_jump_audio" type="file" accept="audio/mpeg,.mp3"/>
				</div>
			</div>
			<div class="setting_element">
				<input id="goofy_jump_text" class="full_input" type="text" maxlength="160" placeholder="Optional text overlay"/>
			</div>
			<div class="goofy_admin_fields">
				<div>
					<p class="label">Display</p>
					<select id="goofy_jump_display">
						<option value="full">Full screen</option>
						<option value="card">Floating card</option>
					</select>
				</div>
				<div>
					<p class="label">Duration</p>
					<input id="goofy_jump_duration" class="full_input" type="number" min="5" max="60" value="12"/>
				</div>
				<div>
					<p class="label">Draggable</p>
					<select id="goofy_jump_drag"><?php echo onOff(0); ?></select>
				</div>
			</div>
			<div class="setting_element">
				<p class="label">Targets</p>
				<select id="goofy_jump_target_mode">
					<option value="all">All users</option>
					<option value="room">Current room</option>
					<option value="some">Some users</option>
				</select>
				<input id="goofy_jump_targets" class="full_input tmargin5" type="text" maxlength="300" placeholder="name1, name2"/>
			</div>
			<button class="reg_button theme_btn" onclick="sendGoofyJump();"><i class="fa fa-bolt"></i> Launch Jump Event</button>
		</div>

		<div class="goofy_admin_card">
			<div class="goofy_admin_head">
				<i class="fa fa-volume-up"></i>
				<div>
					<div class="goofy_admin_name">Audio Broadcast</div>
					<div class="goofy_admin_hint">Play an MP3 for selected users.</div>
				</div>
			</div>
			<div class="setting_element">
				<input id="goofy_audio_file" type="file" accept="audio/mpeg,.mp3"/>
			</div>
			<div class="goofy_admin_fields">
				<div>
					<p class="label">Targets</p>
					<select id="goofy_audio_target_mode">
						<option value="all">All users</option>
						<option value="room">Current room</option>
						<option value="some">Some users</option>
					</select>
				</div>
				<div>
					<p class="label">Names</p>
					<input id="goofy_audio_targets" class="full_input" type="text" maxlength="300" placeholder="name1, name2"/>
				</div>
			</div>
			<button class="reg_button theme_btn" onclick="sendGoofyAudio();"><i class="fa fa-play"></i> Broadcast Audio</button>
		</div>

		<div class="goofy_admin_card">
			<div class="goofy_admin_head">
				<i class="fa fa-magic"></i>
				<div>
					<div class="goofy_admin_name">Goofy Burst</div>
					<div class="goofy_admin_hint">Stack visual effects for a short event.</div>
				</div>
			</div>
			<div class="goofy_option_grid">
				<label><input id="goofy_random_effect" type="checkbox" checked/> Random chat effects</label>
				<label><input id="goofy_random_confetti" type="checkbox" checked/> Confetti</label>
				<label><input id="goofy_random_shake" type="checkbox" checked/> Screen shake</label>
				<label><input id="goofy_random_spin" type="checkbox" checked/> Spin avatars</label>
				<label><input id="goofy_random_pulse" type="checkbox"/> Pulse avatars</label>
				<label><input id="goofy_random_invert" type="checkbox"/> Invert screen</label>
				<label><input id="goofy_random_blur" type="checkbox"/> Blur screen</label>
			</div>
			<div class="goofy_admin_fields">
				<div>
					<p class="label">Duration</p>
					<input id="goofy_random_duration" class="full_input" type="number" min="5" max="40" value="10"/>
				</div>
				<div>
					<p class="label">Targets</p>
					<select id="goofy_random_target_mode">
						<option value="all">All users</option>
						<option value="room">Current room</option>
						<option value="some">Some users</option>
					</select>
				</div>
			</div>
			<div class="setting_element">
				<input id="goofy_random_targets" class="full_input" type="text" maxlength="300" placeholder="name1, name2"/>
			</div>
			<button class="reg_button theme_btn" onclick="sendGoofyRandom();"><i class="fa fa-random"></i> Trigger Goofy Burst</button>
		</div>
	</div>
</div>
