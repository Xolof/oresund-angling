<?php
namespace Anax\View;

$error = $this->di->session->getOnce("error") ?? null;
$message = $this->di->session->getOnce("message") ?? null;

$activeUser = $this->di->session->get("user_id");
?>

<?php if ($activeUser) : ?>
<p class="logged_in">User with id <?= $activeUser ?> is logged in. | <a href="<?= url('logout')?>">Log out</a></p>
<?php endif ?>


<?php if ($error && count($error) > 0) : ?>
    <?php foreach ($error as $err) : ?>
        <p class="error"><?= $err ?></p>
    <?php endforeach ?>
<?php endif ?>

<?php if ($message && count($message) > 0) : ?>
    <?php foreach ($message as $mess) : ?>
        <p class="message"><?= $mess ?></p>
    <?php endforeach ?>
<?php endif ?>
