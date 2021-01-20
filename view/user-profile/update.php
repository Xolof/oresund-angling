<?php

/**
 * View to update a user profile.
 */

namespace Anax\View;

// Create urls for navigation
$urlToView = url("answer");

?><h1>Update your profile</h1>

<?= $form ?>

<script>
const textForm = document.getElementById("form-element-presentation");
textForm.required = true;
</script>
