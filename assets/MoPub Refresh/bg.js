$(document).load(function () {
    console.log("Loaded....");
    manageCPMTimer();
});
function manageCPMTimer() {
    var _counter = 180;
    /*var _val = (Math.random() * (2.99 - 0.99) + 0.99).toFixed(2);*/
    var prices = [0.99, 1.09, 1.19, 1.39, 1.49, 1.55, 1.59, 1.69, 1.74, 1.79, 1.84, 1.89, 1.94, 1.99, 2.09, 2.19, 2.24, 2.29, 2.39, 2.49, 2.59, 2.69, 2.74, 2.79, 2.84, 2.89, 2.94, 2.99];
    var _val = prices[Math.floor(Math.random() * prices.length)];
    console.log(_val);
    var _timer = setInterval(function () {
        if (_counter === 0)
        {
            /*var e = jQuery.Event("blur");
            e.which = 13;
            e.keyCode = 13;*/
            $('#adunit-d830bdb019db41df8b0f005e90d95b03 .price_floor input[type="text"]').val(_val);
            $.ajax({
                url: 'https://app.mopub.com/api/adunit/d830bdb019db41df8b0f005e90d95b03?daily=0&endpoint=all',
                headers: {
                    "x-csrftoken": $('input[name="csrfmiddlewaretoken"]').val(),
                    "x-requested-with": "XMLHttpRequest",
                    "accept": "application/json, text/javascript, */*; q=0.01"
                },
                contentType: "application/json",
                data: JSON.stringify({
                    app: "86a600664a6f43d1b7e23fde0c19ffa6",
                    id: "d830bdb019db41df8b0f005e90d95b03",
                    price_floor: _val,
                    active: ($("#adunit-d830bdb019db41df8b0f005e90d95b03 .targeting .targeting-box").is(":checked")) ? true : false,
                    adgroup: null,
                    campaign: null,
                    clk: 0,
                    conv: 0,
                    ctr: 0,
                    date_range: null,
                    endpoint: 'all',
                    fill_rate: 0,
                    imp: 0,
                    include_daily: false,
                    req: 0,
                    soft_price_floor: 0,
                    spf_auto_manage: false,
                    start_date: null,
                }),
                type: 'PUT',
                success: function (response) {
                    //...
                }
            });
            /*$(document).trigger('keyup');*/
            /*if ($("#adunit-d830bdb019db41df8b0f005e90d95b03 .targeting .targeting-box").length > 0)
             $("#adunit-d830bdb019db41df8b0f005e90d95b03 .targeting .targeting-box").click();*/
            clearInterval(_timer);
            manageCPMTimer();
        }
        console.log(_counter + " seconds");
        _counter--;
    }, 1000);
}