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

$api_response = array();

$html_stats = file_get_contents($lb_url.'access/stats');

$added_rs = array();
$real_servers = array();
try { 
	$xml_stats = @new SimpleXMLElement($html_stats); 
	$rs_list = $xml_stats->Success[0]->Data[0]->Rs;
	foreach ($rs_list as $rs) {
		if (!in_array($rs->Addr->__toString(), $added_rs)) {
			$ip_str = "";
			$ip_split = explode('.', $rs->Addr->__toString());
			foreach($ip_split as $ip_octet) {
				$ip_str .= str_pad($ip_octet, 3, '0', STR_PAD_LEFT);
			}   	
			$real_servers[$ip_str] = array(
				'ip' => $rs->Addr->__toString(),
				'enabled' => $rs->Enable->__toString(),
				'activeconns' => $rs->ActivConns->__toString(),
				'persist' => $rs->Persist->__toString()
			);
		}
		$added_rs[] = $rs->Addr->__toString();
	}
	ksort($real_servers);
	$api_response = $real_servers;
} catch (Exception $e) { 
	$api_response = false; 
} 

?>
