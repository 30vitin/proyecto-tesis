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
                });
            }

        }
    });
</script>