<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script><html>    <div id="radar">        <div id="rad"></div>        <div class="obj" data-x="150" data-y="160"></div>        <div class="obj" data-x="120" data-y="65"></div>        <div class="obj" data-x="140" data-y="185"></div>        <div class="obj" data-x="220" data-y="35"></div>        <div class="obj" data-x="120" data-y="235"></div>    </div></html><script>    $(function () {        var $rad = $('#rad'),                $obj = $('.obj'),                deg = 0,                rad = $rad.width() / 2;        $obj.each(function () {            var pos = $(this).data(),                    getAtan = Math.atan2(pos.x - rad, pos.y - rad),                    getDeg = (-getAtan / (Math.PI / 180) + 180) | 0;            // Read/set positions and store degree            $(this).css({left: pos.x, top: pos.y}).attr('data-atDeg', getDeg);        });        (function rotate() {            $rad.css({transform: 'rotate(' + deg + 'deg)'}); // Radar rotation            $('[data-atDeg=' + deg + ']').stop().fadeTo(0, 1).fadeTo(1700, 0.2); // Animate dot at deg            deg = ++deg % 360;      // Increment and reset to 0 at 360            setTimeout(rotate, 25); // LOOP        })();    });</script><style>    #radar{        position:relative;        overflow:hidden;        width:321px; height:321px;        background:#222 url(http://i.stack.imgur.com/vY6Tl.png);        border-radius: 50%;    }    #rad{        position:absolute;        width:321px;        height:321px; background:url(http://i.stack.imgur.com/fbgUD.png);    }    .obj{        background:#cf5;        position:absolute;        border-radius: 50%;        width:4px; height:4px; margin:-2px;        box-shadow:0 0 10px 5px rgba(100,255,0,0.5);        opacity:0.2;    }</style>