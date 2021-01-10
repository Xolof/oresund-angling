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



?><h1>View all questions</h1>

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
        <th>User</th>
        <th>time</th>
        <th>text</th>
    </tr>
    <?php foreach ($items as $item) : ?>
    <tr>
        <td>
            <a href="<?= url("question/show/{$item->id}"); ?>"><?= $item->id ?></a>
        </td>
        <td><?= htmlentities($item->acronym) ?></td>
        <td><?= htmlentities($item->time) ?></td>
        <td><?= htmlentities($item->text) ?></td>
    </tr>
    <?php endforeach; ?>
</table>
