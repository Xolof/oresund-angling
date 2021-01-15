<?php
namespace Anax\View;

$acronym = $data["acronym"];
$presentation = $data["presentation"];
$gravatar = $data["gravatar"];
$uid = $data["uid"];

$activeUser = $this->di->session->get("user_id");
?>

<h1><?= htmlentities($acronym) ?></h1>

<p><?= $gravatar ?></p>

<?php if($presentation): ?>
    <p><?= $presentation ?></p>
<?php else: ?>
    <p>This user has not yet written a presentation.</p>
<?php endif ?>

<?php if ($activeUser === $uid): ?>
    <a href="<?= url("user-profile/update/$activeUser") ?>">Update your profile</a>
<?php endif ?>
