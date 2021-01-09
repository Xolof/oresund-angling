<?php
namespace Anax\View;

$activeUser = $this->di->session->get("user_id");
?>

<h1>User</h1>

<?php if ($activeUser) : ?>
    <a href="logout">Logout</a>
<?php else: ?>
    <a href="user/login">Login</a>
<?php endif ?>

| <a href="user/create">Create</a>

<p>Create an account or login to be able to add questions, answer questions and make comments.</p>
