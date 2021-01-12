<?php

namespace Anax\View;

/**
 * View to display all books.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

// Gather incoming variables and use default values if not set
$items = isset($items) ? $items : null;

// Create urls for navigation
$urlToCreate = url("question/create");
$urlToDelete = url("question/delete");



?><h1>Questions</h1>

<h2>
    <a href="<?= $urlToCreate ?>">Ask a question</a>
</h2>

<?php if (!$items) : ?>
    <p>There are no items to show.</p>
<?php
    return;
endif;
?>

<?php foreach ($items as $item) : ?>
<div class="view-all-item">
    <h3>
        <a href="<?= url("question/show/{$item->id}"); ?>">Question heading</a>
    </h3>
    <p><?= htmlentities($item->text) ?></p>
    <p>Asked by <?= htmlentities($item->acronym) ?></p>
    <p><?= htmlentities($item->time) ?></p>
</div>
<?php endforeach; ?>
