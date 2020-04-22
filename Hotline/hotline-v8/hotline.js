let html = "<ul> <li class='map'><a href='#!' target='_blank' title='Vị trí'><img src='https://thamgiare.vn/hotline/map.png' alt='Google maps'></a></li> <li class='hotline'><a href='#!'> <div class='coccoc-alo-phone coccoc-alo-green coccoc-alo-show' id='coccoc-alo-phoneIcon callnowicon'> <div class='coccoc-alo-ph-circle'></div> <div class='coccoc-alo-ph-circle-fill'></div> <div class='coccoc-alo-ph-img-circle'></div> </div> </a></li> <li class='message'><a href='#!' target='_blank' title='Gửi tin nhắn cho chúng tôi qua Facebook'><img src='https://thamgiare.vn/hotline/message.svg' alt='Facebook message'> <div class='bubble'>1</div> <div class='bubble-msg'></div> </a></li> <li class='zalo'><a href='#!' target='_blank'> <div class='zs-container'><i class='zs-ico zs-has-notify' id='button-main'> <div class='zs-ico-main'></div><em></em> </i></div> </a></li></ul>";
$(document).ready(function() {
    $('#js-hotline').html(html);

    $('#js-hotline .map a').attr('href', map);
    $('#js-hotline .hotline a').attr('href', hotline);
    $('#js-hotline .message a').attr('href', facebook);
    $('#js-hotline .zalo a').attr('href', zalo);
    $('#js-hotline').css(possition, '5px');
});