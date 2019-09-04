/**
 * Disabled option rooms if area administrator or manger resource
 */
$(document).ready(function () {
    var typeList = $('#authorization_role');
    var authorization_rooms = $('#authorization_rooms');
    typeList.on('click', function (e) {
        loadOptionsWeeks()
    });

    loadOptionsWeeks();

    function loadOptionsWeeks() {
        var radioValue = $("input[name='authorization[role]']:checked").val();
        console.log(radioValue);
        if (radioValue === '1' || radioValue === '2') {
             authorization_rooms.prop('disabled', true);
        } else {
             authorization_rooms.prop('disabled', false);
        }
    }
});