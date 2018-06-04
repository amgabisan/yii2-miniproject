<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    $this->title = 'Registration';
?>

<div>
    <img src='/images/Logo.png' class="img-responsive center-block">
</div>

<div class="col-md-6 col-md-offset-3 col-sm-12" id="loginContainer">
    <h3 class="text-center">USER REGISTRATION</h3>

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

    <div class="input-container center-block">
        <?php $form = ActiveForm::begin([
                'id' => 'register-form',
                'method'    => 'post',
                'fieldConfig' => [
                        'template' => "<div class=\"control-group\">{input}</div>\n<div>{error}</div>"
                ]]);
        ?>

        <?= $form->field($model, 'last_name')->textInput(array('placeholder'=>'Surname')) ?>
        <?= $form->field($model, 'first_name')->textInput(array('placeholder'=>'Given Name')) ?>
        <?= $form->field($model, 'email_address')->textInput(array('placeholder'=>'Email')) ?>
        <?= $form->field($model, 'password')->passwordInput(array('placeholder'=>'Password')) ?>

        <button type="submit" class="btn btn-primary btn-block">Register</button>

        <?php ActiveForm::end(); ?>
    </div>

</div>
