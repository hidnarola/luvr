var current_class = 'bg-03';
// $('.bxslider').bxSlider({
//   mode: 'fade',
//   captions: true,
//   auto:true
// });
$('.image-link').magnificPopup({
	type:'image',
	 gallery:{
		enabled:true,
		tPrev: 'Previous (Left arrow key)', // Alt text on left arrow
		tNext: 'Next (Right arrow key)', // Alt text on right arrow
		tCounter: '%curr% of %total%' // Markup for "1 of 7" counter
	},
	mainClass: 'mfp-with-zoom', // this class is for CSS animation below
	zoom: {
		enabled: true				
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
