<div id="login_wrap" class="back_login">
	<div class="login-bg" aria-hidden="true">
		<div class="gradient-bg">
			<svg xmlns="http://www.w3.org/2000/svg">
				<defs>
					<filter id="goo">
						<feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur" />
						<feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 18 -8" result="goo" />
						<feBlend in="SourceGraphic" in2="goo" />
					</filter>
				</defs>
			</svg>
			<div class="gradients-container">
				<div class="g1"></div>
				<div class="g2"></div>
				<div class="g3"></div>
				<div class="g4"></div>
				<div class="g5"></div>
				<div class="interactive"></div>
			</div>
		</div>
	</div>
	<div id="intro_top" class="btable">
		<div class="bcell_mid">

			<div id="login_all" class="pad30">
				<div class="centered_element">
					<img id="login_logo" src="<?php echo getLogo(); ?>"/>
				</div>
				<div class="login_text vpad20 centered_element">
					<p class="bold text_xlarge bpad10"><?php echo $lang['left_title']; ?></p>
					<p class="text_med"><?php echo $lang['left_welcome']; ?></p>
				</div>
				<div class="login_split_wrap vpad20">
					<div class="login_split">
						<div class="login_rules">
							<div class="login_panel_header">
								<i class="fa fa-gavel"></i>
								<span><?php echo $lang['rules']; ?></span>
							</div>
							<div class="login_rules_body">
								<?php echo loadPageData('rules'); ?>
							</div>
						</div>
						<div class="login_panel">
							<div class="login_panel_header">
								<i class="fa fa-sign-in"></i>
								<span><?php echo $lang['login']; ?></span>
							</div>
							<?php if(bridgeMode(0)){ ?>
							<div id="login_form_box" class="login_form_box">
								<div class="login_form_fields">
									<div class="bpad15">
										<input id="user_username" class="user_username full_input" type="text" maxlength="50" name="username" placeholder="<?php echo $lang['name_email']; ?>">
									</div>
									<div class="bpad15">
										<input id="user_password" class="full_input" maxlength="30" type="password" name="password" placeholder="<?php echo $lang['password']; ?>">
									</div>
								</div>
								<div class="login_form_actions">
									<button onclick="sendLogin();" type="button" class="theme_btn full_button large_button"><i class="fa fa-sign-in"></i> <?php echo $lang['login']; ?></button>
									<div class="forgot_pass_elem tpad15">
										<p onclick="getRecovery();" class="forgot_password text_small bclick sub_text"><?php echo $lang['forgot']; ?></p>
									</div>
								</div>
							</div>
							<?php } ?>
							<?php if(bridgeMode(1)){ ?>
							<button onclick="bridgeLogin('<?php echo getChatPath(); ?>');" class="intro_login_btn large_button_rounded ok_btn btnshadow"><i class="fa fa-user"></i> <?php echo $lang['enter_now']; ?></button>
							<?php } ?>
							<div class="login_panel_footer">
								<?php if(allowGuest()){ ?>
								<button onclick="getGuestLogin();" class="intro_guest_btn large_button_rounded default_btn btnshadow"><?php echo $lang['guest_login']; ?></button>
								<?php } ?>
								<?php if(registration()){ ?>
								<div class="login_register">
									<p class="text_xsmall"><?php echo $lang['new_here']; ?></p>
									<p onclick="getRegistration();" class="text_med bold bclick tpad5"><?php echo $lang['register_now']; ?></p>
								</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
				
				<!-- Recently active users -->
				
				</div>
				<div onclick="getLanguage();" class="bclick btable" id="intro_lang">
					<div class="bcell_mid centered_element">
						<img alt="flag" class="intro_lang" src="system/language/<?php echo BOOM_LANG; ?>/flag.png"/>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="section">
		<div class="section_content">
			<!-- add your content here if you need to add more for seo -->
		</div>
	</div>
	<?php echo boomTemplate('element/page_footer'); ?>
</div>
<?php echo boomTemplate('element/cookie'); ?>
<script data-cfasync="false" src="js/function_login.js<?php echo $bbfv; ?>"></script>
<script data-cfasync="false" src="js/function_active.js<?php echo $bbfv; ?>"></script>
<script>
	document.addEventListener('DOMContentLoaded', function () {
		var interBubble = document.querySelector('.interactive');
		if (!interBubble) {
			return;
		}
		var curX = 0;
		var curY = 0;
		var tgX = 0;
		var tgY = 0;

		function move() {
			curX += (tgX - curX) / 20;
			curY += (tgY - curY) / 20;
			interBubble.style.transform = 'translate(' + Math.round(curX) + 'px, ' + Math.round(curY) + 'px)';
			requestAnimationFrame(move);
		}

		window.addEventListener('mousemove', function (event) {
			tgX = event.clientX;
			tgY = event.clientY;
		});

		move();
	});
</script>