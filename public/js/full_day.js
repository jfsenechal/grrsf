/**
 * Enabled/disabled input
 */
$(document).ready(function () {
    var fullDayCheckbox = $('#entry_duration_full_day');
    var durationTime = $('#entry_duration_time');
    var durationUnit = $('#entry_duration_unit');

    fullDayCheckbox.on('click', function (e) {
       setProp();
    });

    setProp();

    function setProp() {
        if (fullDayCheckbox.prop("checked") === true) {
            durationTime.prop('disabled', true);
            durationUnit.prop('disabled', true);
        } else {
            durationTime.prop('disabled', false);
            durationUnit.prop('disabled', false);
        }
    }
});