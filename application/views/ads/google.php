<html>
    <head>
        <script src="http://ads.nexage.com/js/admax/admax_api.js"></script>
        <script>
            var suid = getSuid();
            /* var loc = "location in format 'lat,long'"; */
            /* if (loc == ",") loc = ""; */
            var admax_vars = {
                "dcn": "2c9d2b50015b5bb0aaaab3d2d9960047" /* Your publisher ID */
                , "pos": "interstitial" /* Position name */
                , "grp": ""
                        /* other desired parameters should be added here, for example... */
                        /* ,"req(url)": document.URL */
                        /* ,"req(loc)": loc */
                        /* ,"p(customParam)": "value for custom parameter" */
                        /* ,"d(id2)": "SHA1 hash of Android ID/UDID" */
                        /* ,"d(id12)": "MD5 hash of Android ID/UDID" */
                        /* ,"d(id24)": "ID sanctioned for Advertising in the clear" */
                        /* ,"ifatrk": "Flag to allow or limit ad tracking. 1 for tracking allowed, 0 for tracking limited" */
                        /* ,"dnt": "Flag for Do Not Track. 1 = ad tracking NOT allowed, 0 = ad tracking allowed" */
            };
            if (suid)
                admax_vars["u(id)"] = suid;
            admaxAd(admax_vars);
        </script>
    </head>
    <body>
        <?php if ($_SERVER['HTTP_HOST'] == 'luvr.me' && $show_ad == true) { ?>
            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <!-- Column ad1 -->
            <ins class="adsbygoogle"
                 style="display:inline-block;width:250px;height:600px"
                 data-ad-client="ca-pub-8931925329892531"
                 data-ad-slot="4333141005"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
            <!-- Column ad2 -->
            <ins class="adsbygoogle"
                 style="display:inline-block;width:250px;height:600px"
                 data-ad-client="ca-pub-8931925329892531"
                 data-ad-slot="5809874207"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
            <!-- Column ad3 -->
            <ins class="adsbygoogle"
                 style="display:inline-block;width:250px;height:600px"
                 data-ad-client="ca-pub-8931925329892531"
                 data-ad-slot="4274265409"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
            <!-- Column ad4 -->
            <ins class="adsbygoogle"
                 style="display:inline-block;width:250px;height:600px"
                 data-ad-client="ca-pub-8931925329892531"
                 data-ad-slot="5750998606"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        <?php } ?>
    </body>
</html>