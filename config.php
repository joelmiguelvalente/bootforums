<?php
$config = array(
	"title"=>"BootForums",
	"desc"=>"Open Source Bootstrap themed forum for php 5.6+ - Flat file, no Database required!",
	"captcha" => array(
    					'min_length' => 6,
    					'max_length' => 8,
    					'backgrounds' => array('backgrounds/45-degree-fabric.png','backgrounds/45-degree-fabric.png','backgrounds/45-degree-fabric.png','backgrounds/45-degree-fabric.png'),
    					'fonts' => array('fonts/times_new_yorker.ttf'),
    					'characters' => 'ABCDEFGHJKLMNPRSTUVWXYZabcdefghjkmnprstuvwxyz23456789',
    					'min_font_size' => 15,
    					'max_font_size' => 20,
    					'color' => '#666',
    					'angle_min' => 0,
    					'angle_max' => 20,
    					'shadow' => true,
    					'shadow_color' => '#fff',
    					'shadow_offset_x' => -1,
    					'shadow_offset_y' => 1
					),
	"ssl" => false
	);
?>