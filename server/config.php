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

$lb_host = '192.0.2.10'; // Load balancer IP address
$lb_username = 'draindroid';
$lb_password = 'secret_password';
$lb_proto = 'https';
$lb_url = $lb_proto.'://'.$lb_username.':'.$lb_password.'@'.$lb_host.'/';

$acl_enabled = true;
$acl_allowed_devices = array(
    'device_uuid1',
    'device_uuid2' 
); 

// Enable or disable login requirement
// If acccessing KempGW through a VPN and have ACL enabled you might want to disable login requirement since the user has already logged on the VPN.
$login_enabled = false;
// Supported login methods:
//	ldap: Authenticate against LDAP/Active directory.
// 	static: Share a static user/password for every user.
$login_method = 'ldap';
// Static login credentials settings
$login_static_username = 'admin';
$login_static_password = 'qwerty';
// LDAP settings
$login_ldap_host = 'example.com';
$login_ldap_DN = 'OU=Users,DC=example,DC=com';
$login_ldap_group = 'draindroid admins';

?>
