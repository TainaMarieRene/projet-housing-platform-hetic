<?= "Je suis le header :) " ?>
<?php if($data['user_logged_in']): ?>
    <?= "Hello " . ($data['user_logged_in'])->getFirstname() ?>
    <a href="<?= __ROOT_URL__ ?>/logout">Logout</a>
<?php else: ?>
    <a href="<?= __ROOT_URL__ ?>/login">Login</a>
    <a href="<?= __ROOT_URL__ ?>/register">Register</a>
<?php endif; ?>