
<script type="text/javascript">
(function () {
var options = {
//facebook: "1234567891011123", // Facebook page ID*/
whatsapp: "+52 123456789", // Número de WhatsApp
call_to_action: "Envíanos un mensaje", // Mensaje de botón
button_color: "#5e2129", // Color de botón  
position: "right", // Posición de botón 'right' o 'left'
order: "facebook,whatsapp", // Order of buttons
pre_filled_message: "Hola Biblioteca Antonio Jose Pacheco, Necesito ayuda con Sistema Bibliotecas", // Mensaje precargado de WhatsApp
};
var proto = document.location.protocol, host = "getbutton.io", url = proto + "//static." + host;
var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = url + '/widget-send-button/js/init.js';
s.onload = function () { WhWidgetSendButton.init(host, proto, options); };
var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x);
})();
</script>

   

   
   
   
   
   
<footer class="py-4 bg-light mt-auto">
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between small">
           
        </div>
    </div>
</footer>
</div>
<!-- JavaScript files-->
<script src="<?php echo base_url(); ?>Assets/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>Assets/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url(); ?>Assets/js/select2.min.js"></script>
<script src="<?php echo base_url(); ?>Assets/js/scripts.js"></script>
<script src="<?php echo base_url(); ?>Assets/js/Funciones.js"></script>
<script src="<?php echo base_url(); ?>Assets/js/all.min.js"></script>
<script src="<?php echo base_url(); ?>Assets/js/sweetalert2@9.js"></script>
<script src="<?php echo base_url(); ?>Assets/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>Assets/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function() {
        $('#table').DataTable({
            language: {
                "decimal": "",
                "emptyTable": "No hay datos",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                "infoFiltered": "(Filtro de _MAX_ total registros)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ registros",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "No se encontraron coincidencias",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                },
                "aria": {
                    "sortAscending": ": Activar orden de columna ascendente",
                    "sortDescending": ": Activar orden de columna desendente"
                }
            }
        });
    });
</script>
</body>

</html>