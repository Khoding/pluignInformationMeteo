jQuery(document).ready(function ($) {
    $('#datePicker')
        .datepicker({
            dateFormat: 'dd.mm.yy'
        })
        .datepicker('setDate', 'now');

    var str = $('#datePicker').val();
    var d = new Date();
    var strDate =
        String(d.getDate()).padStart(2, '0') +
        '.' +
        String(d.getMonth() + 1).padStart(2, '0') +
        '.' +
        d.getFullYear();
    if (str == strDate) {
        $('#meteoSelect').val(0);
        $('#pistesSelect').val(0);
        $('#neigeSelect').val(0);
    }

    $('.radioWebClass')
        .children()
        .on('change', function () {
            $id_webRa = this.id;
            $.post(
                '../wp-content/plugins/informationMeteo/json/webcam.json.php?_=' +
                Date.now(),
                {
                    id_webRa: $id_webRa,
                    id_webRaChange: "changed"
                },
                function (data) {
                    $(".check_webcam").prop("disabled", "");
                    $("#webcamId_" + data.id_webRa + " .check_webcam").prop("disabled", "true");
                    $("#webcamId_" + data.id_webRa + " .check_webcam").prop("checked", "");
                    $( "#webcamSelectId" ).load(window.location.href + " #webcamSelectId" );
                }
            );
        });

    $('.checkBoxWebClass')
        .children()
        .on('click', function () {
            if ($(this).is(':checked')) {
                $checkInAc = 'checked';
            } else {
                $checkInAc = 'notChecked';
            }
            $id_webCh = this.id;
            $.post(
                '../wp-content/plugins/informationMeteo/json/webcam.json.php?_=' +
                Date.now(),
                {
                    id_webCh: $id_webCh,
                    chWeb: $checkInAc
                }
            );
        });

    $('#datePicker').on('change', function () {
        var str = $('#datePicker').val();
        var d = new Date();
        var strDate =
            String(d.getDate()).padStart(2, '0') +
            '.' +
            String(d.getMonth() + 1).padStart(2, '0') +
            '.' +
            d.getFullYear();
        if (str == strDate) {
            $('.checkBoxInstallationInput').attr('disabled', false);
        } else {
            $('.checkBoxInstallationInput').attr('disabled', true);
        }
    });

    $('.checkBoxInstallationInput').on('click', function () {
        if ($(this).is(':checked')) {
            $checkInAc = 'checked';
            if (this.id == 0) {
                $('.checkBoxInstallationInput').each(function () {
                    $('.checkBoxInstallationInput').prop('checked', true);
                });
            }
        } else {
            $checkInAc = 'notChecked';
            if (this.id == 0) {
                $('.checkBoxInstallationInput').each(function () {
                    $('.checkBoxInstallationInput').prop('checked', false);
                });
            }
        }
        if (this.id == 0) {
            $('.checkBoxInstallationInput').each(function () {
                $installIdCheced = this.id;
                $.post(
                    '../wp-content/plugins/informationMeteo/json/installation.json.php?_=' +
                    Date.now(),
                    {
                        ch: $checkInAc,
                        last_id_ins: $installIdCheced,
                        date_ins: $('#datePicker').val()
                    }
                );
            });
        } else {
            $installIdCheced = this.id;
            $.post(
                '../wp-content/plugins/informationMeteo/json/installation.json.php?_=' +
                Date.now(),
                {
                    ch: $checkInAc,
                    last_id_ins: $installIdCheced,
                    date_ins: $('#datePicker').val()
                }
            );
        }
    });

    $.validator.addMethod('valueNotEquals', function (value, element, arg) {
        return arg !== value;
    });

    $('#meteoBulletinForm').validate({
        debug: true,
        rules: {
            timeInput: {
                required: true
            },
            tempInputName: {
                required: true,
                min: -50,
                max: 50
            },
            meteoSelect: {valueNotEquals: '0'},
            pistesSelect: {valueNotEquals: '0'},
            neigeSelect: {valueNotEquals: '0'},

            txtInput: {
                minlength: 2,
                maxlength: 255
            }
        },
        messages: {
            timeInput: {
                required: 'Veuillez saisir une heure'
            },
            tempInputName: {
                required: 'Veuillez saisir une temperateur',
                min: 'la température minimum est -50°C',
                max: 'la température maximum est 50°C'
            },
            meteoSelect: {valueNotEquals: 'veuillez sélectionner'},
            pistesSelect: {valueNotEquals: 'veuillez sélectionner'},
            neigeSelect: {valueNotEquals: 'veuillez sélectionner'},
            txtInput: {
                minlength: 'Veuillez saisir plus que 2 caractère',
                maxlength: 'Veuillez saisir mois de 255 caractère'
            }
        },

        submitHandler: function (form) {
            console.log('Formulaire envoyé');
            $.post(
                '../wp-content/plugins/informationMeteo/json/meteo.json.php?_=' +
                Date.now(),
                {
                    date_bul: $('#datePicker').val(),
                    heure_bul: $('#timeInput').val(),
                    temperature_bul: $('#tempInput').val(),
                    id_met: $('#meteoSelect').val(),
                    id_pst: $('#pistesSelect').val(),
                    id_nge: $('#neigeSelect').val(),
                    id_web: $('#webcamSelect').val(),
                    texte_bul: $('#txtInput').val()
                }
            );
            alert('validation a été fait avec succès');
            location.reload();
        }
    });
});
