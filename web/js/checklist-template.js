"use strict";

/* 
let currentChecklistId;
let currentItemId; 
*/

$(function () {
    if (currentChecklistId !== 0) {
        $(`#collapse${currentChecklistId}`).collapse('show');

        if (currentItemId !== 0)
            $('#details-view').load('/checklist-template/checklist-item-template-details?checklistItemTemplateId=' + currentItemId);
        else
            $('#details-view').load('/checklist-template/checklist-template-details?checklistTemplateId=' + currentChecklistId);
    }
});


$('#checklist-template-accordion').on('click', '.panel-heading', function () {
    $('#details-view').load('/checklist-template/checklist-template-details?checklistTemplateId=' + $(this).attr('id'));
    currentChecklistId = $(this).attr('id');
    currentItemId = 0;
});

$('#checklist-template-accordion').on('click', '.panel-collapse tr', function () {
    $('#details-view').load('/checklist-template/checklist-item-template-details?checklistItemTemplateId=' + $(this).attr('id'));
    currentItemId = $(this).attr('id');
});

$('#modalAssignTemplate').on('change', '#checklist_template_id', function () {
    $('#modalAssignTemplate #checklist_name').attr('value', $(this).children(':selected').text());
    $('#modalAssignTemplate #checklist_name').val($(this).children(':selected').text());
});

$('#modalCreateTemplate').on('change', '#checklist_template_id', function () {
    $('#modalCreateTemplate #checklist_name').attr('value', $(this).children(':selected').text());
    $('#modalCreateTemplate #checklist_name').val($(this).children(':selected').text());
});

$("#formAssignTemplate").submit(function () {

    let form_data = $('#formAssignTemplate').serialize();
    let action_url = $('#formAssignTemplate').attr('action');

    myAjaxRequest(action_url, form_data)
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
});

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

$('#modalCreateItem').on('shown.bs.modal', function (e) {
    $('#formCreateItem #checklist_template_id').val(currentChecklistId);
});

$('#btnMoveUp').add('#btnMoveDown').on('click', function () {
    if (currentChecklistId === 0 || currentItemId === 0)
        return;

    let data = {
        /* checklist_template_id: currentChecklistId, */
        item_template_id: currentItemId
    };

    let url = $(this).attr('href');

    myAjaxRequest(url, data);
});

$('#linkDeleteItem').on('click', function () {
    if (currentChecklistId !== 0 && currentItemId !== 0)
        if (confirm('Bist Du sicher, dass Du dieses Item löschen möchtest?')) {
            let data = {
                item_template_id: currentItemId
            };

            let url = $(this).attr('href');

            myAjaxRequest(url, data);
        }

    return false;
})

$('#linkDeleteTemplate').on('click', function () {
    if (currentChecklistId !== 0)
        if (confirm('Bist Du sicher, dass Du dieses Template löschen möchtest?')) {
            let data = {
                checklist_template_id: currentChecklistId
            };

            let url = $(this).attr('href');

            myAjaxRequest(url, data);
        }

    return false;
})