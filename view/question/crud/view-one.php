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
?>

<div class="question">
    <!-- The user should be required to set a header for the question  -->
    <h2>Question <?= $question->id ?></h2>
    <p><?= htmlentities($question->text) ?></p>
    <p><a href="#">user <?= htmlentities($question->uid) ?></a> <span class="time"><?= htmlentities($question->time) ?></span></p>
    <p><a href="<?= url("answer/create/$question->id") ?>" class="answer-comment-link">Add an answer</a></p>
</div>

<div class="qComments">
    <?php foreach($qComments as $qComment): ?>
        <div class="qComment">
            <p><?= htmlentities($qComment->text) ?></p>
            <p><a href="#">user <?= htmlentities($qComment->uid) ?></a> <span class="time"><?= htmlentities($qComment->time) ?></span></p>
        </div>
    <?php endforeach ?>
    <p><a href="<?= url("question-comment/create/$question->id") ?>" class="answer-comment-link">Add a comment</a></p>
</div>

<?php foreach($answers as $answer): ?>
    <div class="answer">
        <p><?= htmlentities($answer->text) ?></p>
        <p><a href="#">user <?= htmlentities($answer->uid) ?></a> <span class="time"><?= htmlentities($answer->time) ?></span></p>
    </div>

    <div class="aComments">
        <?php foreach($aComments["answer $answer->id"] as $aComment): ?>
            <div class="aComment">
                <p><?= htmlentities($aComment->text) ?></p>
                <p><a href="#">user <?= htmlentities($aComment->uid) ?></a> <span class="time"><?= htmlentities($aComment->time) ?></span></p>
            </div>
        <?php endforeach ?>
        <p><a href="<?= url("answer-comment/create/$answer->id") ?>" class="answer-comment-link">Add a comment</a></p>
    </div>
<?php endforeach ?>
