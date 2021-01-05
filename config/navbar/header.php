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
            "text" => "QuestionComment",
            "url" => "question-comment",
            "title" => "QuestionComment.",
        ],
        [
            "text" => "Answer",
            "url" => "answer",
            "title" => "Answer.",
        ],
        [
            "text" => "AnswerComment",
            "url" => "answer-comment",
            "title" => "AnswerComment.",
        ],
        [
            "text" => "User",
            "url" => "user",
            "title" => "User.",
        ],
    ],
];

return $navbar;
