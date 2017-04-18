var current_class = 'bg-03';
setInterval(function(){
	switch(current_class) {
		case 'bg-01':current_class = 'bg-02'; break;
		case 'bg-02':current_class = 'bg-03'; break;
		case 'bg-03':current_class = 'bg-01'; break;
	}
	$('.with-login #header').removeClass('bg-01').removeClass('bg-02').removeClass('bg-03');
	$('.with-login #header').addClass(current_class);
},4000);
