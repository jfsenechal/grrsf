$(document).ready(function () {
    var $locationSelect = $('#area_menu_select_area');
    var $specificLocationTarget = $('#area_menu_select_room');

    $locationSelect.on('change', function (e) {
        console.log("coucou");
        $.ajax({
            url: '',
            data: {
                location: $locationSelect.val()
            },
            success: function (html) {
                if (!html) {
                    $specificLocationTarget.find('select').remove();
                    $specificLocationTarget.addClass('d-none');

                    return;
                }

                // Replace the current field and show
                $specificLocationTarget
                    .html(html)
                    .removeClass('d-none')
            }
        });
    });
});
