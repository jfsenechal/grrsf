/**
 * Disabled option rooms if area administrator or manger resource
 */
$(document).ready(function () {
    let typeList = $('#authorization_role');
    let authorization_rooms = $('#authorization_rooms');
    typeList.on('click', function (e) {
        loadOptionsWeeks()
    });

    loadOptionsWeeks();

    function loadOptionsWeeks() {
        let radioValue = $("input[name='authorization[role]']:checked").val();

        if (radioValue === '1' || radioValue === '2') {
            authorization_rooms.prop('disabled', true);
        } else {
            authorization_rooms.prop('disabled', false);
        }
    }
});