console.log("Loaded....Very High CPM");
$(document).ready(function () {
    manageCPMTimer();
});
function manageCPMTimer() {
    var _counter = 60;
    var prices = [2.04, 2.09, 2.19, 2.24, 2.29, 2.34, 2.39, 2.44, 2.49, 2.54, 2.59, 2.64, 2.69, 2.74, 2.79, 2.89, 2.94, 2.99, 3.01, 3.05, 3.09];
    var _val = prices[Math.floor(Math.random() * prices.length)];
    console.log(_val);
    var _timer = setInterval(function () {
        if (_counter === 0)
        {
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
            clearInterval(_timer);
            manageCPMTimer();
        }
        console.log(_counter + " seconds");
        _counter--;
    }, 1000);
}