<?php

/**
 * Supply the basis for the navbar as an array.
 */

$navbar = [
    // Use for styling the menu
    "class" => "my-navbar",

    // Here comes the menu items/structure
    "items" => [
        [
            "text" => "Home",
            "url" => "",
            "title" => "Home",
        ],
        [
            "text" => "Questions",
            "url" => "question",
            "title" => "Questions",
        ],
        [
            "text" => "Tags",
            "url" => "tags",
            "title" => "Tags",
        ],
        [
            "text" => "Ask",
            "url" => "question/create",
            "title" => "Ask",
        ],
        [
            "text" => "Users",
            "url" => "users",
            "title" => "Users",
        ],
        [
            "text" => "Register",
            "url" => "user/register",
            "title" => "Register",
        ],
        [
            "text" => "About",
            "url" => "about",
            "title" => "About page",
        ],
    ],
];

$activeUser = $_SESSION["user_id"] ?? null;

$myProfile = [
    "text" => "My profile",
    "url" => "user/show/$activeUser",
    "title" => "User",
];

if ($activeUser) {
    array_splice($navbar["items"], 5, 0, [$myProfile]);
}

return $navbar;
