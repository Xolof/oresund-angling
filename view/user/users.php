<?php

namespace Anax\View;

?>

<h1>Users</h1>

<div class="userlist">
    <?php foreach ($users as $user) : ?>
        <div class="userlist-user">
            <?= $user["gravatar"] ?>
            <p>
                <a href="<?= url("user/show/" . $user["id"]) ?>">
                    <?= htmlentities($user["acronym"]) ?>
                </a>
            </p>
            <p>Number of posts: <?= $user["score"] ?></p>
            <p>Member since <?= $user["registered"] ?></p>
        </div>
    <?php endforeach ?>
</div>
