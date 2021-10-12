"use strict";


$(document).ready(function () {
//send form

    var data_table = [];
    if($('.table-data-edit').length>0){
        getDataToTable($('.table-data-edit').data('id'))
    }
    $('.change-and-consult').on('change',function(){
        var instance = this;
        var request_id = $(instance).val();
        data_table = [];

        $.ajax({

            data: {a:$(instance).data("action"),id:$(instance).val()},
            url: "./?action=admin",
            type: "POST",
            dataType: "JSON",
            success: function (data) {
                if($("#"+$(instance).data("form")).length>0){
                    $("#"+$(instance).data("form"))[0].reset();
                }
                if($('.table-data-add').length>0){
                    $('.table-data-add').html('')
                }
                if($('#total-table').length>0){
                    $('#total-table').html('0.00')

                }
                if($('.table-data-add').length>0){
                    getDataToTable(request_id)
                }
                if(data.data.length>0){

                    jQuery(data.data).each(function (index, value) {

                        if(value.type =='input'){
                            if($('#'+value.id).length>0){
                                $('#'+value.id).val(value.value)

                            }
                        }
                        if(value.type =='select'){
                            if($('#'+value.id).length>0){
                               $('#'+value.id).val(value.value).change();
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


    });

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
                formData.append("data_table",JSON.stringify(TableData))


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
                                 $("#"+formid)[0].reset();
                                 $('.table-data-add').html('')
                                 $('#total-table').html('0.00')

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
                        BtnReset(instance)
                        manageShowAlertFormSuccess(false);
                        console.log("Status: " + textStatus + " Error: " + XMLHttpRequest.responseText);

                    }

                });



            } else {
                manageShowAlertFormSuccess(false);
                manageShowAlertFormError(true);

                BtnReset(instance)
                Swal.fire('¡Alerta!', 'Favor validar los campos de la tabla', 'error')
                $('.new-alert-error').html('<div class="row">' +
                    '<div class="col-md-12">' +
                    '<button type="button" class="close pull-right close-alert-div" data-target="new-alert-error" data-add="alert-error-none">x</button>' +
                    '</div>' +
                    ' <div class="col-md-12">' +
                    '<h4><i class="material-icons">warning</i> Las unidades y el costo de los productos deben ser mayor a cero</h4>' +
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
                        BtnReset(instance)
                        if(data.url){
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
                        }else{

                            if(data.reload){
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

                            }else{
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
                        table.clear();

                        $.each(data, function (index, value) {
                            if (!(jQuery.inArray(value.id, current_id) !== -1 )) {
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
                                var newdata = {dkey: dkey, data: {unit: 0, costs: value.price, total: 0,product_id:dkey}};
                                data_table.push(newdata);


                                var html = '<tr id="line-' + value.id + '">';

                                html += '<td><button type="button" class="btn btn-danger pull-center remove-line btn-sm" ' +
                                    'data-id="' + value.id + '"> <i class="material-icons">close</i></button></td>';

                                html += '<td><label for="form-control-label">' + value.id + '</label><input type="hidden" ' +
                                    'name="product_id[]" class="form-control" value="' + value.id + '"></td>';
                                html += '<td><label for="form-control-label">' + value.name + '</label></td>';

                                html += '<td><label for="form-control-label">' + value.unidad_para_compra + '</label></td>';

                                html += '<td><input type="number" name="units[]" class="form-control units-line" value="0"' +
                                    'min="1" data-id="' + value.id + '" id="unit-' + value.id + '"></td>';

                                html += '<td><input type="number" class="form-control price-line" min="1"'+
                                    'step="0.2" value="' + value.price + '" name="costs[]" data-id="' + value.id + '" id="costs-' + value.id + '"/></td>';

                                html += '<td><label for="form-control-label " id="total-' + value.id + '">0.00</label></td>';


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
        }


    })

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

            if(value.dkey == id){
                costs = Number(value.data.costs)
                return false;
            }

        });

        total+= Number(units * costs);

        //update data
        jQuery(data_table).each(function (index, value) {

            if(value.dkey == id){
                value.data.unit = units
                value.data.costs = costs
                value.data.total = total
                return false;
            }

        });
        $('#total-'+id).html(total.toFixed(2))


        calculateTotal()

    });
    $('.table-data-add').on('keyup mouseup', '.price-line', function () {

        var id = $(this).data('id')
        var costs = Number($(this).val());
        var units = Number(0);
        var total = Number(0);

        //current data line
        jQuery(data_table).each(function (index, value) {

            if(value.dkey == id){
                units = Number(value.data.unit)
                return false;
            }

        });

        total+= Number(units * costs);

        //update data
        jQuery(data_table).each(function (index, value) {

            if(value.dkey == id){
                value.data.unit = units
                value.data.costs = costs
                value.data.total = total
                return false;
            }

        });
        $('#total-'+id).html(total.toFixed(2))
        calculateTotal()

    });

    function documentValidate() {

        var check = true;
        $.each($('.validate'), function (index, value) {


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

    function tableValidate() {
        var check = true;

        if(data_table.length >0){

            jQuery(data_table).each(function (index, value) {
                if(Number(value.data.unit)<=0 || Number(value.data.costs)<=0 || Number(value.data.total)<=0){
                    check = false;
                }

            });
        }else{

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

        if($('#total-table')){
            $('#total-table').html("$ "+Number(total).toFixed(2));
        }


    }

    function getDataToTable(id){

        if(id){
            $.ajax({

                data: {a: 'GET-PURCHASE-REQUEST-DETAILS', id: id},
                url: "./?action=admin",
                type: "POST",
                dataType: "JSON",
                success: function (data) {

                    if (data.length > 0) {
                        var disabled ="";
                        var readonly ="";
                        var disableClass ="";
                        if($('.disable-button').length>0){
                            disabled = "disabled ='disabled'";
                            readonly = "readonly ='readonly'";
                            disableClass ="disable-button";
                        }
                        $.each(data, function (index, value) {
                            var dkey = value.id;
                            var newdata = {dkey: dkey, data: {unit: value.unit, costs: value.costs, total: value.total,product_id:dkey}};
                            data_table.push(newdata);


                            var html = '<tr id="line-' + value.id + '">';

                            html += '<td><button type="button" class="btn btn-danger pull-center remove-line btn-sm '+disableClass+' " ' +
                                'data-id="' + value.id + '" '+disabled+'> <i class="material-icons">close</i></button></td>';

                            html += '<td><label for="form-control-label">' + value.id + '</label><input type="hidden" ' +
                                'name="product_id[]" class="form-control" value="' + value.id + '" ></td>';
                            html += '<td><label for="form-control-label">' + value.name + '</label></td>';

                            html += '<td><label for="form-control-label">' + value.unidad_para_compra + '</label></td>';

                            html += '<td><input type="number" name="units[]" class="form-control units-line" value="'+value.unit+'"' +
                                'min="1" data-id="' + value.id + '" id="unit-' + value.id + '" '+readonly+'></td>';

                            html += '<td><input type="number" class="form-control price-line" min="1"'+
                                'step="0.2" value="' + value.costs + '" name="costs[]" data-id="' + value.id + '" id="costs-' + value.id + '" '+readonly+'/></td>';

                            html += '<td><label for="form-control-label " id="total-' + value.id + '">'+value.total+'</label></td>';


                            html += '</tr>';
                            $('.table-data-add').append(html);

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
});
