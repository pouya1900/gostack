<?php

return [
    'mode'    => env('SHETAB_MODE', 'sandbox'), // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
    'sandbox' => [
    ],
    'live'    => [
    ],
    'dollar'  => env('DOLLAR'),

];
