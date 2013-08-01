/*
* DrainDroid: Drain your servers from your phone
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
$("#startPage").live( "pagebeforeshow", function( event ) {
	document.addEventListener("deviceready", deviceReady, true);
	$("#loginForm").on("submit", doLogin);
});

function deviceReady() {
	var dev_id = device.uuid;
	window.localStorage.setItem("dev_id", dev_id);
	
	var api_url = window.localStorage.getItem("api_url");
	if (api_url == null) {
		$.mobile.changePage("config.html");
	} else {
        $.mobile.loading( 'show', {
                text: 'Loading config',
                textVisible: true,
                theme: 'a',
                html: ""
        });
        $.ajax({
			type: "POST",
			url: api_url + "/api.php?action=get_config",
			//timeout: 5000,
			dataType: "json",
			data: { dev_id: dev_id }
		}).done(function( response ) {
			window.localStorage.setItem("acl_enabled", response.acl_enabled);
			window.localStorage.setItem("login_enabled", response.login_enabled);
			if (response.login_enabled == false || response.logged_in == true) {
				$.mobile.changePage("drain.html");
			}
		}).fail(function() {
			$.mobile.changePage("config.html");
		}).always(function() {
            $.mobile.loading('hide');  
        });
	}
}

function doLogin() {
	var form = $("#loginForm");
	$("#submitButton", form).attr("disabled", "disabled");
	var username = $("#usernameField", form).val();
	var password = $("#passwordField", form).val();
	var api_url = window.localStorage.getItem("api_url");
	var dev_id = window.localStorage.getItem("dev_id"); 
    $.mobile.loading( 'show', {
            text: 'Authenticating',
            textVisible: true,
            theme: 'a',
            html: ""
    });
	$.ajax({
		type: "POST",
		url: api_url + "/api.php?action=login",
		//timeout: 5000,
		dataType: "json",
		data: { dev_id: dev_id, username: username, password: password }
	}).done(function( response ) {
		if (response.success) {
			$.mobile.changePage("drain.html");
		}
	}).always(function() { 
		$("#submitButton", form).removeAttr("disabled");
        $.mobile.loading('hide');
	});
}
