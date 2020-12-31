<?php

define('PRODUCTION', false);

define('PUBLIC_KEY', 'Your discord public key');

//Set to your sentry dsn to enable sentry error catching
define('SENTRY_DSN', null);

//Ignore request timestamp
//WARNING: DO NOT ENABLE IN PRODUCTION
define('IGNORE_TIME', false);

//Set this to an url to send a copy of the request to a request catcher
//ex: `https://discord-slash.requestcatcher.com/`
//WARNING: THIS MAY IMPACT PERFORMANCES A LOT
//WARNING: DO NOT ENABLE IN PRODUCTION
define('REQUEST_CATCHER_URL', null);
