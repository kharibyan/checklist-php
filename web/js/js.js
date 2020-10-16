"use strict";

$(function () {
    setTimeout(function () {
        $('.alert-message').remove();
    }, 5000);
});

function myAlert(type, message, duration = 5) {
    $('#alert-placeholder').append(
        `<div class="alert alert-${type} alert-dismissible">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>${message}</strong>
        </div >`
    );

    setTimeout(function () {

        $('#alert-placeholder').empty();

    }, duration * 1000);
}

function myAjaxRequest(url, data) {
    return new Promise((resolve, reject) => {

        // console.log(url, data)

        $.ajax({
            method: 'POST',
            url: url,
            data: data
        })
            .done(function (result) {

                if (result)
                    return resolve();
                else
                    return reject();
            });
    })
}

jQuery.fn.clear = function () {
    var $form = $(this);

    $form.find('input:text, input:password, input:file, textarea').attr('value', '');
    $form.find('input:text, input:password, input:file, textarea').val('');
    $form.find('select').val('');
    $form.find('input:radio, input:checkbox')
        .removeAttr('checked').removeAttr('selected');

    return this;
};