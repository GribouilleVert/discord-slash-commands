<?php

define('PRODUCTION', false);

define('PUBLIC_KEY', '24927dbb7d20790a8c71193a2de5b6a93431f38186b9ad0390021d4d0a6a308a');
define('BOT_TOKEN', null); #The token of your bot if you plan to use discord api

//For logging error with sentry
define('SENTRY_DSN', null);

//To make sentry catch all error
define('SENTRY_ALL', false);

//Ignore request timestamp
//WARNING: DO NOT ENABLE IN PRODUCTION
define('IGNORE_TIME', false);

//Set this to an url to send a copy of the request to a request catcher
//ex: `https://discord-slash.requestcatcher.com/`
//WARNING: THIS MAY IMPACT PERFORMANCES A LOT
//WARNING: DO NOT ENABLE IN PRODUCTION
define('REQUEST_CATCHER_URL', null);
