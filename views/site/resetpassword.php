<?php
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
    $this->title = 'Reset Password';
?>

<div>
    <img src='/images/Logo.png' class="img-responsive center-block">
</div>

<div class="col-md-6 col-md-offset-3 col-sm-12" id="loginContainer">
    <h3 class="text-center">RESET PASSWORD</h3>

    <!-- Flash Messages -->
    <?php if (Yii::$app->session->hasFlash('success')) { ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Success!</strong> <?= Yii::$app->session->getFlash('success'); ?>
        </div>
    <?php } else if (Yii::$app->session->hasFlash('error')) { ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Error!</strong> <?= Yii::$app->session->getFlash('error'); ?>
        </div>
    <?php } else { ?>

     <?php $form = ActiveForm::begin([
                'id' => 'resetpassword-form',
                'method'    => 'post',
                'fieldConfig' => [
                        'template' => "<div class=\"control-group\">{input}</div>\n<div>{error}</div>"
                ]]);
    ?>

    <?= $form->field($model, 'old_password')->passwordInput(array('placeholder'=>'Old Password')) ?>
    <?= $form->field($model, 'password')->passwordInput(array('placeholder'=>'Password', 'value' => '')) ?>
    <?= $form->field($model, 'confirm_password')->passwordInput(array('placeholder'=>'Confirm Password')) ?>

    <button type="submit" class="btn btn-primary btn-block">Reset Password</button>

     <?php ActiveForm::end(); ?>

    <?php } ?>
</div>
