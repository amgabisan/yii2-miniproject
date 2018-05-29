<?php
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
    $this->title = 'Login';
?>

<div>
    <img src='/images/Logo.png' class="img-responsive center-block">
</div>

<div class="col-md-6 col-md-offset-3 col-sm-12" id="loginContainer">
    <h3 class="text-center">USER LOGIN</h3>
    <p class="text-center">Login with your social media account</p>

    <div class="social-icons center-block">
        <ul class="list-inline center-block">
            <li>
                <button type="button" class="btn btn-fb">
                    <i class="fab fa-facebook-f"></i> Facebook
                </button>
            </li>
            <li>
                <button type="button" class="btn btn-google">
                    <i class="fab fa-google"></i> Google
                </button>
            </li>
            <li>
                <button type="button" class="btn btn-github">
                    <i class="fab fa-github"></i> GitHub
                </button>
            </li>
            <li>
                <button type="button" class="btn btn-linkedin">
                    <i class="fab fa-linkedin-in"></i> LinkedIn
                </button>
            </li>
            <li>
                <button type="button" class="btn btn-twitter">
                    <i class="fab fa-twitter"></i> Twitter
                </button>
            </li>
        </ul>
    </div>

    <hr>
    <hr>

    <p class="text-center">Login with your account</p>
    <div class="input-container center-block">
        <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'method'    => 'post',
                    'fieldConfig' => [
                            'template' => "<div class=\"control-group\">{input}</div>\n<div>{error}</div>"
                    ]]);
        ?>

        <?= $form->field($model, 'email_address')->textInput(array('placeholder'=>'Email')) ?>

        <?= $form->field($model, 'password')->passwordInput(array('placeholder'=>'Password')) ?>

        <div class="col-sm-12 col-md-6 register-btnCont">
            <button type="button" class="btn btn-danger btn-block">Register</button>
        </div>
        <div class="col-sm-12 col-md-6 login-btnCont">
            <button type="button" class="btn btn-primary btn-block">Login</button>
        </div>

        <p class="text-center"> Forgot Password? </p>
         <?php ActiveForm::end(); ?>
    </div>
</div>