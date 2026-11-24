<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Service card description length
    |--------------------------------------------------------------------------
    |
    | Service cards (home page, /services, location pages and the "All
    | Services" block) show a trimmed version of the service description.
    | Change this single number to make those cards shorter or longer.
    |
    | 26 words is roughly two lines on a desktop card.
    |
    */

    'service_card_words' => (int) env('SERVICE_CARD_WORDS', 26),

    /*
    |--------------------------------------------------------------------------
    | Enquiry notification email
    |--------------------------------------------------------------------------
    |
    | Every enquiry is emailed to this address. When it is empty the Business
    | Email from Global Settings is used instead.
    |
    */

    'enquiry_notification_email' => env('ENQUIRY_NOTIFICATION_EMAIL'),

];
