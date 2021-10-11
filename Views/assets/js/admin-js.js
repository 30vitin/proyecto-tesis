"use strict";


$(document).ready(function () {
//send form
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

                    BtnReset(instance)

                    if (data.success) {


                        if (reset) {

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
                                '<h4>' + data.mens + '</h4>' +
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
                            '<h4>' + data.mens + '</h4>' +
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
                    console.log(data)
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
                                '<h4>' + data.mens + '</h4>' +
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


                        //manageShowAlertFormSuccess(false);
                        manageShowAlertFormError(true);

                        $("#ht-preloader").css("display", "none");

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

                        BtnReset(instance)
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
    $('.remove-image').on('click', function (event) {

        $("#ht-preloader").css("display", "block");
        var id = this.id;
        event.preventDefault();


        var parametros = {
            "a": "DELETE-IMAGE-PRODUCT-SINGLE",
            "id": id
        };

        $.ajax({

            data: parametros,
            url: "./?action=admin",
            type: "POST",
            dataType: "JSON",
            success: function (data) {
                console.log(data)
                if (data.type == 'success') {
                    location.reload();

                } else {
                    $("#ht-preloader").css("display", "none");
                    Swal.fire(data.mens)

                }
                //Swal.fire(data.mens)

            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log("Status: " + textStatus + " Error: " + XMLHttpRequest.responseText);

            }

        });

    })


    $('.prev-page').on('click', function (event) {
        var page = Number($("#page").val()) - 1;
        $("#page").val(page);
        $("#form-filter").submit();
    });

    $('.next-page').on('click', function (event) {
        var page = Number($("#page").val()) + 1;

        $("#page").val(page);

        $("#form-filter").submit();
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
                        table.clear();

                        $.each(data, function (index, value) {
                            if ((jQuery.inArray(value.id, current_id))) {
                                table.row.add([
                                    '<input class="checked-table" type="checkbox" value="' + value.id + '" >',
                                    value.id,
                                    value.name,
                                    value.category,
                                    value.price
                                ]).draw(false);

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

    $('.btn-add-product-table').on('click', function () {

        //checked-table
        $('#modalProduct').modal('hide')

        var ids = [];
        if ($('#table')) {

        }
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

                            var html = '<tr id="line-'+value.id+'">';

                            html+='<td><button type="button" class="btn btn-danger pull-center remove-line btn-sm" ' +
                                'data-id="'+value.id+'"> <i class="material-icons">close</i></button></td>';

                            html+='<td><label for="form-control-label">'+value.name+'</label><input type="hidden" ' +
                                'name="product_id[]" class="form-control" value="'+value.id+'"></td>';

                            html+='<td><label for="form-control-label">'+value.unidad_para_compra+'</label></td>';

                            html+='<td><input type="number" name="units[]" class="form-control units-line" value="0"></td>';

                            html+='<td><input type="number" class="form-control price-line" min="0" max="4"\n' +
                                'step="0.2" value="'+value.price+'" name="costs" /></td>';

                            html+='<td><input type="number" class="form-control total-line" min="0" max="4"\n' +
                                'step="0.2" value="0.00" name="total" /></td>';

                            html += '</tr>';
                            $('.table-data-add').append(html);

                        });

                    }

                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    console.log("Status: " + textStatus + " Error: " + XMLHttpRequest.responseText);

                }

            });

        }

    })

    $('.table-data-add').on('click','.remove-line',function(){
        $('#line-'+$(this).data('id')).remove()
    });

    $('#input-filtres').on('keyup', function () {

        var datainput = $(this).val()
        if (datainput.length > 3) {


        }

    });

    function documentValidate() {

        var check = true;
        $.each($('.validate'), function (index, value) {

            //console.log($(value).val()+' '+this.id);


            if ($(value).val() == '') {
                $("." + this.id + '-error').html("This field is required!");
                check = false;
            } else {
                $("." + this.id + '-error').html("");
            }
            if ($(value).attr("name") == 'email') {

                if (!validarEmail($(value).val())) {
                    $("." + this.id + '-error').html("Insert a valid email!");
                    check = false;
                } else {

                }
            }

        });
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
});
