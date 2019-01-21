// ==UserScript==
// @name          Facebook Add firend
// @namespace     http://silverboy.ir
// @description   Auto add friend on list propose

// @include     /^https?://www\.facebook\.com/.*$/
// @require       http://code.jquery.com/jquery-1.8.0.min.js
// @grant       none
// @version 1
// ==/UserScript==
jQuery.noConflict();
var autoAddAdded = 0;
var autoAddTimeout = false;
var autoAddArray = Array(3000, 2000, 4000, 1500, 3000, 2000, 4000, 1500, 3000, 2000, 4000, 1500);

function autoAddFriendFunc() {
    if (autoAddAdded == 100){
         autoAddTimeout = setTimeout(autoAddFriendFunc, 120000);
    }
    if (typeof jQuery('a.pam.uiBoxLightblue.uiMorePagerPrimary') != 'undefined') {
                    jQuery('a.pam.uiBoxLightblue.uiMorePagerPrimary')[0].click();
                }
    if (jQuery('button._42ft._4jy0.FriendRequestAdd.addButton._4jy3._517h._51sy').length > 0) {
        jQuery('button._42ft._4jy0.FriendRequestAdd.addButton._4jy3._517h._51sy').each(function() {
            if (jQuery(this).text() == 'Thêm bạn') {
                if (typeof jQuery('.autofocus.layerCancel') != 'undefined' && jQuery('.autofocus.layerCancel').attr('action') == 'cancel') {
                    jQuery('.autofocus.layerCancel')[0].click();
                }
                jQuery(this)[0].click();
                var num = Math.floor((Math.random() * 10) + 1);
                clearTimeout(autoAddTimeout);
                var autoAddArray = Array(3000, 2000, 4000, 1500, 3000, 2000, 4000, 1500, 3000, 2000, 4000, 1500);
                setTimeout(autoAddFriendFunc, autoAddArray[num]);
                autoAddAdded++;
                jQuery(this).remove();
                console.log(autoAddAdded);
                return false;
            } else {
                if (autoAddTimeout != false)
                    clearTimeout(autoAddTimeout);
                autoAddTimeout = setTimeout(autoAddFriendFunc, 15000);
            }
        });
    } else {
        return false;
    }
}
if ((location.hostname == 'facebook.com' || location.hostname == 'www.facebook.com') && typeof location.pathname !=  "/" )
    autoAddFriendFunc();
else {
    alert('co chay khong');
    var div = document.createElement('div');
    jQuery(div).html('Do you Want To Like All Post In This Page?').css({
        width: '290px',
        height: '16px',
        position: 'absolute',
        top: '0px',
        left: '600px',
        border: '1px dotted silver',
        padding: '15px 5px 5px',
        background: 'rgba(255, 255, 255, 0.6)',
        textAlign: 'center'
    }).appendTo('#blueBar');
    var yes = document.createElement('a');
    var no = document.createElement('a');
    jQuery(yes).html(' Yes ').attr('href', 'javascript:void(0)').appendTo(div).click(function() {
        autoAddFriendFunc();
        jQuery(div).hide();
    }).addClass('uiButton').css('border-radius', '3px');
    jQuery(no).html(' No ').attr('href', 'javascript:void(0)').appendTo(div).click(function() {
        jQuery(div).hide();
    }).addClass('uiButton').css('border-radius', '3px');
}
//autoAddFriendFunc();
