<?php

namespace Anax\View;

/**
 * View to display one question.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
// echo showEnvironment(get_defined_vars());

$question = $data["question"];
$qComments = $data["qComments"];
$answers = $data["answers"];
$aComments = $data["aComments"];
$qCommentForm = $data["qCommentForm"]
?>

<div class="question">
    <!-- The user should be required to set a header for the question  -->
    <h2>Question <?= $question->id ?></h2>
    <p><?= $question->text ?></p>
    <p><a href="#">user <?= $question->uid ?></a> <span class="time"><?= $question->time ?></span></p>
    <p><a href="<?= url('answer/create') ?>" class="answer-comment-link">Add an answer</a></p>
</div>

<div class="qComments">
    <?php foreach($qComments as $qComment): ?>
        <div class="qComment">
            <p><?= $qComment->text ?></p>
            <p><a href="#">user <?= $qComment->uid ?></a> <span class="time"><?= $qComment->time ?></span></p>
        </div>
    <?php endforeach ?>
    <p><a href="<?= url('question-comment/create') ?>" class="answer-comment-link">Add a comment</a></p>
</div>

<?php foreach($answers as $answer): ?>
    <div class="answer">
        <p><?= $answer->text ?></p>
        <p><a href="#">user <?= $answer->uid ?></a> <span class="time"><?= $answer->time ?></span></p>
    </div>

    <div class="aComments">
        <?php foreach($aComments["answer $answer->id"] as $aComment): ?>
            <div class="aComment">
                <p><?= $aComment->text ?></p>
                <p><a href="#">user <?= $aComment->uid ?></a> <span class="time"><?= $aComment->time ?></span></p>
            </div>
        <?php endforeach ?>
        <p><a href="<?= url('answer-comment/create') ?>" class="answer-comment-link">Add a comment</a></p>
    </div>
<?php endforeach ?>
