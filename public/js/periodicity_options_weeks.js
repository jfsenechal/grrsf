/**
 * Display/Hide options for week periodicity
 */
$(document).ready(function () {
    var typeList = $('#entry_periodicity_type');
    var periodicityZone = $('#weeks_options');
    typeList.on('click', function (e) {
        loadOptionsWeeks()
    });

    loadOptionsWeeks();

    function loadOptionsWeeks() {
        var radioValue = $("input[name='entry[periodicity][type]']:checked").val();
        console.log(radioValue);
        if (radioValue === '2') {

            periodicityZone.removeClass('d-none');
        } else {
            if (!periodicityZone.hasClass('d-none')) {
                periodicityZone.addClass('d-none');
            }
        }
    }
});