# AdsIpBlock
Google Adwords (ADS) automatic IP Block with ADS PHP API

If you have the adsapi_php.ini file you used for the AdWords API, copy and name it as google_ads_php.ini. Simply change the section name from [ADWORDS] to [GOOGLE_ADS].
Change autoload.php real path and use:
YourNameSpace\BlockedIP::add($campaignId, $ip);

require:
    php >= 7.2,
    googleads/google-ads-php v5.0.0 https://github.com/googleads/google-ads-php
    
   
