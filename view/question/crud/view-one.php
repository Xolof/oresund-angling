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
$activeUser = $this->di->session->get("user_id");
?>

<div class="question">
    <p><?= $question->text ?></p>
    <p class="question-answer-comment-info">
        <span class="gravatar-view-one"><?= $question->gravatar ?></span>
        <span  class="acronym">
            <a href="<?= url("user/show/$question->uid") ?>">
                <span><?= htmlentities($question->acronym) ?></span>
            </a>
        </span>
        <span class="time">
            <span><?= htmlentities($question->time) ?></span>
        </span>
        <?php if ($question->updated): ?>
            <span class="updated">(Updated <?= $question->updated ?>)</span>
        <?php endif ?>
    </p>
    <p class="question-edit-delete-para">
        <?php if($activeUser === $question->uid): ?>
            <a href="<?= url("question/update/$question->id") ?>"  class="edit-delete-link">Edit</a>
            <a href="<?= url("question/delete/$question->id") ?>"  class="edit-delete-link">Delete</a>
        <?php endif ?>
    </p>
</div>

<p class="answer-comment-link-para">
    <a href="<?= url("answer/create/$question->id") ?>" class="answer-comment-link">Add an answer</a>
</p>

<div class="qComments">
    <?php foreach($qComments as $qComment): ?>
        <div class="qComment">
            <p><?= $qComment->text ?></p>
            <p class="question-answer-comment-info">
                <span class="gravatar-view-one"><?= $qComment->gravatar ?></span>
                <span class="acronym">
                    <a href="<?= url("user/show/$qComment->uid") ?>">
                        <span><?= htmlentities($qComment->acronym) ?></span>
                    </a>
                </span>
                <span class="time">
                    <span><?= htmlentities($qComment->time) ?></span>
                </span>
                <?php if ($qComment->updated): ?>
                    <span class="updated">(Updated <?= $qComment->updated ?>)</span>
                <?php endif ?>
            </p>
            <?php if($activeUser === $qComment->uid): ?>
                <a href="<?= url("question-comment/update/$qComment->id") ?>"  class="edit-delete-link">Edit</a>
                <a href="<?= url("question-comment/delete/$qComment->id") ?>"  class="edit-delete-link">Delete</a>
            <?php endif ?>
        </div>
    <?php endforeach ?>
    <p><a href="<?= url("question-comment/create/$question->id") ?>" class="answer-comment-link">Add a comment</a></p>
</div>

<?php foreach($answers as $answer): ?>
    <div class="answer">
        <p><?= $answer->text ?></p>
        <p class="question-answer-comment-info">
            <span class="gravatar-view-one"><?= $answer->gravatar ?></span>
            <span class="acronym">
                <a href="<?= url("user/show/$answer->uid") ?>">
                    <span><?= htmlentities($answer->acronym) ?></span>
                </a>
            </span>
            <span class="time">
                <span><?= htmlentities($answer->time) ?></span>
            </span>
            <?php if ($answer->updated): ?>
                <span class="updated">(Updated <?= $answer->updated ?>)</span>
            <?php endif ?>
        </p>
        <?php if($activeUser === $answer->uid): ?>
            <a href="<?= url("answer/update/$answer->id") ?>"  class="edit-delete-link">Edit</a>
            <a href="<?= url("answer/delete/$answer->id") ?>"  class="edit-delete-link">Delete</a>
        <?php endif ?>
    </div>

    <div class="aComments">
        <?php foreach($aComments["answer $answer->id"] as $aComment): ?>
            <div class="aComment">
                <p><?= $aComment->text ?></p>
                <p class="question-answer-comment-info">
                    <span class="gravatar-view-one"><?= $aComment->gravatar ?></span>
                    <span class="acronym">
                        <a href="<?= url("user/show/$aComment->uid") ?>">
                            <span><?= htmlentities($aComment->acronym) ?></span>
                        </a>
                    </span>
                    <span class="time">
                        <span>
                            <?= htmlentities($aComment->time) ?>
                        </span>
                    </span>
                    <?php if ($aComment->updated): ?>
                        <span class="updated">(Updated <?= $aComment->updated ?>)</span>
                    <?php endif ?>
                </p>
                <?php if($activeUser === $aComment->uid): ?>
                    <a href="<?= url("answer-comment/update/$aComment->id") ?>"  class="edit-delete-link">Edit</a>
                    <a href="<?= url("answer-comment/delete/$aComment->id") ?>"  class="edit-delete-link">Delete</a>
                <?php endif ?>
            </div>
        <?php endforeach ?>
        <p><a href="<?= url("answer-comment/create/$answer->id") ?>" class="answer-comment-link">Add a comment</a></p>
    </div>
<?php endforeach ?>
