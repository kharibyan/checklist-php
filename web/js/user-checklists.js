"use strict";

// let currentChecklistId;

$(function () {
    $('#selectUser').on('change', function () {

        let value = $(this).children(':selected').text().toLowerCase();
        $("#checklist-list tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});

$('#checklist-list').on('click', 'tr', function () {
    $('#details-view').load('/user-checklists/checklist-details?checklistId=' + $(this).attr('id'));
});

$('#details-view').on('click', '#btnDelete', function () {
    if (currentChecklistId !== 0)
        if (confirm('Bist Du sicher, dass Du diese Checkliste entgültig löschen möchtest?')) {
            let data = {
                id: currentChecklistId
            };

            let url = $(this).attr('href');

            myAjaxRequest(url, data);
        }

    return false;
})