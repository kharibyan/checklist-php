"use strict";
/* 
let currentChecklistId;
let currentItemId; 
*/

$(function () {
    if (currentChecklistId !== 0) {
        $(`#collapse${currentChecklistId}`).collapse('show');

        if (currentItemId !== 0)
            $('#details-view').load('/checklist/checklist-item-details?checklistItemId=' + currentItemId);
        else
            $('#details-view').load('/checklist/checklist-details?checklistId=' + currentChecklistId);
    }
});

$('#details-view').on('change', '#radioStateId', function () {
    let selected_value = $("#radioStateId input[name='state_id']:checked").val();
    if (parseInt(selected_value) === 3)
        $("#details-view #comment").prop('required', true);
    else
        $("#details-view #comment").prop('required', false);
});

$('#checklist-accordion').on('click', '.panel-heading', function () {

    if ((currentChecklistId === 0 && currentItemId === 0) || (currentChecklistId !== $(this).attr('id'))) {
        currentChecklistId = $(this).attr('id');
        currentItemId = 0;
        $('#details-view').load('/checklist/checklist-details?checklistId=' + $(this).attr('id'));
    }

    else if ((currentChecklistId === $(this).attr('id')) && currentItemId === 0) {
        currentChecklistId = 0;
        $('#details-view').empty();
    }

    else if ((currentChecklistId === $(this).attr('id')) && currentItemId !== 0) {
        currentChecklistId = $(this).attr('id');
        currentItemId = 0;
        $('#details-view').load('/checklist/checklist-details?checklistId=' + $(this).attr('id'));
        return false;
    }
});

/* $('#checklist-accordion').on('click', '.panel-heading', function () {
    $('#details-view').load('/checklist/checklist-details?checklistId=' + $(this).attr('id'));
    currentChecklistId = $(this).attr('id');
    currentItemId = 0;
}); */

/* $('#checklist-accordion').on('click', '.panel-heading', function () {
    if ($(this).attr('aria-expanded') === "false")
        $('#details-view').load('/checklist/checklist-details?checklistId=' + $(this).attr('id'));
    else
        $('#details-view').empty();
}); */

$('#checklist-accordion').on('click', '.panel-collapse tr', function () {
    $('#details-view').load('/checklist/checklist-item-details?checklistItemId=' + $(this).attr('id'));
    currentItemId = $(this).attr('id');
});

$('#modalCreateChecklist').on('change', '#checklist_template_id', function () {
    $('#modalCreateChecklist #checklist_name').attr('value', $(this).children(':selected').text());
    $('#modalCreateChecklist #checklist_name').val($(this).children(':selected').text());
});

$('#modalCreateItem').on('shown.bs.modal', function (e) {
    $('#formCreateItem #checklist_id').val(currentChecklistId);
});

$('#btnMoveUp').add('#btnMoveDown').on('click', function () {
    if (currentChecklistId === 0 || currentItemId === 0)
        return;

    let data = {
        /* checklist_template_id: currentChecklistId, */
        id: currentItemId
    };

    let url = $(this).attr('href');

    myAjaxRequest(url, data);
});