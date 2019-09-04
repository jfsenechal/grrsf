/**
 * Display/Hide options for week periodicity
 */
$(document).ready(function () {
    let typeList = $('#entry_periodicity_type');
    let periodicityZone = $('#weeks_options');
    typeList.on('click', function (e) {
        loadOptionsWeeks()
    });

    loadOptionsWeeks();

    function loadOptionsWeeks() {
        let radioValue = $("input[name='entry[periodicity][type]']:checked").val();
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