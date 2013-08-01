<?php
/* DrainDroid: Drain your servers from your phone
 * Copyright (C) 2013 Roger Steneteg <roger@steneteg.org>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see [http://www.gnu.org/licenses/].
 */

require_once('config.php');

function GetHttpVar($name, $default = null) {
	if (isset($_GET[$name])) {
		return $_GET[$name];
	} else if ($_POST[$name]) {
		return $_POST[$name];
	} else {
		return $default;
	}
}

$api_loaded = true;
$api_logged_in = false;
$api_dev_id = GetHttpVar('dev_id');
//$api_session = GetHttpVar('session');
$api_action = GetHttpVar('action');
$api_response = null;

header('Content-Type: application/json');

// Verify access
if ($acl_enabled && !in_array($api_dev_id, $acl_allowed_devices)) {
	exit;
}
if ($login_enabled) {
	session_cache_expire(5);
	session_start();
	if ($_SESSION['logged_in']) {
		$api_logged_in = true;
	}
}

// Include the API action code
require_once($api_action.'.php');

// Return the response
$api_response = json_encode($api_response);
echo($api_response);
?>
