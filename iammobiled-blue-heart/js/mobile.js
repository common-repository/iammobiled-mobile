// This file is part of the IamMobiled Mobile Engine/Themes for WordPress
// http://iammobiled.com
//
// Copyright (c) 2009 IamMobiled.com All rights reserved.
// http://iammobiled.com
//
// Released under the GPL license
// http://www.opensource.org/licenses/gpl-license.php
//
// **********************************************************************
// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
// **********************************************************************

jQuery(function($) {
	$('div.tabbed').prepend('<ul class="tabs hide"><li class="active"><a href="#recent">' + POSTS_TAB + '</a></li><li><a href="#pages">' + PAGES_TAB + '</a></li></ul>');
	var tabs = $('ul.tabs');
	if (tabs.size()) {
		tabs.removeClass('hide');
		$('#recent, #pages').hide();
		$('ul.tabs a[href=#recent]').click(function() {
			$('ul.tabs li').removeClass('active');
			$(this).parent().addClass('active');
			$('#pages_tab').hide();
			$('#recent_tab').show();
			return false;
		});
		$('ul.tabs a[href=#pages]').click(function() {
			$('ul.tabs li').removeClass('active');
			$(this).parent().addClass('active');
			$('#recent_tab').hide();
			$('#pages_tab').show();
			return false;
		});
		if (IS_PAGE) {
			$('ul.tabs a[href=#pages]').click();
		}
		else {
			$('ul.tabs a[href=#recent]').click();
		}
		$('.tabbed ul.group').css({
			'border-top': '0',
			'margin-top': '0'
		});
	}
});
