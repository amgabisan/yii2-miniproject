<?php
    use yii\helpers\Html;
    $this->title = 'Dashboard';

    $user = Yii::$app->user->identity;
    $ins_time = date_create($user->ins_time);
?>

<!--
<div>
    <img src='/images/Logo.png' class="img-responsive center-block">
</div>

<div class="col-md-6 col-md-offset-3 col-sm-12" id="loginContainer">
    <h3 class="text-center">WELCOME</h3>
    <p class="text-center">Here is your account details:</p>
    <h3 class="text-center"><?= $user->first_name.' '.$user->last_name ?></h3>
    <h3 class="text-center"><?= $user->email_address ?></h3>
    <h3 class="text-center"><?= date_format($ins_time, 'F d, Y h:i') ?></h3>

    <hr>

    <a href="/site/logout" class="btn btn-primary center-block">Logout</a>
</div>
-->
