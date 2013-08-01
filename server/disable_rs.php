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

if (!$api_loaded) {
	exit;
}
if ($login_enabled == true && $api_logged_in == false) {
	exit;
}

$api_response = array('success' => false);
$api_rs = (isset($_GET['rs'])) ? $_GET['rs'] : null;

if ($api_rs != null) {
	$html = file_get_contents($lb_url.'access/disablers?rs='.$api_rs);
	try { 
		$xml = @new SimpleXMLElement($html); 
		$success = $xml->Success->__toString();
		if ($success == 'Command completed ok') {
			$api_response = array('success' => true);
		}
	} catch (Exception $e) { 
		$api_response = false; 
	} 
}

?>
