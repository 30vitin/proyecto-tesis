<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function () {
        if($('#table')){
            var targets = [1];
            if($('.column-image').length>0 && $('.column-image').data('target')){
                targets = $('.column-image').data('target');
            }
            if($('.column-image').length>0){
                $('#table').DataTable({
                    language: {
                        search: "Consultar registro: ",
                        searchPlaceholder: "...",
                        zeroRecords: "No se encontraron resultados",
                        paginate: {
                            "first":      "Primera",
                            "last":       "Ultima",
                            "next":       "Siguiente",
                            "previous":   "Atras"
                        },
                        infoEmpty:      "Mostrando 0 resultados",
                        info:           "Mostrando _START_ de _END_ / Total de registros _TOTAL_ ",
                        lengthMenu:     "Mostrar _MENU_ por pagina",

                    },
                    autoWidth: false, // might need this
                    columnDefs: [{   width: 25,orderable: false,targets: targets},]
                });
            }else{
                $('#table').DataTable({
                    ordering: true,
                    order: [[ 1, "asc" ]],
                    language: {

                        search: "Consultar registro: ",
                        searchPlaceholder: "...",
                        zeroRecords: "No se encontraron resultados",
                        paginate: {
                            "first":      "Primera",
                            "last":       "Ultima",
                            "next":       "Siguiente",
                            "previous":   "Atras"
                        },
                        infoEmpty:      "Mostrando 0 resultados",
                        info:           "Mostrando _START_ de _END_ / Total de registros _TOTAL_ ",
                        lengthMenu:     "Mostrar _MENU_ por pagina",

                    },
                    autoWidth: false, // might need this
                    footerCallback: function ( row, data, start, end, display ) {

                        if($("#total-buy-tbl").length>0){
                            var api = this.api(), data;
                            // Remove the formatting to get integer data for summation
                            var intVal = function ( i ) {
                                return typeof i === 'string' ?
                                    i.replace(/[\$,]/g, '')*1 :
                                    typeof i === 'number' ?
                                        i : 0;
                            };

                            total = api
                                .column( 3,{filter:'applied'} )
                                .data()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0 );
                            $("#total-buy-tbl" ).html(Number(total));
                        }
                        if($("#total-unrequest-tbl").length>0){
                            var api = this.api(), data;
                            // Remove the formatting to get integer data for summation
                            var intVal = function ( i ) {
                                return typeof i === 'string' ?
                                    i.replace(/[\$,]/g, '')*1 :
                                    typeof i === 'number' ?
                                        i : 0;
                            };

                            total = api
                                .column( 4,{filter:'applied'} )
                                .data()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0 );
                            $("#total-unrequest-tbl" ).html(Number(total));
                        }
                        if($("#total-diff-tbl").length>0){
                            var api = this.api(), data;
                            // Remove the formatting to get integer data for summation
                            var intVal = function ( i ) {
                                return typeof i === 'string' ?
                                    i.replace(/[\$,]/g, '')*1 :
                                    typeof i === 'number' ?
                                        i : 0;
                            };

                            total = api
                                .column( 5,{filter:'applied'} )
                                .data()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0 );
                            $("#total-diff-tbl" ).html(Number(total));
                        }

                        if($('#custom-total').length>0){
                            var col = $('#custom-total').data('colum');
                            var api = this.api(), data;
                            // Remove the formatting to get integer data for summation
                            var intVal = function ( i ) {
                                return typeof i === 'string' ?
                                    i.replace(/[\$,]/g, '')*1 :
                                    typeof i === 'number' ?
                                        i : 0;
                            };

                            total = api
                                .column( col,{filter:'applied'} )
                                .data()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0 );
                            if($('#custom-total').data('fixed')=="NO"){
                                $("#custom-total" ).html(Number(total));
                            }else{
                                $("#custom-total" ).html(Number(total).toFixed(2));
                            }

                        }


                    }

                });
            }

        }
    });
</script>