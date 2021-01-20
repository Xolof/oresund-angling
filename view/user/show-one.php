<?php

namespace Anax\View;

$acronym = $data["acronym"];
$presentation = $data["presentation"];
$gravatar = $data["gravatar"];
$uid = $data["uid"];
$questions = $data["questions"];

$activeUser = $this->di->session->get("user_id");
?>

<h1><?= htmlentities($acronym) ?></h1>

<p><?= $gravatar ?></p>

<?php if ($presentation) : ?>
    <div class="users-presentation">
        <p><?= $presentation ?></p>
    </div>
<?php else : ?>
    <p><?= htmlentities($acronym) ?> has not yet written a presentation.</p>
<?php endif ?>

<?php if ($activeUser === $uid) : ?>
    <a href="<?= url("user-profile/update/$activeUser") ?>">Update your profile</a>
<?php endif ?>

<?php if ($questions) : ?>
    <h3><?= htmlentities($acronym) ?>'s questions</h3>

    <?php foreach ($questions as $question) : ?>
        <div class="users-question">
            <p><?= htmlentities($question["text"]) ?></p>
            <?php if ($question["answered"]) : ?>
                <p class="answered">Answered <a class="read-more" href="<?= url("question/show/{$question["id"]}") ?>">Read more</a></p>
            <?php else : ?>
                <p class="not-answered">Not yet answered <a class="read-more" href="<?= url("question/show/{$question["id"]}") ?>">Read more</a></p>
            <?php endif ?>
        </div>
    <?php endforeach ?>
<?php else : ?>
    <p><?= htmlentities($acronym) ?> has not yet asked any questions.</p>
<?php endif ?>
