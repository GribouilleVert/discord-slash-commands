<?php

use League\Route\Strategy\ApplicationStrategy;

return [

    'env'    => PRODUCTION ? 'production' : 'development',
    'release' => '1.0.0',

    //La stratégie de votre application
    'app.strategy' => \DI\get(ApplicationStrategy::class),

    //Set to your sentry dsn to enable sentry error catching
    'app.sentryDsn' => 'https://171b39bbf02f455a80913d9218e8bbca@o342392.ingest.sentry.io/5682029',


];
