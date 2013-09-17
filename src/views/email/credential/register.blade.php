<?php

use Illuminate\Support\Fluent;
is_array($user) and $user = new Fluent($user); ?>

Hello <?php echo $user->fullname; ?>

<p>Thank you for registering with us, in order to login please use the following:</p>

<p>E-mail Address: <?php echo $user->email; ?></p>
<p>Password: <?php echo $password; ?></p>
