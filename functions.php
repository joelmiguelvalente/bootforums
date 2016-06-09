<?php
require("config.php");


function themeSelector(){
	global $config;
	
	return 'bootstrap.min.'.$config['theme'].'.css';
}
