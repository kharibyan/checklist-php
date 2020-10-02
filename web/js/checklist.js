"use strict";

$('#checklist-accordion').on('click', '.panel-heading', function () {
    if ($(this).attr('aria-expanded') === "false")
        $('#details-view').load('/checklist/checklist-details?checklistId=' + $(this).attr('id'));
    else
        $('#details-view').empty();
});

$('#checklist-accordion').on('click', '.panel-collapse tr', function () {
    $('#details-view').load('/checklist/checklist-item-details?checklistItemId=' + $(this).attr('id'));
});