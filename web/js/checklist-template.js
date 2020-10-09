"use strict";

$('#checklist-template-accordion').on('click', '.panel-heading', function () {
    if ($(this).attr('aria-expanded') === "false")
        $('#details-view').load('/checklist-template/checklist-template-details?checklistTemplateId=' + $(this).attr('id'));
    else
        $('#details-view').empty();
});

$('#checklist-template-accordion').on('click', '.panel-collapse tr', function () {
    $('#details-view').load('/checklist-template/checklist-item-template-details?checklistItemTemplateId=' + $(this).attr('id'));
});

$('#modalAssignTemplate').on('change', '#checklist_template_id', function () {
    $('#modalAssignTemplate #checklist_name').attr('value', $(this).children(':selected').text());
    $('#modalAssignTemplate #checklist_name').val($(this).children(':selected').text());
});

$('#modalCreateTemplate').on('change', '#checklist_template_id', function () {
    $('#modalCreateTemplate #checklist_name').attr('value', $(this).children(':selected').text());
    $('#modalCreateTemplate #checklist_name').val($(this).children(':selected').text());
});

/* $("#formAssignTemplate").submit(function () {

    myAjaxRequest('#formAssignTemplate')
        .then(() => {
            myAlert('success', 'Die Checkliste wurde erfolgreich zugewiesen!');
            $('#formAssignTemplate').clear();
        })
        .catch(() => {
            myAlert('danger', 'Beim Zuweisen einer Checkliste ist ein Fehler aufgetreten!');
        })
        .finally(() => {
            $('#modalAssignTemplate').modal('toggle');
        })

    return false;
}); */

/* $("#formAssignTemplate").submit(function () {

    let form_data = $("#formAssignTemplate").serialize();
    let action_url = $("#formAssignTemplate").attr("action");

    $.ajax({
        method: "POST",
        url: action_url,
        data: form_data
    })
        .done(function (result) {
            console.log(result);
            if (result) {
                myAlert('success', 'Die Checkliste wurde erfolgreich zugewiesen');
                $('#formAssignTemplate').clear();
            } else
                myAlert('error', 'Beim Zuweisen einer Checkliste ist ein Fehler aufgetreten');

            $('#modalAssignTemplate').modal('toggle');
        });

    return false;
}); */