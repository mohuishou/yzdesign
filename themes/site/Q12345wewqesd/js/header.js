/**
 * PgwMenu - Version 2.0
 *
 * Copyright 2014, Jonathan M. Piat
 * http://pgwjs.com - http://pagawa.com
 * 
 * Released under the GNU GPLv3 license - http://opensource.org/licenses/gpl-3.0
 */
(function(a){a.fn.pgwMenu=function(d){var e={mainClassName:"pgwMenu",dropDownLabel:'<span class="icon"></span>',viewMoreEnabled:true,viewMoreLabel:'View more <span class="icon"></span>',viewMoreMaxWidth:480};if(this.length==0){return this}else{if(this.length>1){this.each(function(){a(this).pgwMenu(d)});return this}}var c=this;c.plugin=this;c.config={};c.resizeEvent=null;c.window=a(window);var f=function(){c.config=a.extend({},e,d);b();c.checkMenu();c.window.resize(function(){c.plugin.css("overflow","hidden");clearTimeout(c.resizeEvent);c.resizeEvent=setTimeout(function(){c.checkMenu()},100)});c.plugin.find(".pm-dropDown").click(function(g){c.enableMobileDropDown();g.stopPropagation()});c.plugin.find(".pm-viewMore").click(function(g){c.enableViewMoreDropDown();g.stopPropagation()});a(document).click(function(){c.disableMobileDropDown();c.disableViewMoreDropDown()})};var b=function(){var h=c.config.mainClassName;var g=c.plugin.attr("class");if(g&&g.indexOf("light")>-1){h+=" light"}c.plugin.removeClass().addClass("pm-links");c.plugin.wrap('<div class="'+h+'"></div>');c.plugin=c.plugin.parent();c.plugin.prepend('<div class="pm-dropDown"><a href="javascript:void(0)">'+c.config.dropDownLabel+"</a></div>");if(c.config.viewMoreEnabled){c.plugin.append('<div class="pm-viewMore" style="display:inline-block"><a href="javascript:void(0)">'+c.config.viewMoreLabel+"</a><ul></ul></div>")}};c.checkMenu=function(){var j=c.plugin.width();if(c.config.viewMoreEnabled){var h=c.plugin.find(".pm-viewMore").width()}function g(){var k=0;c.plugin.find(".pm-links").removeClass("mobile").show();c.plugin.find(".pm-links > li").each(function(){k+=a(this).width()});return k}function i(l){if(l=="viewmore"){var k=h;c.plugin.find(".pm-links").removeClass("mobile").show();c.plugin.find(".pm-viewMore > ul > li").remove();c.plugin.find(".pm-links > li").show().each(function(){if(k+a(this).width()<j){k+=a(this).width()}else{c.plugin.find(".pm-viewMore > ul").append(a(this).clone().show());a(this).hide()}});c.plugin.find(".pm-dropDown, .pm-viewMore > ul").hide();c.plugin.find(".pm-viewMore").show()}else{if(l=="dropdown"){c.plugin.find(".pm-links > li").show();c.plugin.find(".pm-links").addClass("mobile").hide();c.plugin.find(".pm-viewMore, .pm-viewMore > ul").hide();c.plugin.find(".pm-viewMore > ul > li").remove();c.plugin.find(".pm-dropDown").show()}else{c.plugin.find(".pm-links > li").show();c.plugin.find(".pm-links").removeClass("mobile").show();c.plugin.find(".pm-dropDown, .pm-viewMore, .pm-viewMore > ul").hide();c.plugin.find(".pm-viewMore > ul > li").remove()}}c.plugin.find(".pm-dropDown > a, .pm-viewMore > a").removeClass("active")}if(g()>j){if(c.config.viewMoreEnabled&&(j>c.config.viewMoreMaxWidth)){i("viewmore")}else{i("dropdown")}}else{i("normal")}c.plugin.css("overflow","")};c.enableViewMoreDropDown=function(){if(c.plugin.find(".pm-viewMore > a").hasClass("active")){c.disableViewMoreDropDown();return false}c.plugin.find(".pm-viewMore > a").addClass("active");c.plugin.find(".pm-viewMore > ul").show()};c.disableViewMoreDropDown=function(){if(c.plugin.find(".pm-viewMore > a").hasClass("active")){c.plugin.find(".pm-viewMore > a").removeClass("active");c.plugin.find(".pm-viewMore > ul").hide()}};c.enableMobileDropDown=function(){if(c.plugin.find(".pm-dropDown > a").hasClass("active")){c.disableMobileDropDown();return false}c.plugin.find(".pm-dropDown > a").addClass("active");c.plugin.find(".pm-links").show()};c.disableMobileDropDown=function(){if(c.plugin.find(".pm-dropDown > a").hasClass("active")){c.plugin.find(".pm-dropDown > a").removeClass("active");c.plugin.find(".pm-links").hide()}};f();return this}})(window.Zepto||window.jQuery);