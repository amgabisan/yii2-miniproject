$(document).ready(function() {

    /* Questionnaire List */
    if ($('#questionnaire-list').length > 0) {
        $('#questionnaire-list').DataTable({
            "order": [[3, "desc"]],
            "columnDefs": [
                { "searchable": false, "targets": [1,2,3,4] },
                { "sortable": false, "targets": [2, 4]}
            ]
        });
    }

});
