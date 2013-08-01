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

require_once('config.php');

$api_username = GetHttpVar('username');
$api_password = GetHttpVar('password');

if ($login_enabled) {
	$api_response = array('success' => false);
	$_SESSION['logged_in'] = false;
	if ($login_method == 'static') {
		if ($login_static_username != $api_username && $login_static_password != $api_password) {
			exit;
		}
		$_SESSION['logged_in'] = true;
		$api_response = array('success' => true);
	}
	if ($login_method == 'ldap') {
		$ldap = ldap_connect($login_ldap_host) or die('Could not connect to LDAP server');
		if (!$ldap) {
			exit;
		}
		
		$ldap_bind = ldap_bind($ldap, $api_username.'@'.$login_ldap_host, $api_password);
		if (!$ldap_bind) {
			exit;
		}
		
		$filter = '(sAMAccountName='.$api_username.')';
		$attr = array('memberof');
		$result = ldap_search($ldap, $login_ldap_DN, $filter, $attr) or exit;
		$entries = ldap_get_entries($ldap, $result);
		ldap_unbind($ldap);
		
		foreach ($entries[0]['memberof'] as $groups) {
			if (strpos($groups, $login_ldap_group)) {
				$_SESSION['logged_in'] = true;
				$api_response = array('success' => true);
			}
		}
	}
}
