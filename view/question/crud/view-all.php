<?php

namespace Anax\View;

/**
 * View to display all books.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

// Gather incoming variables and use default values if not set
// $items = isset($items) ? $items : null;

?><h1>Questions</h1>

<?php if (!$items) : ?>
    <p>There are no items to show.</p>
<?php
    return;
endif;
?>

<?php foreach ($items as $item) : ?>
<div class="view-all-item">
    <p><?= $item->text ?></p>
    <p>
        Asked by <a href="<?= url("user/show/$item->uid") ?>"><?= htmlentities($item->acronym) ?></a>
        <?= htmlentities($item->time) ?>
    </p>
    <p>
        <a href="<?= url("question/show/{$item->id}"); ?>">Read more</a>
    </p>
</div>
<?php endforeach; ?>
