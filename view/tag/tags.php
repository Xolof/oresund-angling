<?php

namespace Anax\View;

?>

<h1>Tags</h1>

<div class="taglist">
    <?php foreach ($tags as $tag) : ?>
        <a class="index-taglink" href=<?= url("question/tag/" . htmlentities($tag["tag"])) ?>>
            <?= htmlentities($tag["tag"]) ?>
        </a>
    <?php endforeach ?>
</div>
