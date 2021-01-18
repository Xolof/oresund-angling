<?php

/**
 * Supply the basis for the navbar as an array.
 */

return [
    // Use for styling the menu
    "id" => "rm-menu",
    "wrapper" => null,
    "class" => "rm-default rm-mobile",

    // Here comes the menu items
    "items" => [
        [
            "text" => "Home",
            "url" => "",
            "title" => "Home.",
        ],
        [
            "text" => "Questions",
            "url" => "question",
            "title" => "Questions.",
        ],
        [
            "text" => "Ask",
            "url" => "question/create",
            "title" => "Ask.",
        ],
        [
            "text" => "User",
            "url" => "user",
            "title" => "User.",
        ],
    ],
];
