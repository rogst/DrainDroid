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
$("#configPage").live( "pagebeforeshow", function( event ) {
	var form = $("#configForm");
	var timer;
	var delay = 1000; // 0.6 seconds delay after last input
 
	var api_url = window.localStorage.getItem("api_url");
	$('#apiUrlField', form).val(api_url);
	
	$('#apiUrlField', form).bind('input', function() {
		$("#configBackButton", form).attr("disabled", "disabled");
		window.clearTimeout(timer);
		timer = window.setTimeout(function(){
			var value = $('#apiUrlField', form).val();
			window.localStorage.setItem("api_url", value);
			$("#configBackButton", form).removeAttr("disabled");
		}, delay);
	});
});

$("#configPage").live( "pageshow", function( event ) {
	var form = $("#configForm");
	var dev_id = window.localStorage.getItem("dev_id");
	$("#dev_id", form).text(dev_id);
});
