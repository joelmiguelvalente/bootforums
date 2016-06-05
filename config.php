<?php
$config = array(
	"title"=>"BootForums", //Title for the forums
	"desc"=>"Open Source Bootstrap themed forum for php 5.6+ - Flat file, no Database required!", //Description for the site in html (Not displayed on page, but in search engines.)
	"user_data" => "/var/forum_data/users", //Folder to store user data in, make sure to give proper permissions (0744) and the owner of the folder must be apache's user (Or nginx's user)
	"thread_data" => "/var/forum_data/threads", //Folder to store thread data in, make sure to give proper permissions (0744) and the owner of the folder must be apache's user (Or nginx's user)
	"captcha" => array( //Configure Captcha settings for registration.
    					'min_length' => 4,
    					'max_length' => 4,
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
	"ssl" => false, //Eventually this will force SSL on the site, but right now does nothing.
	"announce" => "This forum is currently in beta, but please enjoy your stay!" //Announcement to show on home page. set to "" if you want to disable. Supports HTML if needed.
	);
?>