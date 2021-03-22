<?php

use League\Route\Strategy\ApplicationStrategy;

return [

    'env'    => PRODUCTION ? 'production' : 'development',
    'release' => '1.0.0',

    //Set to your sentry dsn to enable sentry error catching
    'app.sentryDsn' => SENTRY_DSN,


];
