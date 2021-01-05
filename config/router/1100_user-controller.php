<?php
/**
 * Mount the controller onto a mountpoint.
 */
return [
    "routes" => [
        [
            "info" => "Log-out controller.",
            "mount" => "logout",
            "handler" => "\Xolof\Controller\LogoutController",
        ],
    ]
];
