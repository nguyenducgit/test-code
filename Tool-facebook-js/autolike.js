// ==UserScript==
// @name          Facebook Autolike
// @namespace     http://silverboy.ir
// @description   auto facebook like<br>this script like first 60 post in your wall in facebook, its liking post with random time , so facebook cant detect you as spam<br><br><a href="http://silverboy.ir" rel="nofollow">http://silverboy.ir</a>
		
// @include     /^https?://www\.facebook\.com/.*$/
// @require       http://code.jquery.com/jquery-1.8.0.min.js
// @grant       none
// @version 0.0.1.20150114051653
// ==/UserScript==
	jQuery.noConflict();
var scrolBody = 4000;
	var autolikeLiked = 0;
	var autolikeTimeout = false;
	var autolikeArray = Array(3000, 2000, 4000, 1500,3000, 2000, 4000, 1500,3000, 2000, 4000, 1500);
	function autolikeFunc(){
        scrolBody = scrolBody +10000;
		if(autolikeLiked == 20 || jQuery('body').scrollTop() == 100000){
            location.reload();
			return false;
        }
		if(jQuery('.UFILikeLink').length > 0 && jQuery('button[name="like"]').length == 0)
			{
				jQuery('.UFILikeLink').each(function(){
					if(jQuery(this).text() == 'Thích' && jQuery(this).attr('title') != 'Like this comment' && jQuery(this).attr('data-testid') == 'fb-ufi-likelink' )
						{
							jQuery(this).removeAttr('href');
							jQuery(this)[0].click();
							var num =  Math.floor((Math.random()*10)+10);
							clearTimeout(autolikeTimeout);
							var autolikeArray = Array(3000, 2000, 4000, 1500,3000, 2000, 4000, 1500,3000, 2000, 4000, 1500);
							setTimeout(autolikeFunc, autolikeArray[num]);
							autolikeLiked++;
                            jQuery('body').scrollTop(scrolBody);
							return false;
						}
					else
						{
							if(autolikeTimeout != false)
								clearTimeout(autolikeTimeout);
							autolikeTimeout = setTimeout(autolikeFunc, 15000);
                            jQuery('body').scrollTop(scrolBody);
						}
				});
			}
		else {
			if(jQuery('button[name="like"]').length == 0)
			{
				if(autolikeTimeout != false)
					clearTimeout(autolikeTimeout);
				autolikeTimeout = setTimeout(autolikeFunc, 15000);
				return false;
			}
            jQuery('body').scrollTop(scrolBody);
			jQuery('button[name="like"]').each(function(){
				jQuery(this).click();
				var num =  Math.floor((Math.random()*10)+1);
				clearTimeout(autolikeTimeout);

				setTimeout(autolikeFunc, autolikeArray[num]);
				autolikeLiked++;
				return false;
			});
		}
	}
	if(location.href == 'http://facebook.com' || location.href == 'https://facebook.com'
		|| location.href == 'http://www.facebook.com' || location.href == 'https://www.facebook.com'
		|| location.href == 'http://www.facebook.com/' || location.href == 'https://www.facebook.com/')
		autolikeFunc();
	else {
		var div = document.createElement('div');
		jQuery(div).html('Do you Want To Like All Post In This Page?').css({
			width : '290px',
			height : '16px',
			position : 'absolute',
			top : '0px',
			left : '600px',
			border : '1px dotted silver',
			padding : '15px 5px 5px',
			background : 'rgba(255, 255, 255, 0.6)',
			textAlign : 'center'
		}).appendTo('#blueBar');
		var yes = document.createElement('a');
		var no = document.createElement('a');
		jQuery(yes).html(' Yes ').attr('href', 'javascript:void(0)').appendTo(div).click(function(){
			autolikeFunc();
			jQuery(div).hide();
		}).addClass('uiButton').css('border-radius', '3px');
		jQuery(no).html(' No ').attr('href', 'javascript:void(0)').appendTo(div).click(function(){
			jQuery(div).hide();
		}).addClass('uiButton').css('border-radius', '3px');
	}
	//autolikeFunc();