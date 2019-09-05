/**
 * Disabled option rooms if area administrator or manger resource
 */
$(document).ready(function () {
    let typeList = $('#authorization_user_role');
    let authorization_rooms = $('#authorization_user_rooms');
    typeList.on('click', function (e) {
        loadOptionsWeeks()
    });

    loadOptionsWeeks();

    function loadOptionsWeeks() {
        let radioValue = $("input[name='authorization_user[role]']:checked").val();
        console.log(radioValue);
        let t = $("input:radio.form-check-input:checked");
        console.log(t);

        if (radioValue === '1' || radioValue === '2') {
            authorization_rooms.prop('disabled', true);
        } else {
            authorization_rooms.prop('disabled', false);
        }
    }
});