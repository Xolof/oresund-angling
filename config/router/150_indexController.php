<?php
/**
 * Route for index.
 */
 return [
     "routes" => [
         [
             "info" => "Controller for question.",
             "mount" => "",
             "handler" => "\Xolof\Index\IndexController",
         ],
     ]
 ];

// return [
//     // Path where to mount the routes, is added to each route path.
//     "mount" => "",
//
//     // All routes in order
//     "routes" => [
//         [
//             "info" => "Just say hi with a string.",
//             "method" => null,
//             "path" => "",
//             "handler" => function () {
//                 //echo "Ho";
//                 return "Hello.";
//             },
//         ],
//     ]
// ];
