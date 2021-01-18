<?php

/**
 * View for index.
 */

namespace Anax\View;

?>
<h1>Welcome to Öresund fishing!</h1>

<p>Here you can ask and answer questions about anything related to angling in Öresund.</p>

<div class="index-questions">
    <?php if (!$questions) : ?>
        <p>There are no questions to show.</p>
    <?php else : ?>
        <?php foreach ($questions as $question) : ?>
        <div class="view-all-question">
            <p><?= $question->text ?></p>
            <p>
                Asked by <a href="<?= url("user/show/$question->uid") ?>"><?= htmlentities($question->acronym) ?></a>
                <?= htmlentities($question->time) ?>
            </p>
            <p>
                <a href="<?= url("question/show/{$question->id}"); ?>">Read more</a>
            </p>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="index-tags">
    <h5>Most popular tags</h5>
    <?php if (!$mostPopularTags) : ?>
        <p>There are not yet any tags.</p>
    <?php else : ?>
        <?php foreach ($mostPopularTags as $tag) : ?>
            <a class="index-taglink" href=<?= url("question/tag/" . htmlentities($tag["tag"])) ?>>
                <?= htmlentities($tag["tag"]) ?>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="index-users">
    <h5>Most active users</h5>
    <?php if (!$mostActiveUsers) : ?>
        <p>There are not yet any users.</p>
    <?php else : ?>
        <?php foreach ($mostActiveUsers as $user) : ?>
            <a class="index-userlink" href="<?= url("user/show/" . $user["id"]) ?>">
                <?= htmlentities($user["acronym"]) ?>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
