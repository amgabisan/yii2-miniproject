<?php
    use yii\helpers\Html;
    use app\assets\CloningAsset;
    use yii\bootstrap\ActiveForm;

    CloningAsset::register($this);

    $this->title = 'Survey Creation';
?>

<div class="page-header text-center">
    <h3>Survey Creation</h3>
</div>
<div class="page-content">
    <?php if (Yii::$app->session->hasFlash('error')) { ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Error!</strong> <?= Yii::$app->session->getFlash('error'); ?>
        </div>
    <?php } ?>

    <div class="alert alert-danger alert-dismissible" role="alert" style="display:none" id="errorAlert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Error!</strong> Please check the following errors:
        <p></p>
    </div>

    <?php
        $form = ActiveForm::begin([
            'id' => 'survey-form',
            'layout' => 'horizontal',
            'fieldConfig' => [
                'template' => "{label}\n{beginWrapper}\n{input}{error}\n{endWrapper}",
                'horizontalCssClasses' => [
                    'label' => 'col-sm-1',
                    'offset' => 'col-sm-offset-3',
                    'wrapper' => 'col-sm-8',
                    'error' => '',
                    'hint' => '',
                ],
            ],
        ])
    ?>

    <?= $form->field($model, 'name') ?>

    <div class="row">
        <ul class="list-inline text-right">
            <a id="sheepItForm_add" style="display:none"></a>
            <li>Question Type:</li>
            <li>
                <button type="button" class="btn btn-primary btn-add" id="freeinput-btn">Add Free Answer</button>
            </li>
            <li>
                <button type="button" class="btn btn-success btn-add" id="choice-btn">Add Multiple Choice</button>
            </li>
            <li>
                <button type="button" class="btn btn-info btn-add" id="rating-btn">Add Rating</button>
            </li>
        </ul>
    </div>

    <div id="sheepItForm">
        <!-- template -->
        <div id="sheepItForm_template" class="surveyContainer">
            <a id="sheepItForm_remove_current" class="delete-btn">
                <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </a>
            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-1"><label for="sheepItForm_#index#_question">Question</label></div>
                <div class="col-md-11">
                    <input id="sheepItForm_#index#_question" name="Question[#index#][question]" type="text" class="form-control questions"/>
                </div>
                <div class="clearfix"></div>

                <!-- Free Input -->
                <div class="freeInputContainer">
                    <div class="col-md-12">
                        <label for="sheepItForm_#index#_type">Question Type</label> &nbsp;&nbsp;
                        <label class="radio-inline">
                          <input type="radio" name="Question[#index#][type]" id="sheepItForm_#index#_type" value="single_free_input" checked> Single Line Free Answer
                        </label>
                        <label class="radio-inline">
                          <input type="radio" name="Question[#index#][type]" id="sheepItForm_#index#_type" value="multiple_free_input"> Multiple Line Free Answer
                        </label>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <!-- Choice -->
                <div class="choiceContainer">
                    <div class="col-md-12">
                        <label for="sheepItForm_#index#_type">Question Type</label> &nbsp;&nbsp;
                        <label class="radio-inline">
                          <input type="radio" name="Question[#index#][type]" id="sheepItForm_#index#_type" value="single_choice" checked> Single Choice
                        </label>
                        <label class="radio-inline">
                          <input type="radio" name="Question[#index#][type]" id="sheepItForm_#index#_type" value="multiple_choice"> Multiple Choice
                        </label>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-1"><label for="sheepItForm_#index#_choice">Choices</label></div>
                    <div class="col-md-11">
                        <input type="text" class="form-control choices-text" placeholder="Separate each choice with comma" id="sheepItForm_#index#_choices" name="Question[#index#][choices]">
                    </div>
                    <div class="clearfix"></div>
                </div>

                <!-- Rating -->
                <div class="ratingContainer">
                    <input type="hidden" id="sheepItForm_#index#_type" name="Question[#index#][type]" value="rating">
                    <div class="col-md-1"><label for="sheepItForm_#index#_rating">Stars</label></div>
                    <div class="col-md-11">
                        <input id="sheepItForm_#index#_no_of_stars" name="Question[#index#][no_of_stars]" type="number" class="form-control stars" max="10" min="1" placeholder="Number of Stars for the rating"/>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="col-md-1"><label for="sheepItForm_#index#_required">Required?</label></div>
                <div class="col-md-11">
                    <label class="radio-inline">
                      <input type="radio" name="Question[#index#][required]" id="sheepItForm_#index#_required" value="Yes" checked> Yes
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="Question[#index#][required]" id="sheepItForm_#index#_required" value="No"> No
                    </label>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>

        <?php
            if (isset($questions)) {
                foreach ($questions as $key => $question) {
        ?>
                <!-- template -->
        <div id="pregenerated_form_<?= $key ?>" class="surveyContainer pregenerated">
                <a id="sheepItForm_remove_current" class="delete-btn">
                    <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </a>
                <div class="clearfix"></div>

                <div class="row" data-qtype="<?= $question['type'] ?>" data-details="<?= $question['type_details'] ?>">
                    <div class="col-md-1"><label for="sheepItForm_#index#_question">Question</label></div>
                    <div class="col-md-11">
                        <input id="sheepItForm_#index#_question" name="Question[#index#][question]" type="text" class="form-control questions" value="<?= $question['question'] ?>" />
                    </div>
                    <div class="clearfix"></div>


                    <div class="freeInputContainer">
                        <div class="col-md-12">
                            <label for="sheepItForm_#index#_type">Question Type</label> &nbsp;&nbsp;
                            <label class="radio-inline">
                              <input type="radio" name="Question[#index#][type]" id="sheepItForm_#index#_type" value="single_free_input" <?= ($question['type'] == 'single_free_input') ? 'checked' : '' ?> class="question-type"> Single Line Free Answer
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="Question[#index#][type]" id="sheepItForm_#index#_type" value="multiple_free_input" <?= ($question['type'] == 'single_free_input') ? '' : 'checked' ?> class="question-type"> Multiple Line Free Answer
                            </label>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="choiceContainer">
                        <div class="col-md-12">
                            <label for="sheepItForm_#index#_type">Question Type</label> &nbsp;&nbsp;
                            <label class="radio-inline">
                              <input type="radio" name="Question[#index#][type]" id="sheepItForm_#index#_type" value="single_choice" <?= ($question['type'] == 'single_choice') ? 'checked' : '' ?>> Single Choice
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="Question[#index#][type]" id="sheepItForm_#index#_type" value="multiple_choice" <?= ($question['type'] == 'single_choice') ? '' : 'checked' ?>> Multiple Choice
                            </label>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-1"><label for="sheepItForm_#index#_choice">Choices</label></div>
                        <div class="col-md-11">
                            <input type="text" class="form-control choices-text" placeholder="Separate each choice with comma" id="sheepItForm_#index#_choices" name="Question[#index#][choices]" value="<?= $question['type_details'] ?>">
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="ratingContainer">
                        <input type="hidden" id="sheepItForm_#index#_type" name="Question[#index#][type]" value="rating">
                        <div class="col-md-1"><label for="sheepItForm_#index#_rating">Stars</label></div>
                        <div class="col-md-11">
                            <input id="sheepItForm_#index#_no_of_stars" name="Question[#index#][no_of_stars]" type="number" class="form-control stars" max="10" min="1" placeholder="Number of Stars for the rating" value="<?= $question['type_details'] ?>"/>
                        </div>
                        <div class="clearfix"></div>
                    </div>


                    <div class="col-md-1"><label for="sheepItForm_#index#_required">Required?</label></div>
                    <div class="col-md-11">
                        <label class="radio-inline">
                          <input type="radio" name="Question[#index#][required]" id="sheepItForm_#index#_required" value="Yes" <?= ($question['required_flag']) ? 'checked' : '' ?>> Yes
                        </label>
                        <label class="radio-inline">
                          <input type="radio" name="Question[#index#][required]" id="sheepItForm_#index#_required" value="No" <?= ($question['required_flag']) ? '' : 'checked' ?>> No
                        </label>
                    </div>
                <div class="clearfix"></div>
                </div>
        </div>


        <?php } } ?>

        <!-- No forms template -->
        <div id="sheepItForm_noforms_template" class="text-center text-danger">No questions added. Please choose on any question type to start adding questions.</div>
        <!-- /No forms template-->

    </div>

    <hr>

    <ul class="list-inline text-center">
        <li>
            <a href="/dashboard" class="btn btn-danger">Back</a>
        </li>
        <li>
            <button type="button" class="btn btn-primary" id="save-btn">Save</button>
        </li>
    </ul>

    <?php ActiveForm::end() ?>
</div>
