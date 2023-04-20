"use strict";


$(document).ready(function () {
//send form

    var data_table = [];
    if ($('.table-data-edit').length > 0) {
        if ($('.table-data-add-sect2').length) {
            getDataToTableSect2($('.table-data-edit').data('action'), $('.table-data-edit').data('id'), '.table-data-add-sect2')

        } else {
            getDataToTable($('.table-data-edit').data('action'), $('.table-data-edit').data('id'), '.table-data-edit')


        }

    }
    $('.change-and-consult').on('change', function () {

        var instance = this;
        var request_id = $(instance).val();


        if (request_id) {
            data_table = [];
            $.ajax({

                data: {a: $(instance).data("action"), id: $(instance).val()},
                url: "./?action=admin",
                type: "POST",
                dataType: "JSON",
                success: function (data) {
                    console.log('aqui',data)
                    if ($("#" + $(instance).data("form")).length > 0) {
                        $("#" + $(instance).data("form"))[0].reset();
                    }

                    if ($('#total-table').length > 0) {
                        $('#total-table').html('0.00')

                    }
                    if ($('.table-data-add').length > 0) {
                        $('.table-data-add').html('')
                        getDataToTable($('.table-data-add').data('action'), request_id)
                    }
                    if ($('.table-data-add-sect2').length > 0) {

                        $('.table-data-add-sect2').html('');
                        $('#unit-buy').html('0.00')
                        $('#unit-request').html('0.00')
                        $('#unit-diff').html('0.00')
                        getDataToTableSect2($('.table-data-add-sect2').data('action'), request_id)

                    }


                    if (data.data) {

                        jQuery(data.data).each(function (index, value) {

                            if (value.type == 'input') {
                                if ($('#' + value.id).length > 0) {
                                    $('#' + value.id).val(value.value)

                                }
                            }
                            if (value.type == 'select') {
                                if ($('#' + value.id).length > 0) {
                                    $('#' + value.id).val(value.value).change();
                                }
                            }

                        });

                    }

                    $(instance).val(data.id)


                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    console.log("Status: " + textStatus + " Error: " + XMLHttpRequest.responseText);

                }

            });
        } else {

            if ($("#" + $(instance).data("form")).length > 0) {
                $("#" + $(instance).data("form"))[0].reset();
            }
            if ($('.table-data-add').length > 0) {
                $('.table-data-add').html('')
            }
            if ($('#total-table').length > 0) {
                $('#total-table').html('0.00')

            }
            if ($('[data-reset-select-field="true"]').length > 0) {
                $('[data-reset-select-field="true"]').val('').change()

            }
            if ($('.table-data-add-sect2').length > 0) {

                $('.table-data-add-sect2').html('');
                $('#unit-buy').html('0.00')
                $('#unit-request').html('0.00')
                $('#unit-diff').html('0.00')

            }

        }

    });
    $('.change-and-consult-edit').on('change', function () {

        var instance = this;
        var request_id = $(instance).val();

        if (request_id) {
            data_table = [];
            $.ajax({

                data: {a: $(instance).data("action"), id: $(instance).val()},
                url: "./?action=admin",
                type: "POST",
                dataType: "JSON",
                success: function (data) {

                    if ($("#" + $(instance).data("form")).length > 0) {
                        $("#" + $(instance).data("form"))[0].reset();
                    }
                    if ($('.table-data-edit').length > 0) {
                        $('.table-data-edit').html('')
                    }
                    if ($('#total-table').length > 0) {
                        $('#total-table').html('0.00')

                    }
                    if ($('.table-data-edit').length > 0 && $('.table-data-add-sect2').length == 0) {
                        getDataToTable($('.table-data-edit').data('action-change'), request_id, '.table-data-edit')
                    }
                    if ($('.table-data-add-sect2').length > 0) {

                        $('.table-data-add-sect2').html('');
                        $('#unit-buy').html('0.00')
                        $('#unit-request').html('0.00')
                        $('#unit-diff').html('0.00')

                        getDataToTableSect2($('.table-data-add-sect2').data('action-change'), request_id)

                    }

                    if(data.data){
                        if (data.data.length > 0) {

                            jQuery(data.data).each(function (index, value) {

                                if (value.type == 'input') {
                                    if ($('#' + value.id).length > 0) {
                                        $('#' + value.id).val(value.value)

                                    }
                                }
                                if (value.type == 'select') {
                                    if ($('#' + value.id).length > 0) {
                                        $('#' + value.id).val(value.value).change();
                                    }
                                }

                            });

                        }
                    }


                    $(instance).val(data.id)

                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    console.log("Status: " + textStatus + " Error: " + XMLHttpRequest.responseText);

                }

            });
        } else {

            if ($("#" + $(instance).data("form")).length > 0) {
                $("#" + $(instance).data("form"))[0].reset();
            }
            if ($('.table-data-add').length > 0) {
                $('.table-data-add').html('')
            }
            if ($('#total-table').length > 0) {
                $('#total-table').html('0.00')

            }
            if ($('[data-reset-select-field="true"]').length > 0) {
                $('[data-reset-select-field="true"]').val('').change()

            }

        }

    });

    $('.set-status-document').on('click', function (e) {
        e.preventDefault()
        var action = $(this).data('action');
        var id = $(this).data('id')
        var myArrayOfThings = [
            {id: 'ACTIVO', name: 'ACTIVO'},
            {id: 'CERRADO', name: 'CERRADO'},
            {id: 'APROBADA', name: 'APROBADA'},
            {id: 'CANCELADA', name: 'CANCELADA'}
        ];


        $.ajax({ 
            data: {a: action + '-GET-CURRENT-STATUS', id: id},
            url: "./?action=admin",
            type: "POST",
            dataType: "JSON",
            success: function (data) {
                if (data.success) {

                    var currentStatus = data.status;
                    var options = {};
                    $.map(myArrayOfThings, function (data) {
                        options[data.id] = data.name;
                    });

                    Swal.fire({
                        title: 'Selecione status',
                        input: 'select',
                        inputOptions: options,
                        inputValue: currentStatus,
                        showCancelButton: true,
                        inputValidator: function (value) {
                            return new Promise(function (resolve, reject) {
                                if (value !== '') {

                                    if (value == currentStatus) {

                                        resolve('Nose puede actualizar con el mismo status');
                                    } else {

                                        resolve();
                                    }
                                } else {
                                    resolve('Este campo es requerido');
                                }
                            });
                        }
                    }).then(function (result) {
                        if (result.isConfirmed) {

                            $.ajax({

                                data: {a: action, id: id,status:result.value},
                                url: "./?action=admin",
                                type: "POST",
                                dataType: "JSON",
                                success: function (data) {

                                    if (data.success) {
                                        if (data.reload) {
                                            let timerInterval
                                            Swal.fire({
                                                title: 'Recargando',
                                                html: data.mens,
                                                timer: 1000,
                                                timerProgressBar: true,
                                                didOpen: () => {
                                                    Swal.showLoading()
                                                },
                                                willClose: () => {
                                                    clearInterval(timerInterval)
                                                }
                                            }).then((result) => {
                                                if (result.dismiss === Swal.DismissReason.timer) {
                                                    location.reload()
                                                }
                                            })

                                        }



                                    } else {

                                        manageShowAlertFormError(true);

                                        $("#ht-preloader").css("display", "none");

                                        Swal.fire('¡Alerta!', data.mens, 'error')
                                        $('.new-alert-error').html('<div class="row">' +
                                            '<div class="col-md-12">' +
                                            '<button type="button" class="close pull-right close-alert-div" data-target="new-alert-error" data-add="alert-error-none">x</button>' +
                                            '</div>' +
                                            ' <div class="col-md-12">' +
                                            '<h4><i class="material-icons">warning</i> ' + data.mens + '</h4>' +
                                            '</div>' +
                                            '</div>');


                                    }


                                },
                                error: function (XMLHttpRequest, textStatus, errorThrown) {
                                    BtnReset(instance)
                                    console.log("Status: " + textStatus + " Error: " + XMLHttpRequest.responseText);

                                }

                            });

                        }
                    });

                } else {

                    //currentStatus="Error - "+data.mens
                    Swal.fire('¡Alerta!', data.mens, 'error')
                    $('.new-alert-error').html('<div class="row">' +
                        '<div class="col-md-12">' +
                        '<button type="button" class="close pull-right close-alert-div" data-target="new-alert-error" data-add="alert-error-none">x</button>' +
                        '</div>' +
                        ' <div class="col-md-12">' +
                        '<h4>' + data.mens + '</h4>' +
                        '</div>' +
                        '</div>');
                }

            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log("Status: " + textStatus + " Error: " + XMLHttpRequest.responseText);

            }

        });


    })

    //set status con confirmacion
    $('.btn-confirm-action').on('click', function () {
        var instance = this;
        var id = $(this).data('id');
        var action = $(this).data('action');
        var text = $(this).data('text');
        var winput = $(this).data('winput');
        var winputtext = $(this).data('winputtext');

        var validForm = $(this).data('validform');
        var validTableForm = $(this).data('validtableform');


        if (validForm) {
            if (!documentValidate()) {
                return false;
            }
        }
        if (validTableForm) { //validar aqui
            if (!tableValidate()) {
                manageShowAlertFormSuccess(false);
                manageShowAlertFormError(true);

                BtnReset(instance)
                //condicion aqui
                var mensaje = "<i class=\"material-icons\">warning</i> Las unidades y el costo de los productos deben ser mayor a cero";
                if ($('.table-data-add-sect2').length > 0) {


                    if($('.table-data-add-sect2').data('section')==2){
                        mensaje = "<i class=\"material-icons\">warning</i> Las unidades solicitadas no deben ser mayor a las recibidas." +
                            "<br><i class=\"material-icons\">warning</i> Las solicitadas deben ser mayor a cero";

                    }else{
                        mensaje = "<i class=\"material-icons\">warning</i> Las unidades solicitadas no deben ser mayor a las compradas." +
                            "<br><i class=\"material-icons\">warning</i> Las solicitadas deben ser mayor a cero";

                    }

                }

                Swal.fire('¡Alerta!', 'Favor validar los campos de la tabla', 'error')
                $('.new-alert-error').html('<div class="row">' +
                    '<div class="col-md-12">' +
                    '<button type="button" class="close pull-right close-alert-div" data-target="new-alert-error" data-add="alert-error-none">x</button>' +
                    '</div>' +
                    ' <div class="col-md-12">' +
                    '<h4>' + mensaje + '</h4>' +
                    '</div>' +
                    '</div>');
                return false;
            }
        }


        Swal.fire({
            title: (text) ? text : '¿Estas seguro de realizar esta acción?',
            showCancelButton: true,
            confirmButtonText: 'Si',
            allowOutsideClick: false,
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.value) {
                if (winput) {

                    Swal.fire({
                        title: (winputtext) ? winputtext : 'Agregar nota',
                        input: 'text',
                        inputAttributes: {
                            autocapitalize: 'off'
                        },
                        showCancelButton: true,
                        confirmButtonText: 'Enviar',
                        showLoaderOnConfirm: true,
                        backdrop: false,
                        preConfirm: (comment) => {
                            if (!comment) {
                                return Swal.showValidationMessage('Este campo es obligatorio')
                            }

                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                data: {a: action, id: id, comment_canceled: result.value},
                                url: "./?action=admin",
                                type: "POST",
                                dataType: "JSON",
                                success: function (data) {

                                    BtnReset(instance)
                                    if (data.success) {


                                        if (data.url) {
                                            let timerInterval
                                            Swal.fire({
                                                title: (data.title) ? data.title : 'Procensando',
                                                html: (data.subtitle) ? data.subtitle : '...',
                                                timer: 2000,
                                                timerProgressBar: true,
                                                allowOutsideClick: false,
                                                didOpen: () => {
                                                    Swal.showLoading()
                                                },
                                                willClose: () => {
                                                    clearInterval(timerInterval)
                                                }
                                            }).then((result) => {
                                                if (result.dismiss === Swal.DismissReason.timer) {
                                                    location.href = data.url;
                                                }
                                            })
                                        } else {

                                            if (data.reload) {
                                                let timerInterval
                                                Swal.fire({
                                                    title: 'Recargando',
                                                    timer: 1000,
                                                    timerProgressBar: true,
                                                    didOpen: () => {
                                                        Swal.showLoading()
                                                    },
                                                    willClose: () => {
                                                        clearInterval(timerInterval)
                                                    }
                                                }).then((result) => {
                                                    if (result.dismiss === Swal.DismissReason.timer) {
                                                        location.reload()
                                                    }
                                                })

                                            } else {
                                                manageShowAlertFormSuccess(true);
                                                manageShowAlertFormError(false);
                                                $('.new-alert-success').html('<div class="row col-md-12">' +
                                                    '<div class="col-md-12">' +
                                                    '<button type="button" class="close pull-right close-alert-div" data-target="new-alert-success" data-add="alert-success-none">x</button>' +
                                                    '</div>' +
                                                    ' <div class="col-md-12">' +
                                                    '<h4> <i class="material-icons">check</i> ' + data.mens + '</h4>' +
                                                    '</div>' +
                                                    ' <div class="col-md-12">' +

                                                    '</div>' +
                                                    '</div>');
                                            }


                                        }
                                    } else {

                                        manageShowAlertFormSuccess(false);
                                        manageShowAlertFormError(true);

                                        $('.new-alert-error').html('<div class="row">' +
                                            '<div class="col-md-12">' +
                                            '<button type="button" class="close pull-right close-alert-div" data-target="new-alert-error" data-add="alert-error-none">x</button>' +
                                            '</div>' +
                                            ' <div class="col-md-12">' +
                                            '<h4><i class=\"material-icons\">warning</i> ' + data.mens + ' </h4>' +
                                            '</div>' +
                                            '</div>');
                                    }

                                },
                                error: function (XMLHttpRequest, textStatus, errorThrown) {
                                    BtnReset(instance)
                                    console.log("Status: " + textStatus + " Error: " + XMLHttpRequest.responseText);

                                }

                            });
                        }


                    })

                } else {
                    $.ajax({

                        data: {a: action, id: id},
                        url: "./?action=admin",
                        type: "POST",
                        dataType: "JSON",
                        success: function (data) {
                            //console.log(data,data.url)
                            BtnReset(instance)
                            if (data.url) {
                                let timerInterval
                                Swal.fire({
                                    title: (data.title) ? data.title : 'Procensando...',
                                    html: (data.mens) ? data.mens : '',
                                    timer: 3000,
                                    timerProgressBar: true,
                                    didOpen: () => {
                                        Swal.showLoading()
                                    },
                                    willClose: () => {
                                        clearInterval(timerInterval)
                                    }
                                }).then((result) => {
                                    if (result.dismiss === Swal.DismissReason.timer) {
                                        //document.location.href = encodeURI(data.url);
                                        location.href = encodeURI(data.url)
                                    }
                                })
                            } else {

                                if (data.reload) {
                                    let timerInterval
                                    Swal.fire({
                                        title: 'Recargando',
                                        timer: 1000,
                                        timerProgressBar: true,
                                        didOpen: () => {
                                            Swal.showLoading()
                                        },
                                        willClose: () => {
                                            clearInterval(timerInterval)
                                        }
                                    }).then((result) => {
                                        if (result.dismiss === Swal.DismissReason.timer) {
                                            location.reload()
                                        }
                                    })

                                } else {


                                    manageShowAlertFormError(true);

                                    $("#ht-preloader").css("display", "none");

                                    Swal.fire('¡Alerta!', data.mens, 'error')
                                    $('.new-alert-error').html('<div class="row">' +
                                        '<div class="col-md-12">' +
                                        '<button type="button" class="close pull-right close-alert-div" data-target="new-alert-error" data-add="alert-error-none">x</button>' +
                                        '</div>' +
                                        ' <div class="col-md-12">' +
                                        '<h4><i class="material-icons">warning</i> ' + data.mens + '</h4>' +
                                        '</div>' +
                                        '</div>');

                                }


                            }


                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            BtnReset(instance)
                            console.log("Status: " + textStatus + " Error: " + XMLHttpRequest.responseText);

                        }

                    });

                }

            }
        })

    });

    //envio de formularios / envio de formularios con tabla
    $('.btn-send-form').on('click', function (e) {
        e.preventDefault()
        var instance = this;
        BtnLoading(instance);
        var formid = $(instance).data('form');
        var reset = $(instance).data('reset');

        var form = $("#" + formid);
        if (documentValidate()) {


            $.ajax({

                data: form.serialize(),
                url: "./?action=admin",
                type: "POST",
                dataType: "JSON",
                success: function (data) {
                    console.log(data)
                    BtnReset(instance)

                    if (data.success) {


                        if (reset) {

                            form[0].reset();
                            form[0].reset();


                        }
                        Swal.fire(
                            '¡Listo!',
                            data.mens,
                            'success'
                        )

                        manageShowAlertFormSuccess(true);
                        manageShowAlertFormError(false);

                        if (data.url) {
                            $('.new-alert-success').html('<div class="row col-md-12">' +
                                '<div class="col-md-12">' +
                                '<button type="button" class="close pull-right close-alert-div" data-target="new-alert-success" data-add="alert-success-none">x</button>' +
                                '</div>' +
                                ' <div class="col-md-12">' +
                                '<h4><i class="material-icons">check</i> ' + data.mens + '</h4>' +
                                '</div>' +
                                ' <div class="col-md-12">' +
                                '<a href="' + data.url + '">' +
                                '<i class="material-icons">arrow_forward</i> Ir a ' + data.post_name + ' #' + data.id + ' </a>' +
                                '</div>' +
                                '</div>');
                        } else {
                            $('.new-alert-success').html('<div class="row col-md-12">' +
                                '<div class="col-md-12">' +
                                '<button type="button" class="close pull-right close-alert-div" data-target="new-alert-success" data-add="alert-success-none">x</button>' +
                                '</div>' +
                                ' <div class="col-md-12">' +
                                '<h4> <i class="material-icons">check</i> ' + data.mens + '</h4>' +
                                '</div>' +
                                ' <div class="col-md-12">' +

                                '</div>' +
                                '</div>');

                        }


                    } else {

                        //hacer lo mismo aqui
                        manageShowAlertFormSuccess(false);
                        manageShowAlertFormError(true);

                        $("#ht-preloader").css("display", "none");

                        Swal.fire('¡Alerta!', data.mens, 'error')
                        $('.new-alert-error').html('<div class="row">' +
                            '<div class="col-md-12">' +
                            '<button type="button" class="close pull-right close-alert-div" data-target="new-alert-error" data-add="alert-error-none">x</button>' +
                            '</div>' +
                            ' <div class="col-md-12">' +
                            '<h4><i class="material-icons">warning</i> ' + data.mens + '</h4>' +
                            '</div>' +
                            '</div>');

                    }

                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    BtnReset(instance)
                    manageShowAlertFormSuccess(false);
                    console.log("Status: " + textStatus + " Error: " + XMLHttpRequest.responseText);

                }

            });


        } else {
            Swal.fire(
                'Alerta!',
                'Favor validar los campos obligatorios.',
                'error'
            )
            BtnReset(instance)
        }

    });
    $('.btn-send-form-file').on('click', function (e) {
        e.preventDefault()
        var instance = this;
        BtnLoading(instance);
        var formid = $(instance).data('form');
        var reset = $(instance).data('reset');

        if (documentValidate()) {

            let formdata = new FormData(document.getElementById(formid));
            formdata.append('file', $('#file')[0]);
            $.ajax({

                data: formdata,
                url: "./?action=admin",
                type: "POST",
                contentType: false,
                processData: false,
                cache: false,
                dataType: "JSON",
                success: function (data) {

                    BtnReset(instance)
                    if (data.success) {
                        if (reset) {

                            $(formid)[0].reset();
                            if ($('.select2')) {
                                $(".select2").val('').trigger("change");
                            }

                        }


                        Swal.fire(
                            '¡Listo!',
                            data.mens,
                            'success'
                        )

                        manageShowAlertFormSuccess(true);
                        manageShowAlertFormError(false);

                        if (data.url) {
                            $('.new-alert-success').html('<div class="row col-md-12">' +
                                '<div class="col-md-12">' +
                                '<button type="button" class="close pull-right close-alert-div" data-target="new-alert-success" data-add="alert-success-none">x</button>' +
                                '</div>' +
                                ' <div class="col-md-12">' +
                                '<h4><i class="material-icons">check</i> ' + data.mens + '</h4>' +
                                '</div>' +
                                ' <div class="col-md-12">' +
                                '<a href="' + data.url + '">' +
                                '<i class="material-icons">arrow_forward</i> Ir a ' + data.post_name + ' #' + data.id + ' </a>' +
                                '</div>' +
                                '</div>');
                        } else {
                            $('.new-alert-success').html('<div class="row col-md-12">' +
                                '<div class="col-md-12">' +
                                '<button type="button" class="close pull-right close-alert-div" data-target="new-alert-success" data-add="alert-success-none">x</button>' +
                                '</div>' +
                                ' <div class="col-md-12">' +
                                '<h4><i class="material-icons">check</i> ' + data.mens + '</h4>' +
                                '</div>' +
                                ' <div class="col-md-12">' +

                                '</div>' +
                                '</div>');

                        }


                    } else {


                        //manageShowAlertFormSuccess(false);
                        manageShowAlertFormError(true);

                        $("#ht-preloader").css("display", "none");

                        Swal.fire('¡Alerta!', data.mens, 'error')
                        $('.new-alert-error').html('<div class="row">' +
                            '<div class="col-md-12">' +
                            '<button type="button" class="close pull-right close-alert-div" data-target="new-alert-error" data-add="alert-error-none">x</button>' +
                            '</div>' +
                            ' <div class="col-md-12">' +
                            '<h4><i class="material-icons">warning</i> ' + data.mens + '</h4>' +
                            '</div>' +
                            '</div>');

                    }

                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    BtnReset(instance)
                    manageShowAlertFormSuccess(false);
                    manageShowAlertFormError(false);
                    console.log("Status: " + textStatus + " Error: " + XMLHttpRequest.responseText);

                }

            });


        } else {
            Swal.fire(
                'Alerta!',
                'Favor validar los campos obligatorios.',
                'error'
            )
            BtnReset(instance)
        }

    });

    $('.btn-send-form-table').on('click', function (e) {
        e.preventDefault()
        var instance = this;
        BtnLoading(instance);
        var formid = $(instance).data('form');
        var reset = $(instance).data('reset');

        if (documentValidate()) {

            if (tableValidate()) {
                var TableData = [];
                jQuery(data_table).each(function (index, value) {
                    TableData.push(value.data)
                });
                var formData = new FormData(document.getElementById(formid));
                formData.append("data_table", JSON.stringify(TableData))


                $.ajax({

                    data: formData,
                    url: "./?action=admin",
                    type: "POST",
                    contentType: false,
                    processData: false,
                    cache: false,
                    dataType: "JSON",
                    success: function (data) {
                        console.log(data)
                        BtnReset(instance)
                        if (data.success) {


                            if (reset) {
                                $("#" + formid)[0].reset();
                                $('.table-data-add').html('')
                                $('#total-table').html('0.00')
                                if ($('.change-and-consult').length > 0) {
                                    $('.change-and-consult').val('').change()
                                }


                            }
                            Swal.fire(
                                '¡Listo!',
                                data.mens,
                                'success'
                            )

                            manageShowAlertFormSuccess(true);
                            manageShowAlertFormError(false);

                            if (data.url) {
                                $('.new-alert-success').html('<div class="row col-md-12">' +
                                    '<div class="col-md-12">' +
                                    '<button type="button" class="close pull-right close-alert-div" data-target="new-alert-success" data-add="alert-success-none">x</button>' +
                                    '</div>' +
                                    ' <div class="col-md-12">' +
                                    '<h4><i class="material-icons">check</i> ' + data.mens + '</h4>' +
                                    '</div>' +
                                    ' <div class="col-md-12">' +
                                    '<a href="' + data.url + '">' +
                                    '<i class="material-icons">arrow_forward</i> Ir a ' + data.post_name + ' #' + data.id + ' </a>' +
                                    '</div>' +
                                    '</div>');
                            } else {
                                $('.new-alert-success').html('<div class="row col-md-12">' +
                                    '<div class="col-md-12">' +
                                    '<button type="button" class="close pull-right close-alert-div" data-target="new-alert-success" data-add="alert-success-none">x</button>' +
                                    '</div>' +
                                    ' <div class="col-md-12">' +
                                    '<h4>' + data.mens + '</h4>' +
                                    '</div>' +
                                    ' <div class="col-md-12">' +

                                    '</div>' +
                                    '</div>');

                            }


                        } else {
                            manageShowAlertFormSuccess(false);
                            manageShowAlertFormError(true);

                            $("#ht-preloader").css("display", "none");

                            Swal.fire('¡Alerta!', data.mens, 'error')
                            $('.new-alert-error').html('<div class="row">' +
                                '<div class="col-md-12">' +
                                '<button type="button" class="close pull-right close-alert-div" data-target="new-alert-error" data-add="alert-error-none">x</button>' +
                                '</div>' +
                                ' <div class="col-md-12">' +
                                '<h4><i class="material-icons">warning</i> ' + data.mens + '</h4>' +
                                '</div>' +
                                '</div>');

                        }

                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                       // console.log(XMLHttpRequest, textStatus, errorThrown)
                        BtnReset(instance)
                        manageShowAlertFormSuccess(false);
                        console.log("Status: " + textStatus + " Error: " + XMLHttpRequest.responseText);

                    }

                });


            } else {


                var mensaje = "<i class=\"material-icons\">warning</i> Las unidades y el costo de los productos deben ser mayor a cero";
                if ($('.table-data-add-sect2').length > 0) {
                    if($('.table-data-add-sect2').data('section')==2){
                        mensaje = "<i class=\"material-icons\">warning</i> Las unidades solicitadas no deben ser mayor a las recibidas." +
                            "<br><i class=\"material-icons\">warning</i> Las solicitadas deben ser mayor a cero";
                    }else{
                        mensaje = "<i class=\"material-icons\">warning</i> Las unidades solicitadas no deben ser mayor a las compradas." +
                            "<br><i class=\"material-icons\">warning</i> Las solicitadas deben ser mayor a cero";
                    }


                }

                manageShowAlertFormSuccess(false);
                manageShowAlertFormError(true);

                BtnReset(instance)
                Swal.fire('¡Alerta!', 'Favor validar los campos de la tabla', 'error')
                $('.new-alert-error').html('<div class="row">' +
                    '<div class="col-md-12">' +
                    '<button type="button" class="close pull-right close-alert-div" data-target="new-alert-error" data-add="alert-error-none">x</button>' +
                    '</div>' +
                    ' <div class="col-md-12">' +
                    '<h4>' + mensaje + ' </h4>' +
                    '</div>' +
                    '</div>');
            }


        } else {

            Swal.fire(
                'Alerta!',
                'Favor validar los campos obligatorios.',
                'error'
            )
            BtnReset(instance)

        }

    });

    $('.btn-delete-form').on('click', function () {
        var instance = this;
        var id = $(this).data('id');
        var action = $(this).data('action');
        var text = $(this).data('text');

        Swal.fire({
            title: (text) ? text : '¿Estas seguro de eliminar este elemento?',

            showCancelButton: true,
            confirmButtonText: 'Si, Eliminar',
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.value) {

                $.ajax({

                    data: {a: action, id: id},
                    url: "./?action=admin",
                    type: "POST",
                    dataType: "JSON",
                    success: function (data) {
                        console.log(data)
                        BtnReset(instance)

                        if (data.success) {
                            if (data.url) {
                                let timerInterval
                                Swal.fire({
                                    title: 'Procensando',
                                    html: 'Eliminando registro',
                                    timer: 2000,
                                    timerProgressBar: true,
                                    didOpen: () => {
                                        Swal.showLoading()
                                    },
                                    willClose: () => {
                                        clearInterval(timerInterval)
                                    }
                                }).then((result) => {
                                    if (result.dismiss === Swal.DismissReason.timer) {
                                        location.href = data.url;
                                    }
                                })
                            } else {

                                if (data.reload) {
                                    let timerInterval
                                    Swal.fire({
                                        title: (data.title) ? data.title : 'Recargando',
                                        html: (data.mens) ? data.mens : '...',
                                        timer: 2000,
                                        timerProgressBar: true,
                                        allowOutsideClick: false,
                                        didOpen: () => {
                                            Swal.showLoading()
                                        },
                                        willClose: () => {
                                            clearInterval(timerInterval)
                                        }
                                    }).then((result) => {
                                        if (result.dismiss === Swal.DismissReason.timer) {
                                            location.reload()
                                        }
                                    })

                                } else {
                                    manageShowAlertFormSuccess(true);
                                    manageShowAlertFormError(false);
                                    $('.new-alert-success').html('<div class="row col-md-12">' +
                                        '<div class="col-md-12">' +
                                        '<button type="button" class="close pull-right close-alert-div" data-target="new-alert-success" data-add="alert-success-none">x</button>' +
                                        '</div>' +
                                        ' <div class="col-md-12">' +
                                        '<h4> <i class="material-icons">check</i> ' + data.mens + '</h4>' +
                                        '</div>' +
                                        ' <div class="col-md-12">' +

                                        '</div>' +
                                        '</div>');
                                }


                            }
                        } else {
                            manageShowAlertFormSuccess(false);
                            manageShowAlertFormError(true);

                            $('.new-alert-error').html('<div class="row">' +
                                '<div class="col-md-12">' +
                                '<button type="button" class="close pull-right close-alert-div" data-target="new-alert-error" data-add="alert-error-none">x</button>' +
                                '</div>' +
                                ' <div class="col-md-12">' +
                                '<h4><i class=\"material-icons\">warning</i> ' + data.mens + ' </h4>' +
                                '</div>' +
                                '</div>');

                        }


                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        BtnReset(instance)
                        console.log("Status: " + textStatus + " Error: " + XMLHttpRequest.responseText);

                    }

                });

                /**/

            }
        })

    });

    $('#alert-form').on('click', '.close-alert-div', function () {
        var target = $(this).data('target')
        var add = $(this).data('add')
        $('.' + target).addClass(add)
    });

    //openModal products
    $('.btn-product-table-line').on('click', function () {

        var current_id = $('input[name="product_id[]"]').map(function () {
            return this.value; // $(this).val()
        }).get();
        $('#modalProduct').modal('show')
       
        $.ajax({

            data: {a: 'GET-PRODUCTS'},
            url: "./?action=admin",
            type: "POST",
            dataType: "JSON",
            success: function (data) {

                if (data.length > 0) {

                    if ($('#table')) {
                        var table = $('#table').DataTable();
                        table.clear().draw();

                        $.each(data, function (index, value) {
                            //console.log(value.id)
                            //console.log(current_id)
                            if (!(jQuery.inArray(value.id, current_id) !== -1)) {
                                console.log('entro')
                                table.row.add([
                                    '<input class="checked-table" type="checkbox" value="' + value.id + '" >',
                                    value.id,
                                    value.name,
                                    value.category,
                                    value.price
                                ]).draw(false);

                            }else{
                                console.log('no entro')
                            }


                        });
                    }


                }


            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {

                console.log("Status: " + textStatus + " Error: " + XMLHttpRequest.responseText);

            }

        });
    });
    $('.btn-add-product-table').on('click', function (e) {
        //checked-table
        $('#modalProduct').modal('hide')

        if ($('#table')) {
            var ids = [];
            var table = $('#table').DataTable();
            table.$('td > input:checkbox').each(function () {
                if (this.checked) {
                    ids.push($(this).val());
                }
            });

            if (ids.length > 0) {
                $.ajax({

                    data: {a: 'GET-PRODUCTS', ids: ids},
                    url: "./?action=admin",
                    type: "POST",
                    dataType: "JSON",
                    success: function (data) {
                       if (data.length > 0) {
                            $.each(data, function (index, value) {
                                var dkey = value.id;
                                var newdata = {
                                    dkey: dkey,
                                    data: {unit: 0, costs: value.price, total: 0, product_id: dkey}
                                };

                                data_table.push(newdata);


                                var html = '<tr id="line-' + value.id + '">';

                                html += '<td><button type="button" class="btn btn-danger remove-line  pull-center btn-sm" ' +
                                    'data-id="' + value.id + '" > <i class="material-icons">close</i></button></td>';


                                html += '<td><label for="form-control-label">' + value.id + '</label><input type="hidden" ' +
                                    'name="product_id[]" class="form-control" value="' + value.id + '"></td>';
                                html += '<td><label for="form-control-label">' + value.name + '</label></td>';

                                html += '<td><label for="form-control-label">' + value.unidad_para_compra + '</label></td>';

                                html += '<td><input type="number" name="units[]" class="form-control units-line" value="0"' +
                                    'min="1" data-id="' + value.id + '" id="unit-' + value.id + '"></td>';

                                html += '<td><input type="number" class="form-control price-line" min="1"' +
                                    'step="0.2" value="' + value.price + '" name="costs[]" data-id="' + value.id + '" id="costs-' + value.id + '"/></td>';

                                html += '<td><label for="form-control-label " id="total-' + value.id + '">0.00</label></td>';


                                html += '</tr>';

                                if ($('.table-data-add').length > 0) {
                                    $('.table-data-add').append(html);
                                }
                                if ($('.table-data-edit').length > 0) {
                                    $('.table-data-edit').append(html);
                                }


                            });

                        }

                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        console.log("Status: " + textStatus + " Error: " + XMLHttpRequest.responseText);

                    }

                });

            }
        }


    })

    //en el crear
    $('.table-data-add').on('click', '.remove-line', function () {
        var instance = this;
        $('#line-' + $(instance).data('id')).remove()
        jQuery(data_table).each(function (index, value) {

            if (value.dkey == $(instance).data('id')) {
                data_table.splice(index, 1); // This will remove the object that first name equals to Test1

                return false; // This will stop the execution of jQuery each loop.
            }
        });
        calculateTotal();
    });
    $('.table-data-add').on('keyup mouseup', '.units-line', function () {

        var id = $(this).data('id')
        var units = Number($(this).val());
        var costs = Number(0);
        var total = Number(0);

        //current data line
        jQuery(data_table).each(function (index, value) {

            if (value.dkey == id) {
                costs = Number(value.data.costs)
                return false;
            }

        });

        total += Number(units * costs);

        //update data
        jQuery(data_table).each(function (index, value) {

            if (value.dkey == id) {
                value.data.unit = units
                value.data.costs = costs
                value.data.total = total
                return false;
            }

        });
        $('#total-' + id).html(total.toFixed(2))


        calculateTotal()

    });
    $('.table-data-add').on('keyup mouseup', '.price-line', function () {

        var id = $(this).data('id')
        var costs = Number($(this).val());
        var units = Number(0);
        var total = Number(0);

        //current data line
        jQuery(data_table).each(function (index, value) {

            if (value.dkey == id) {
                units = Number(value.data.unit)
                return false;
            }

        });

        total += Number(units * costs);

        //update data
        jQuery(data_table).each(function (index, value) {

            if (value.dkey == id) {
                value.data.unit = units
                value.data.costs = costs
                value.data.total = total
                return false;
            }

        });
        $('#total-' + id).html(total.toFixed(2))
        calculateTotal()

    });

    //valida pedidos
    $('.table-data-add-sect2').on('keyup mouseup', '.units-line-request', function () {
        var instance = this;
        var id = $(instance).data('id')
        var units_request = Number($(instance).val());
        var units_diff = Number(0);
        var unit_purchase = Number(0);


        //current data line
        jQuery(data_table).each(function (index, value) {

            if (value.dkey == id) {
                unit_purchase = value.data.unit;
                units_diff = (Number(value.data.unit) - units_request);
                return false;
            }

        });

        if (units_request > unit_purchase) {
            $("#error-" + $(instance).data('id')).css("display", "block");
            $(instance).val(0)
            return false;
        } else {
            $("#error-" + $(instance).data('id')).css("display", "none");
        }

        //update data
        jQuery(data_table).each(function (index, value) {

            if (value.dkey == id) {
                value.data.units_request = units_request
                value.data.units_diff = units_diff
                return false;
            }

        });

        $('#total-diff' + id).html((units_request > 0) ? units_diff : 0)

        calculateTotalSect2();

    });


    //en el editar
    $('.table-data-edit').on('click', '.remove-line', function () {
        var instance = this;
        $('#line-' + $(instance).data('id')).remove()
        jQuery(data_table).each(function (index, value) {

            if (value.dkey == $(instance).data('id')) {
                data_table.splice(index, 1); // This will remove the object that first name equals to Test1

                return false; // This will stop the execution of jQuery each loop.
            }
        });
        calculateTotal();
    });
    $('.table-data-edit').on('keyup mouseup', '.units-line', function () {

        var id = $(this).data('id')
        var units = Number($(this).val());
        var costs = Number(0);
        var total = Number(0);

        //current data line
        jQuery(data_table).each(function (index, value) {

            if (value.dkey == id) {
                costs = Number(value.data.costs)
                return false;
            }

        });

        total += Number(units * costs);

        //update data
        jQuery(data_table).each(function (index, value) {

            if (value.dkey == id) {
                value.data.unit = units
                value.data.costs = costs
                value.data.total = total
                return false;
            }

        });
        $('#total-' + id).html(total.toFixed(2))


        calculateTotal()

    });
    $('.table-data-edit').on('keyup mouseup', '.price-line', function () {

        var id = $(this).data('id')
        var costs = Number($(this).val());
        var units = Number(0);
        var total = Number(0);

        //current data line
        jQuery(data_table).each(function (index, value) {

            if (value.dkey == id) {
                units = Number(value.data.unit)
                return false;
            }

        });

        total += Number(units * costs);

        //update data
        jQuery(data_table).each(function (index, value) {

            if (value.dkey == id) {
                value.data.unit = units
                value.data.costs = costs
                value.data.total = total
                return false;
            }

        });
        $('#total-' + id).html(total.toFixed(2))
        calculateTotal()

    });

    //modal global with datable
    $('.show-data-modal').on('click', function () {
        var instance = this;
        var id = $(instance).data('id');
        var action = $(instance).data('action');
        var columns = $(instance).data('columns').split(',');
        var applylink = $(instance).data('applylink');
        var title = $(instance).data('title');

        $('#modalGlobal').modal('show')
        $('#modalGlobalLabel').html(title)

        if ($('#table')) {

            //titles
            $('#header-table').html('');
            $.each(columns, function (index, value) {
                $('#header-table').append("<th>" + value + "</th>")
            });

            var table = $('#table-global').DataTable();
            table.clear();

            $.ajax({

                data: {a: action, id: id},
                url: "./?action=admin",
                type: "POST",
                dataType: "JSON",
                success: function (data) {
                    console.log(data)
                    if (data.length > 0) {

                        $.each(data, function (index, valuereceived) {
                            var datos = [];
                            $.each(columns, function (index, value) {
                                datos.push(valuereceived[value])
                            })
                            table.row.add(datos).draw(false);

                        });


                    }


                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    console.log("Status: " + textStatus + " Error: " + XMLHttpRequest.responseText);

                }

            });

        }


    });

    function documentValidate() {

        var check = true;
        $.each($('.validate'), function (index, value) {
            console.log(this.id,$(value).val())
            if ($(value).val() == '') {
                $("." + this.id + '-error').html("¡Este campo es obligatorio!");
                check = false;
            } else {
                $("." + this.id + '-error').html("");
            }
            if ($(value).attr("name") == 'email') {

                if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3,4})+$/.test($(value).val()))) {
                    $("." + this.id + '-error').html("¡Ingrese un email valido.");
                    check = false;
                } else {

                }
            }
            if ($(value).attr("name") == 'new_password') {

                if ($('.password_confirm').length > 0) {

                    if ($(value).val() != $('.password_confirm').val()) {
                        $(".password_confirm-error").html("¡Las contraseñas no coinciden!");
                        check = false;
                    }

                }
            }

        });
        return check;
    }

    function tableValidate() {
        var check = true;

        if (data_table.length > 0) {

            if ($('.table-data-add-sect2').length > 0) {
                jQuery(data_table).each(function (index, value) {

                    if (Number(value.data.units_request) > Number(value.data.unit)) {
                        check = false;
                    }
                    if (Number(value.data.units_request) <= 0) {
                        if (Number(value.data.unit) > 0) {
                            check = false;
                        }

                    }
                    if (Number(value.data.units_diff) < 0) {
                        check = false;
                    }

                });

            } else {

                jQuery(data_table).each(function (index, value) {
                    if (Number(value.data.unit) <= 0 || Number(value.data.costs) <= 0 || Number(value.data.total) <= 0) {
                        check = false;
                    }

                });
            }

        } else {

            check = false;
        }


        return check;

    }

    function BtnLoading(elem) {
        $(elem).attr("data-original-text", $(elem).html());
        $(elem).prop("disabled", true);
        $(elem).html('Enviado...');
    }

    function BtnReset(elem) {
        $(elem).prop("disabled", false);
        $(elem).html($(elem).attr("data-original-text"));
    }

    function manageShowAlertFormSuccess(active = false) {

        if (active) {

            $('.new-alert-success').removeClass('alert-success-none');
            $('.new-alert-success').addClass('d-flex col-md-8 alert pt-3');
            $('.new-alert-error').addClass('alert-error-none');

        } else {

            $('.new-alert-success').removeClass('d-flex col-md-8 alert pt-3');
            $('.new-alert-success').addClass('alert-success-none');
        }
    }

    function manageShowAlertFormError(active = false) {
        if (active) {

            $('.new-alert-error').removeClass('alert-error-none');
            $('.new-alert-error').addClass('d-flex col-md-8 pt-3');
            $('.new-alert-success').addClass('alert-success-none');

        } else {

            $('.new-alert-error').removeClass('d-flex col-md-8 pt-3');
            $('.new-alert-error').addClass('alert-success-none');

        }
    }

    function calculateTotal() {
        var units = 0;
        var costs = 0;
        var total = 0;
        jQuery(data_table).each(function (index, value) {

            units += Number(value.data.unit)
            costs += Number(value.data.costs)
            total += Number(value.data.total)

        });

        if ($('#total-table')) {
            $('#total-table').html("$ " + Number(total).toFixed(2));
        }


    }

    function calculateTotalSect2() {
        var units = 0;
        var units_request = 0;
        var units_diff = 0;
        jQuery(data_table).each(function (index, value) {

            units += Number(value.data.unit)
            units_request += Number(value.data.units_request)
            units_diff += Number(value.data.units_diff)

        });

        if ($('#unit-buy').length > 0) {
            $('#unit-buy').html(units);
        }
        if ($('#unit-request').length > 0) {
            $('#unit-request').html(units_request);
        }
        if ($('#unit-diff').length > 0) {
            $('#unit-diff').html(units_diff);
        }


    }

    function getDataToTable(action, id, targetAdd = ".table-data-add") {
        console.log(action, id, targetAdd)

        if (action && id && targetAdd) {
            $.ajax({

                data: {a: action, id: id},
                url: "./?action=admin",
                type: "POST",
                dataType: "JSON",
                success: function (data) {
                    //console.log(data)
                    if (data.length > 0) {
                        var disabled = "";
                        var readonly = "";
                        var disableClass = "";
                        if ($('.disable-button').length > 0) {
                            disabled = "disabled ='disabled'";
                            readonly = "readonly ='readonly'";
                            disableClass = "disable-button";
                        }
                        $.each(data, function (index, value) {
                            var dkey = value.id;
                            var newdata = {
                                dkey: dkey,
                                data: {unit: value.unit, costs: value.costs, total: value.total, product_id: dkey}
                            };
                            data_table.push(newdata);


                            var html = '<tr id="line-' + value.id + '">';

                            html += '<td><button type="button" class="btn btn-danger pull-center remove-line btn-sm ' + disableClass + ' " ' +
                                'data-id="' + value.id + '" ' + disabled + '> <i class="material-icons">close</i></button></td>';


                            html += '<td><label for="form-control-label">' + value.id + '</label><input type="hidden" ' +
                                'name="product_id[]" class="form-control" value="' + value.id + '" ></td>';
                            html += '<td><label for="form-control-label">' + value.name + '</label></td>';

                            html += '<td><label for="form-control-label">' + value.unidad_para_compra + '</label></td>';

                            html += '<td><input type="number" name="units[]" class="form-control units-line" value="' + value.unit + '"' +
                                'min="1" data-id="' + value.id + '" id="unit-' + value.id + '" ' + readonly + '></td>';

                            html += '<td><input type="number" class="form-control price-line" min="1"' +
                                'step="0.2" value="' + value.costs + '" name="costs[]" data-id="' + value.id + '" id="costs-' + value.id + '" ' + readonly + '/></td>';

                            html += '<td><label for="form-control-label " id="total-' + value.id + '">' + value.total + '</label></td>';


                            html += '</tr>';
                            $(targetAdd).append(html);

                        });
                        calculateTotal();
                    }

                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    console.log("Status: " + textStatus + " Error: " + XMLHttpRequest.responseText);

                }

            });
        }

    }

    function getDataToTableSect2(action, id, targetAdd = ".table-data-add-sect2") {
        console.log(action)
        if (action && id && targetAdd) {
            $.ajax({

                data: {a: action, id: id},
                url: "./?action=admin",
                type: "POST",
                dataType: "JSON",
                success: function (data) {
                    console.log(data)
                    if (data.length > 0) {
                        var disabled = "";
                        var readonly = "";
                        var disableClass = "";

                        if ($('.disable-button').length > 0) {
                            disabled = "disabled ='disabled'";
                            disableClass = "disable-button";
                            readonly = "readonly ='readonly'";
                        }
                        $.each(data, function (index, value) {
                            var dkey = value.id;
                            var newdata = {
                                dkey: dkey,
                                data: {
                                    unit: value.unit,
                                    units_request: value.units_request,
                                    units_diff: value.units_diff,
                                    product_id: dkey
                                }
                            };

                            data_table.push(newdata);


                            var html = '<tr id="line-' + value.id + '">';

                            html += '<td><label for="form-control-label">' + value.id + '</label><input type="hidden" ' +
                                'name="product_id[]" class="form-control" value="' + value.id + '" ></td>';

                            html += '<td><label for="form-control-label">' + value.name + '</label></td>';

                            html += '<td><label for="form-control-label">' + value.unidad_para_compra + '</label></td>';

                            html += '<td><input type="number" name="units[]" class="form-control units-line" value="' + value.unit + '"' +
                                'min="1" data-id="' + value.id + '" id="unit-' + value.id + '" readonly ="readonly"></td>';

                            html += '<td><input type="number" name="units_request[]" class="form-control units-line-request" value="' + value.units_request + '"' +
                                'min="0" max="' + value.unit + '" data-id="' + value.id + '" id="unit-request-' + value.id + '" ' + readonly + '>' +
                                '<small class="form-text text-muted error" style="color:red !important;display: none" id="error-' + value.id + '">Limite unit. O/C (' + value.unit + ')</small>' +
                                '</td>';

                            html += '<td><label for="form-control-label " id="total-diff' + value.id + '">' + value.units_diff + '</label></td>';


                            html += '</tr>';
                            $(targetAdd).append(html);

                        });
                        calculateTotalSect2();
                    }

                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    console.log("Status: " + textStatus + " Error: " + XMLHttpRequest.responseText);

                }

            });

        }
    }


});
