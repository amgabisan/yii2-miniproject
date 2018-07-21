$(document).ready(function() {

    /* Questionnaire List */
    if ($('#questionnaire-list').length > 0) {
        $('#questionnaire-list').DataTable({
            "order": [[3, "desc"]]
        });
    }

});
