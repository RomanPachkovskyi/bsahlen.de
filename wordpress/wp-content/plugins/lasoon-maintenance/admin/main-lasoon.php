<?php
/**
 * Lasoon Main Page
 *
 * @package Lasoon\Admin
 */

	$output = new Lasoon_Admin_Settings();

	if($_POST){
		$output->save();
	}

	$output->output();


