<?php

namespace Anax\View;

$activeUser = $this->di->session->get("user_id");
?>

<h1>Register or login</h1>

<?php if (!$activeUser) : ?>
    <a href="login">Login</a> |
<?php endif ?>

<a href="create">Sign up</a>

<p>Create an account or login to be able to add questions, answer questions and make comments.</p>
