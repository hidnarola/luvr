var current_class = 'bg-03';
$('.bxslider').bxSlider({
  mode: 'fade',
  captions: true,
  auto:true
});
$('.image-link, .test-popup-link').magnificPopup({
	type:'image',
	 gallery:{
		enabled:true,
		tPrev: 'Previous (Left arrow key)', // Alt text on left arrow
		tNext: 'Next (Right arrow key)', // Alt text on right arrow
		tCounter: '%curr% of %total%' // Markup for "1 of 7" counter
	},
	mainClass: 'mfp-with-zoom', // this class is for CSS animation below
	
	zoom: {
		enabled: true, // By default it's false, so don't forget to enable it

		duration: 300, // duration of the effect, in milliseconds
		easing: 'ease-in-out', // CSS transition easing function

		// The "opener" function should return the element from which popup will be zoomed in
		// and to which popup will be scaled down
		// By defailt it looks for an image tag:
		opener: function(openerElement) {
		  // openerElement is the element on which popup was initialized, in this case its <a> tag
		  // you don't need to add "opener" option if this code matches your needs, it's defailt one.
		  return openerElement.is('img') ? openerElement : openerElement.find('img');
		}
	}
});


setInterval(function(){
	switch(current_class) {
		case 'bg-01':current_class = 'bg-02'; break;
		case 'bg-02':current_class = 'bg-03'; break;
		case 'bg-03':current_class = 'bg-01'; break;
	}
	$('.with-login #header').removeClass('bg-01').removeClass('bg-02').removeClass('bg-03'); 
	$('.with-login #header').addClass(current_class);
},4000);
