<?php
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
    $this->title = 'Forgot Password';
?>

<div>
    <img src='/images/Logo.png' class="img-responsive center-block">
</div>

<div class="col-md-6 col-md-offset-3 col-sm-12" id="loginContainer">
    <h3 class="text-center">FORGOT PASSWORD?</h3>
    <p class="text-center">We will send you an e-mail containing the link to reset your password.</p>

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
    <?php } ?>

    <?php $form = ActiveForm::begin([
                'id' => 'forgotpassword-form',
                'method'    => 'post',
                'fieldConfig' => [
                        'template' => "<div class=\"control-group\">{input}</div>\n<div>{error}</div>"
                ]]);
    ?>
    <div class="col-md-8 col-sm-12">
        <?= $form->field($model, 'email_address')->textInput(array('placeholder'=>'Email')) ?>
    </div>
    <div class="col-md-4 col-sm-12">
        <button type="submit" class="btn btn-primary btn-block">Send Mail</button>
    </div>

     <?php ActiveForm::end(); ?>
</div>
