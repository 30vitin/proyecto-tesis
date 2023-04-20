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


                    }

                });


        }

    });
</script>