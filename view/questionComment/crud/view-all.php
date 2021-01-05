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
$urlToCreate = url("question-comment/create");
$urlToDelete = url("question-comment/delete");



?><h1>View all question comments</h1>

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
        <th>qid</th>
        <th>uid</th>
        <th>time</th>
        <th>text</th>
    </tr>
    <?php foreach ($items as $item) : ?>
    <tr>
        <td>
            <a href="<?= url("question-comment/update/{$item->id}"); ?>"><?= $item->id ?></a>
        </td>
        <td><?= $item->qid ?></td>
        <td><?= $item->uid ?></td>
        <td><?= $item->time ?></td>
        <td><?= $item->text ?></td>
    </tr>
    <?php endforeach; ?>
</table>
