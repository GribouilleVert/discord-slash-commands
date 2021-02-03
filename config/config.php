<?php

use SlashCommands\Strategies\EndpointStrategy;

return [

    //La stratégie de votre application
    'app.strategy' => \DI\get(EndpointStrategy::class),

];
