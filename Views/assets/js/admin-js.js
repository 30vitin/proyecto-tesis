"use strict";


$(document).ready(function () {
//send form
    $('.btn-send-form').on('click', function () {

        var instance = this;
        BtnLoading(instance);
        var formid = $(instance).data('form');

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
                        form[0].reset();
                        Swal.fire(
                            '¡Listo!',
                            data.mens,
                            'success'
                        )

                        $('.new-alert-success').removeClass('alert-success-none');
                        $('.new-alert-error').addClass('alert-error-none');

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
                        $("#ht-preloader").css("display", "none");
                        $('.new-alert-error').removeClass('alert-error-none');
                        $('.new-alert-success').addClass('alert-success-none');

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

                    data: {a:action,id:id},
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

    $('#input-filtres').on('keyup',function(){

        var datainput =  $(this).val()
        if(datainput.length>3){


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

});
