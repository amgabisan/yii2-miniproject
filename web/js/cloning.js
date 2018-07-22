var questionCounter = 0;
$(document).ready(function() {
    var questionType = null;
    var pregenerated_form = [];

    if ($('.pregenerated').length != 0) {
        $('.pregenerated').each(function() {
            pregenerated_form.push($(this).attr('id'));
        });
    }

    var sheepItForm = $('#sheepItForm').sheepIt({
        separator: '',
        allowRemoveLast: true,
        allowRemoveCurrent: true,
        allowRemoveAll: true,
        allowAdd: true,
        allowAddN: true,
        maxFormsCount: 15,
        minFormsCount: 0,
        iniFormsCount: 0,
        pregeneratedForms: pregenerated_form,
        afterAdd: function(source, newForm) {
            if (newForm.hasClass('pregenerated')) {
                questionType = newForm.find('.row').data('qtype');
            }

            if (questionType == 'freeinput-btn' || questionType == 'single_free_input' || questionType == 'multiple_free_input') {
                newForm.find('.choiceContainer').remove();
                newForm.find('.ratingContainer').remove();
                if (!newForm.hasClass('pregenerated')) {
                    newForm.find('.freeInputContainer').find('input[type="radio"]').first().prop('checked', true);
                } else {
                    newForm.find('.freeInputContainer').find('input[type="radio"][value=' + questionType + ']').prop('checked', true);
                }
            } else if (questionType == 'choice-btn' || questionType == 'single_choice' || questionType == 'multiple_choice') {
                newForm.find('.freeInputContainer').remove();
                newForm.find('.ratingContainer').remove();
            } else {
                newForm.find('.freeInputContainer').remove()
                newForm.find('.choiceContainer').remove();
            }

            questionType = null;
            questionCounter++;

            if (questionCounter < 15) {
                $('.btn-add').prop('disabled', false);
            } else {
                $('.btn-add').prop('disabled', true);
            }
        },
    });

    $('.btn-add').click(function() {
        questionType = $(this).attr('id');
        $('#sheepItForm_add').trigger('click');
    });
});

$(document).on('click', '.delete-btn', function() {
    questionCounter--;

    if (questionCounter < 15) {
        $('.btn-add').prop('disabled', false);
    } else {
        $('.btn-add').prop('disabled', true);
    }
});

$(document).on('blur', '.questions', function() {
    if ($(this).val().trim().length == 0) {
        $(this).addClass('has-error-field');
    } else {
        $(this).removeClass('has-error-field');
    }
});

$(document).on('blur', '.choices-text', function() {
    if ($(this).val().trim().length == 0) {
        $(this).addClass('has-error-field');
    } else {
        $(this).removeClass('has-error-field');
    }
});

$(document).on('blur', '.stars', function() {
    if ($(this).val().trim().length == 0) {
        $(this).addClass('has-error-field');
    } else {
        $(this).removeClass('has-error-field');
    }
});

$('#save-btn').click(function() {
    var flag = 0;
    var emptyField = 0;
    var msg = '';

    $('#questionnaire-name').blur();

    $('.questions').each(function() {
        if ($(this).val().trim().length == 0) {
            $(this).addClass('has-error-field');
            emptyField++;
        }
    });

    $('.choices-text').each(function() {
        if ($(this).val().trim().length == 0) {
            $(this).addClass('has-error-field');
            emptyField++;
        }
    });

    $('.stars').each(function() {
        if ($(this).val().trim().length == 0) {
            $(this).addClass('has-error-field');
            emptyField++;
        }
    });

    if ($('.surveyContainer').length == 0) {
        flag++;
        msg += '* Must add at least 1 question and at most of 15 question for the survey.<br />';
    }

    if ($('.has-error').length != 0) {
        emptyField++;
    }

    if (emptyField != 0) {
        msg += '* Please populate the empty fields. <br />';
    }

    console.log("flag = " + flag);
    console.log("empty field = " + emptyField);

    if (flag == 0 && emptyField == 0) {
        $('#survey-form').submit();
    } else {
        $('#errorAlert').find('p').html(msg);
        $('#errorAlert').show();
        return false;
    }
});
