<?php
require(__DIR__ . '/../config_session.php');

if(!canGoofyAdmin()){
    echo boomCode(4);
    die();
}

function goofyPrepareUpload($key){
    if(!isset($_FILES[$key]) || !is_array($_FILES[$key])){
        return false;
    }
    $_FILES['file'] = $_FILES[$key];
    return true;
}

function ensureGoofyDirectory(){
    $path = BOOM_PATH . '/goofy';
    if(!is_dir($path)){
        @mkdir($path, 0755, true);
    }
}

ensureGoofyDirectory();

// Helper: process targets
$target_mode = isset($_POST['target_mode']) ? escape($_POST['target_mode']) : 'all';
$target_mode = in_array($target_mode, ['all', 'room', 'some'], true) ? $target_mode : 'all';
$raw_targets = isset($_POST['targets']) ? $_POST['targets'] : '';
$room = ($target_mode === 'room' && isset($data['user_roomid'])) ? (int) $data['user_roomid'] : 0;

function goofyTargetModeTargets($mode, $raw, $room){
    if($mode !== 'some'){
        return 'all';
    }
    $targets = goofyTargetCsvFromNames($raw, $room);
    if($targets === ''){
        return false;
    }
    return $targets;
}

// Announcement
if(isset($_POST['send_announce'])){
    $text = trim((string) $_POST['announce_text']);
    $dur = (int) $_POST['announce_duration'];
    $drag = (isset($_POST['announce_drag']) && (int) $_POST['announce_drag'] > 0) ? 1 : 0;
    $style = isset($_POST['announce_style']) ? escape($_POST['announce_style']) : 'cosmic';
    $position = isset($_POST['announce_position']) ? escape($_POST['announce_position']) : 'top';
    $style = in_array($style, ['cosmic', 'alert', 'soft'], true) ? $style : 'cosmic';
    $position = in_array($position, ['top', 'center', 'bottom'], true) ? $position : 'top';
    if($text === ''){
        echo boomCode(0);
        die();
    }
    $targets = goofyTargetModeTargets($target_mode, $raw_targets, $room);
    if($targets === false){
        echo boomCode(3);
        die();
    }
    createGoofyEvent('announce', ['text' => $text, 'style' => $style, 'position' => $position], $room, $targets, $drag, $dur);
    echo boomCode(1);
    die();
}

// Jump scare (image + optional mp3)
if(isset($_POST['send_jump'])){
    $dur = (int) $_POST['jump_duration'];
    $drag = (isset($_POST['jump_drag']) && (int) $_POST['jump_drag'] > 0) ? 1 : 0;
    $text = isset($_POST['jump_text']) ? trim((string) $_POST['jump_text']) : '';
    $display = isset($_POST['jump_display']) ? escape($_POST['jump_display']) : 'full';
    $display = in_array($display, ['full', 'card'], true) ? $display : 'full';
    $targets = goofyTargetModeTargets($target_mode, $raw_targets, $room);
    if($targets === false){
        echo boomCode(3);
        die();
    }

    $img_path = '';
    $audio_path = '';

    if(goofyPrepareUpload('jump_image') && !fileError()){
        $info = pathinfo($_FILES['jump_image']['name']);
        $ext = strtolower($info['extension'] ?? '');
        if(isImage($ext)){
            $fname = encodeFile($ext);
            boomMoveFile('goofy/' . $fname);
            if(sourceExist('goofy/' . $fname)){
                $img_path = 'goofy/' . $fname;
            }
        }
    }

    if(goofyPrepareUpload('jump_audio') && !fileError()){
        $info = pathinfo($_FILES['jump_audio']['name']);
        $ext = strtolower($info['extension'] ?? '');
        if(isMusic($ext)){
            $mname = encodeFile($ext);
            boomMoveFile('goofy/' . $mname);
            if(sourceExist('goofy/' . $mname)){
                $audio_path = 'goofy/' . $mname;
            }
        }
    }

    if($img_path === ''){
        echo boomCode(0);
        die();
    }

    $payload = ['image' => $img_path, 'audio' => $audio_path, 'text' => $text, 'display' => $display];
    createGoofyEvent('jumpscare', $payload, $room, $targets, $drag, $dur);
    echo boomCode(1);
    die();
}

// Global audio (MP3) broadcast
if(isset($_POST['send_audio'])){
    $targets = goofyTargetModeTargets($target_mode, $raw_targets, $room);
    if($targets === false){
        echo boomCode(3);
        die();
    }
    $audio_path = '';
    if(goofyPrepareUpload('audio_file') && !fileError()){
        $info = pathinfo($_FILES['audio_file']['name']);
        $ext = strtolower($info['extension'] ?? '');
        if(isMusic($ext)){
            $mname = encodeFile($ext);
            boomMoveFile('goofy/' . $mname);
            if(sourceExist('goofy/' . $mname)){
                $audio_path = 'goofy/' . $mname;
            }
        }
    }
    if($audio_path === ''){
        echo boomCode(0);
        die();
    }

    createGoofyEvent('audio', ['audio' => $audio_path], $room, $targets, 0, 60);
    echo boomCode(1);
    die();
}

// Random goofy burst
if(isset($_POST['send_random'])){
    $dur = (int) $_POST['random_duration'];
    $drag = (isset($_POST['random_drag']) && (int) $_POST['random_drag'] > 0) ? 1 : 0;
    $flags = [
        'effects' => (isset($_POST['random_effect']) && (int) $_POST['random_effect'] > 0) ? 1 : 0,
        'confetti' => (isset($_POST['random_confetti']) && (int) $_POST['random_confetti'] > 0) ? 1 : 0,
        'shake' => (isset($_POST['random_shake']) && (int) $_POST['random_shake'] > 0) ? 1 : 0,
        'spin' => (isset($_POST['random_spin']) && (int) $_POST['random_spin'] > 0) ? 1 : 0,
        'pulse' => (isset($_POST['random_pulse']) && (int) $_POST['random_pulse'] > 0) ? 1 : 0,
        'invert' => (isset($_POST['random_invert']) && (int) $_POST['random_invert'] > 0) ? 1 : 0,
        'blur' => (isset($_POST['random_blur']) && (int) $_POST['random_blur'] > 0) ? 1 : 0,
    ];
    $targets = goofyTargetModeTargets($target_mode, $raw_targets, $room);
    if($targets === false){
        echo boomCode(3);
        die();
    }
    createGoofyEvent('goofy', ['flags' => $flags], $room, $targets, $drag, $dur);
    echo boomCode(1);
    die();
}

echo boomCode(0);
die();
?>
