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
$("#drainPage").live( "pageshow", function( event ) {
	$("#drainRetryButton").on("click", fetchRealServers);
    $("#confirmYesButton").on("click", drainHost); 
    fetchRealServers();
});

function confirmDrain(host, enabled) {
    var action = "enable";
    if (enabled == "1") {
        action = "disable";
    }
    window.localStorage.setItem("drainHost", host);
    window.localStorage.setItem("drainAction", action);
    var message = 'Do you want to ' + action + ' ' + host + '?';
    var page = $("#confirm");
    $("h3", page).text(message);
    $("#confirm").popup("open");
}

function drainHost() {
    var host = window.localStorage.getItem("drainHost");
    var action = window.localStorage.getItem("drainAction");
	var api_url = window.localStorage.getItem("api_url");
	var dev_id = window.localStorage.getItem("dev_id");
    $.mobile.loading('show');
    $.ajax({
		type: "POST",
		url: api_url + "/api.php?action=" + action + "_rs&rs=" + host,
		timeout: 5000,
		dataType: "json",
		data: { dev_id: dev_id }
	}).done(function(response) {
        $("#confirm").popup("close");
        window.setTimeout(function() {
            $.mobile.loading('hide');
            fetchRealServers();
        }, 5000);
    }).always(function() {
        window.localStorage.setItem("drainHost", null);
        window.localStorage.setItem("drainAction", null);
    });
}

function fetchRealServers() {
    $("#failedToGetRS").popup("close");
	var page = $("#drainPage");
	var api_url = window.localStorage.getItem("api_url");
	var dev_id = window.localStorage.getItem("dev_id");
    if (api_url == null) {
        $.mobile.changePage("config.xml");
    }
    $.mobile.loading( 'show', {
            text: 'Fetching data',
            textVisible: true,
            theme: 'a',
            html: ""
    });
    $.ajax({
		type: "POST",
		url: api_url + "/api.php?action=get_rs",
		timeout: 5000,
		dataType: "json",
		data: { dev_id: dev_id }
	}).done(function( response ) {
		$('#realServerList', page).empty();
        $.each( response, function( key, rs ) {
			var theme = 'a';
			if (rs.enabled == '1') {
				theme = 'b';
			}
			var listItem = '<li class="rs-enabled-' + rs.enabled + '" data-icon="false" data-theme="' + theme + '"><a href="#" onclick="confirmDrain(&quot;' + rs.ip + '&quot;,&quot;' + rs.enabled + '&quot;);">';
			listItem +=	rs.ip + ' (AC:' + rs.activeconns + '/P:' + rs.persist + ')'; 
			listItem += '</a></li>';
			$('#realServerList', page).append(listItem);
        });

		$('#realServerList', page).listview('refresh');
	}).fail(function() {
        $( "#failedToGetRS" ).popup( "open" );  
    }).always(function() {
        $.mobile.loading( 'hide');
    });
}
