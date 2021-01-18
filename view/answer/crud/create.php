<?php

/**
 * View to create a new answer.
 */

namespace Anax\View;

// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

// Gather incoming variables and use default values if not set
$items = isset($items) ? $items : null;

// Create urls for navigation
$urlToViewItems = url("answer");



?><h1>Add an answer</h1>

<?= $form ?>
