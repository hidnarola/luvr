console.log("Loaded....Low CPM");
$(document).ready(function () {
    manageCPMTimer();
});
function manageCPMTimer() {
    var _counter = 60;
    var prices = [0.05, 0.10, 0.15, 0.20, 0.25, 0.49, 0.59, 0.69, 0.79, 0.89, 0.99, 1.09, 1.19];
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