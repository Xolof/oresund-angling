<?php

/**
 * View to update an answer.
 */

namespace Anax\View;

// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

// Gather incoming variables and use default values if not set
$item = isset($item) ? $item : null;

// Create urls for navigation
$urlToView = url("answer");



?><h1>Update an answer</h1>

<?= $form ?>
