<?php

namespace Anax\View;

$activeUser = $this->di->session->get("user_id");
?>

<h1>User</h1>

<?php if ($activeUser) : ?>
    <a href="logout">Logout</a>
        | <a href="user/show/<?= $activeUser ?>">Show your profile</a>
        | <a href="<?= url("user-profile/update/$activeUser") ?>">Update your profile</a>
<?php else : ?>
    <a href="user/login">Login</a>
    | <a href="user/create">Sign up</a>
<?php endif ?>

<p>Create an account or login to be able to add questions, answer questions and make comments.</p>
