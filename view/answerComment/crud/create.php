<?php

/**
 * View to create a new answer comment.
 */

namespace Anax\View;

// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

// Gather incoming variables and use default values if not set
$items = isset($items) ? $items : null;

// Create urls for navigation
$urlToViewItems = url("answer-comment");



?><h1>Add a comment</h1>

<?= $form ?>
