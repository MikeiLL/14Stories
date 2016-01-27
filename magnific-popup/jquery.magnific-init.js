// initialize magnific popup galleries with titles and descriptions
(function($){
$('.gallery').magnificPopup({
   callbacks: {
   open: function() {
   $('.mfp-description').append(this.currItem.el.attr('title'));
   },
  
   afterChange: function() {
    $('.mfp-description').empty().append(this.currItem.el.attr('title'));
    }
 },
   delegate: 'a',
   type: 'image',
   image: {
      markup: '<div class="mfp-figure">'+
	'<div class="mfp-close"></div>'+
	'<div class="mfp-img"></div>'+
	'<div class="mfp-bottom-bar">'+
	'<div class="mfp-title"></div>'+
	'<div class="mfp-description"></div>'+
	'<div class="mfp-counter"></div>'+
	'</div>'+
	'</div>',
	titleSrc: function(item) {
	return '<strong>' + item.el.find('img').attr('alt') + '</strong>';
	}
},

	gallery: {
	enabled: true,
	navigateByImgClick: true
	}
  });
})(jQuery);
