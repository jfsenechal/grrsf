/**
 * Affiche la liste des jours de la semaine
 */
$(document).ready(function () {
    var typeList = $('#periodicity_type');
    var periodicityZone = $('#periodicity_days');
    typeList.on('click', function (e) {
        var radioValue = $("input[name='periodicity[type]']:checked").val();
        if (radioValue === '2') {
            periodicityZone.removeClass('d-none');
        } else {
            if (!periodicityZone.hasClass('d-none')) {
                periodicityZone.addClass('d-none');
            }
        }
    });
});