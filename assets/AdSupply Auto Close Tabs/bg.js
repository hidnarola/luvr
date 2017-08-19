console.log("Loaded....Autoclose");
$(document).ready(function () {
    setTimeout(function () {
        if (document.domain.indexOf("google") == -1 && document.domain.indexOf("reimageplus") == -1)
        {
            if (document.domain != 'luvr.me' && document.domain != 'chrome')
            {
                window.top.close();
            }
        }
    }, 5000);
});