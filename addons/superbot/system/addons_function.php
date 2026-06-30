<?php
function superbot($string){
	global $data, $addons;
	if(stripos($string, '%user%') !== false){
		$string = str_replace('%user%', $data['user_name'], $string);
	}
	if(stripos($string, '%bot%') !== false){
		$string = str_replace('%bot%', $addons['bot_name'], $string);
	}
	if(stripos($string, '%time%') !== false){
		$string = str_replace('%time%', date("H:i"), $string);
	}
	if(stripos($string, '%members%') !== false){
		$string = str_replace('%members%', countRegister(), $string);
	}
	if(stripos($string, '%male%') !== false){
		$string = str_replace('%male%', countMale(), $string);
	}
	if(stripos($string, '%female%') !== false){
		$string = str_replace('%female%', countFemale(), $string);
	}
	return softEscape($string);
}
function superbotReg($string){
	global $mysqli, $addons;
	$result = '';
	$search = superbotSearch($string);
	if($search != ''){
		$sbot = $mysqli->query("SELECT * FROM superbot_data WHERE superbot_question = '$search' ORDER BY RAND() LIMIT 1");
		if($sbot->num_rows > 0){
			$search_result = $sbot->fetch_array(MYSQLI_BOTH);
			$result = $search_result['superbot_answer'];
		}
	}
	if($result == ''){
		$result = superbotCommand($search);
	}
	return $result;
}
function superbotSearch($string){
	global $addons;
	$search = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
	$search = str_ireplace(array('@' . $addons['bot_name'], $addons['bot_name'], '@superbot', 'superbot'), '', $search);
	$search = str_replace(array('?', '!', "\r", "\n", "\t"), ' ', $search);
	$search = preg_replace('!\s+!', ' ', $search);
	return trim($search);
}
function superbotCommand($search){
	$ask = trim($search);
	$low = strtolower($ask);
	$help = '%user%, try these commands with %bot%: help, time, date, stats, flip, roll, roll 2d6, choose red, blue, green, or 8ball should I stay?';
	if($ask == '' || $low == 'help' || $low == 'commands' || $low == 'ai' || $low == 'ask'){
		return $help;
	}
	if($low == 'time'){
		return '%user%, it is %time% for the chat server.';
	}
	if($low == 'date'){
		return '%user%, today is ' . date('F j, Y') . '.';
	}
	if($low == 'stats' || $low == 'members'){
		return 'There are %members% registered members here right now.';
	}
	if($low == 'flip' || $low == 'coin' || $low == 'coin flip'){
		return '%user%, I flipped ' . (mt_rand(0, 1) ? 'heads' : 'tails') . '.';
	}
	if(preg_match('/^roll(?:\s+(\d+))?$/i', $ask, $match)){
		$max = isset($match[1]) ? min(max((int) $match[1], 2), 1000) : 6;
		return '%user%, you rolled ' . mt_rand(1, $max) . ' out of ' . $max . '.';
	}
	if(preg_match('/^roll\s+(\d+)d(\d+)$/i', $ask, $match)){
		$dice = min(max((int) $match[1], 1), 20);
		$sides = min(max((int) $match[2], 2), 1000);
		$total = 0;
		$rolls = array();
		for($i = 0; $i < $dice; $i++){
			$roll = mt_rand(1, $sides);
			$total += $roll;
			$rolls[] = $roll;
		}
		return '%user%, you rolled ' . $total . ' [' . implode(', ', $rolls) . '].';
	}
	if(preg_match('/^(choose|pick)\s+(.+)$/i', $ask, $match)){
		$options = preg_split('/\s*(,|\||\bor\b)\s*/i', $match[2], -1, PREG_SPLIT_NO_EMPTY);
		$options = array_values(array_filter(array_map('trim', $options)));
		if(count($options) > 1){
			return '%user%, I choose ' . $options[array_rand($options)] . '.';
		}
	}
	if(preg_match('/^(8ball|eightball|magic 8ball)\b/i', $ask)){
		$answers = array('yes', 'no', 'maybe', 'ask again soon', 'it looks likely', 'I would not count on it', 'absolutely', 'not today');
		return '%user%, my answer is: ' . $answers[array_rand($answers)] . '.';
	}
	if(preg_match('/^(ai|ask)\s+(.+)$/i', $ask, $match)){
		return '%user%, I heard you. I can answer trained Superbot prompts and run chat commands, but a live AI model is not connected here yet.';
	}
	return '%user%, I heard you. I can answer trained prompts or run commands like help, time, roll, flip, choose, 8ball, and stats.';
}
function superbotParse($result){
	$result = superbot($result);
	$result = emoprocess($result);
	$result = linking($result);
	$result = emoticon($result);
	return $result;
}
function superbotQuestion($question){
	$question = escape($_POST['question']);
	$question = str_replace(array('?'), '', $question);
	$question = preg_replace('!\s+!', ' ', $question);
	$question = trim($question);
	return $question;
}
function countRegister(){
	global $mysqli;
	$count = $mysqli->query("SELECT count(user_id) FROM boom_users WHERE user_id > 0");
	$cal = $count->fetch_array(MYSQLI_BOTH);
	return $cal[0];
}
function countFemale(){
	global $mysqli;
	$count = $mysqli->query("SELECT count(user_id) FROM boom_users WHERE user_sex = 2");
	$cal = $count->fetch_array(MYSQLI_BOTH);
	return $cal[0];
}
function countMale(){
	global $mysqli;
	$count = $mysqli->query("SELECT count(user_id) FROM boom_users WHERE user_sex = 1");
	$cal = $count->fetch_array(MYSQLI_BOTH);
	return $cal[0];
}
?>
