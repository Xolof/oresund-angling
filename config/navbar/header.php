<?php
/**
 * Supply the basis for the navbar as an array.
 */

$navbar = [
    // Use for styling the menu
    "wrapper" => null,
    "class" => "my-navbar rm-default rm-desktop",

    // Here comes the menu items
    "items" => [
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

return $navbar;
