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
$urlToCreate = url("answer-comment/create");
$urlToDelete = url("answer-comment/delete");



?><h1>View all answer comments</h1>

<p>
    <a href="<?= $urlToCreate ?>">Create</a> |
    <a href="<?= $urlToDelete ?>">Delete</a>
</p>

<?php if (!$items) : ?>
    <p>There are no items to show.</p>
<?php
    return;
endif;
?>

<table>
    <tr>
        <th>Id</th>
        <th>aid</th>
        <th>uid</th>
        <th>text</th>
    </tr>
    <?php foreach ($items as $item) : ?>
    <tr>
        <td>
            <a href="<?= url("answer-comment/update/{$item->id}"); ?>"><?= $item->id ?></a>
        </td>
        <td><?= htmlentities($item->aid) ?></td>
        <td><?= htmlentities($item->uid) ?></td>
        <td><?= htmlentities($item->text) ?></td>
    </tr>
    <?php endforeach; ?>
</table>
