console.log("Loaded....Autoclose");
$(document).ready(function () {
    setTimeout(function () {
		if(document.domain != 'luvr.me')
		{
			window.top.close();
		}
    }, 5000);
});