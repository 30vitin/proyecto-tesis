<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function () {
        if($('.table-dashboard').length>0){
            var targets = [1];

            $('.table-dashboard').DataTable({
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

                        if($('#custom-total').length>0){
                            console.log('aa')
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

                        }else{
                            /*var api = this.api(), data;

                            // Remove the formatting to get integer data for summation
                            var intVal = function ( i ) {
                                return typeof i === 'string' ?
                                    i.replace(/[\$,]/g, '')*1 :
                                    typeof i === 'number' ?
                                        i : 0;
                            };

                            // Total over all pages
                            total = api
                                .column( 7 )
                                .data()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0 );

                            // Total over this page
                            pageTotal = api
                                .column( 7, { page: 'current'} )
                                .data()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0 );

                            // Update footer

                            $( api.column( 7 ).footer() ).html(
                                '$'+Number(total).toFixed(2)
                            );*/

                        }
                    }

                });


        }

    });
</script>