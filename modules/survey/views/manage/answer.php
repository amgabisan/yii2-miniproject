<?php
    use kartik\rating\StarRating;
    use yii\bootstrap\ActiveForm;
?>

<div class="page-header text-center">
    <h3><?= $model->name ?></h3>
</div>

<div class="page-content">
    <?php if (Yii::$app->session->hasFlash('error')) { ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Error!</strong> <?= Yii::$app->session->getFlash('error'); ?>
        </div>
    <?php } ?>

    <?php
        $form = ActiveForm::begin([
            'id' => 'answer-form',
        ]);
    ?>
    <?php foreach ($question as $key => $value) { ?>
    <div class="questionContainer">
        <h3><?= ($key+1).' '.$value['question'] ?></h3>
        <input type="hidden" name="UserAnswer[<?= $key ?>][id]" value="<?= $value['id'] ?>">
        <?php if ($value['type'] == 'single_free_input') { ?>
        <input type="text" name="UserAnswer[<?= $key ?>][answer]" class="form-control <?= ($value['required_flag']) ? 'is-required' : '' ?>" <?= ($value['required_flag']) ? 'required' : '' ?>>
        <?php } else if ($value['type'] == 'multiple_free_input') { ?>
        <textarea class="form-control <?= ($value['required_flag']) ? 'is-required' : '' ?>" name="UserAnswer[<?= $key ?>][answer]" <?= ($value['required_flag']) ? 'required' : '' ?>> </textarea>
        <?php } else if ($value['type'] == 'single_choice') {  ?>
        <div class="singleChoiceContainer <?= ($value['required_flag']) ? 'is-required' : '' ?>">
            <?php
                $choices = explode(",", $value['type_details']);
                foreach ($choices as $choice) {
            ?>
            <div class="radio">
                <label>
                    <input type="radio" name="UserAnswer[<?= $key ?>][answer]" value="<?= $choice ?>"> <?= $choice ?>
                </label>
            </div>
            <?php } ?>
        </div>
        <?php } else if ($value['type'] == 'multiple_choice') {  ?>
        <div class="multipleChoiceContainer <?= ($value['required_flag']) ? 'is-required' : '' ?>">
            <?php
                $choices = explode(",", $value['type_details']);
                foreach ($choices as $choice) {
            ?>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="UserAnswer[<?= $key ?>][answer][]" value="<?= $choice ?>"> <?= $choice ?>
                    </label>
                </div>
            <?php } ?>
        </div>
        <?php } else {
            echo StarRating::widget(['name' => "UserAnswer[$key][answer]",
                'pluginOptions' => [
                    'stars' => $value['type_details'],
                    'min' => 0,
                    'max' => $value['type_details'],
                    'step' => 1,
                    'defaultCaption' => '{rating} Stars'
                ]
            ]);

} ?>


    </div>
    <?php } ?>
    <hr />
    <div class="row text-center">
        <button type="submit" class="btn btn-lg btn-primary" id="save-btn">Submit Survey</button>
    </div>
    <?php ActiveForm::end() ?>
</div>
