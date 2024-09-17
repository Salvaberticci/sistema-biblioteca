<?php encabezado() ?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <button class="btn btn-primary mb-2" type="button" data-toggle="modal" data-target="#modalRegistroDonacion">
                        <i class="fas fa-donate"></i> Registrar Donación
                    </button>
                    <div class="table-responsive">
                        <table class="table table-light mt-4" id="table">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Monto</th>
                                    <th>Fecha de donación</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['donaciones'] as $donacion) { ?>
                                    <tr>
                                        <td><?php echo $donacion['id']; ?></td>
                                        <td><?php echo $donacion['nombre_donante']; ?></td>
                                        <td><?php echo $donacion['correo_donante']; ?></td>
                                        <td><?php echo $donacion['monto']; ?></td>
                                        <td><?php echo $donacion['fecha_donacion']; ?></td>
                                        <td>
                                            
                                            <form method="post" action="<?php echo base_url() ?>donaciones/eliminar" class="d-inline">
                                                <input type="hidden" name="id" value="<?php echo $donacion['id']; ?>">
                                                <button class="btn btn-danger" type="button" onclick="eliminarDonacion(<?php echo $donacion['id']; ?>)">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>

                                            </form>

                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Modal para registrar donaciones -->
<div class="modal fade" id="modalRegistroDonacion" tabindex="-1" role="dialog" aria-labelledby="modalRegistroDonacionLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRegistroDonacionLabel">Registrar Donación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?php echo base_url() ?>donaciones/registrar" method="post" id="frmDonaciones" class="row" autocomplete="off">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="nombre">Nombre del Donante</label>
                            <input id="nombre" class="form-control" type="text" name="nombre" placeholder="Nombre del donante">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="correo">Correo del Donante</label>
                            <input id="correo" class="form-control" type="email" name="correo" placeholder="Correo del donante">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="monto">Monto</label>
                            <input id="monto" class="form-control" type="text" name="monto" placeholder="Monto de la donación">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="fecha">Fecha de Donación</label>
                            <input id="fecha" class="form-control" type="date" name="fecha" placeholder="Fecha de la donación">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar donaciones -->
<div class="modal fade" id="modalEditarDonacion" tabindex="-1" role="dialog" aria-labelledby="modalEditarDonacionLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarDonacionLabel">Editar Donación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEditarDonacion" onsubmit="event.preventDefault(); editarDonacionSubmit();">
                    <input type="hidden" id="idEditar" name="idEditar">
                    <div class="form-group">
                        <label for="nombreEditar">Nombre del Donante</label>
                        <input type="text" class="form-control" id="nombreEditar" name="nombreEditar" required>
                    </div>
                    <div class="form-group">
                        <label for="correoEditar">Correo del Donante</label>
                        <input type="email" class="form-control" id="correoEditar" name="correoEditar" required>
                    </div>
                    <div class="form-group">
                        <label for="montoEditar">Monto</label>
                        <input type="text" class="form-control" id="montoEditar" name="montoEditar" required>
                    </div>
                    <div class="form-group">
                        <label for="fechaEditar">Fecha de la Donación</label>
                        <input type="date" class="form-control" id="fechaEditar" name="fechaEditar" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function editarDonacion(id) {
        $.ajax({
            url: '<?php echo base_url() ?>donaciones/obtener/' + id,
            type: 'GET',
            success: function(response) {
                try {
                    var data = JSON.parse(response);
                    if (data.status == 'success') {
                        $('#idEditar').val(data.data.id);
                        $('#nombreEditar').val(data.data.nombre_donante);
                        $('#correoEditar').val(data.data.correo_donante);
                        $('#montoEditar').val(data.data.monto);
                        $('#fechaEditar').val(data.data.fecha_donacion);
                        $('#modalEditarDonacion').modal('show');
                    } else {
                        alert(data.message);
                    }
                } catch (error) {
                    console.error('Error al analizar JSON:', error);
                    console.log('Respuesta completa del servidor:', response);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la solicitud AJAX:', error);
            }
        });
    }

    function editarDonacionSubmit() {
        var formData = $('#formEditarDonacion').serialize();
        $.ajax({
            url: '<?php echo base_url() ?>donaciones/editar',
            type: 'POST',
            data: formData,
            success: function(response) {
                try {
                    var data = JSON.parse(response);
                    if (data.status == 'success') {
                        $('#modalEditarDonacion').modal('hide');
                        alert('Donación actualizada correctamente');
                        window.location.reload(); // Recargar la página para reflejar cambios
                    } else {
                        alert('Error al actualizar la donación: ' + data.message);
                    }
                } catch (error) {
                    console.error('Error al analizar JSON:', error);
                    console.log('Respuesta completa del servidor:', response);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la solicitud AJAX:', error);
            }
        });
    }

    function eliminarDonacion(id) {
        if (confirm('¿Estás seguro de eliminar esta donación?')) {
            $.ajax({
                url: '<?php echo base_url() ?>donaciones/eliminar',
                type: 'POST',
                data: {
                    id: id
                },
                success: function(response) {
                    try {
                        var data = JSON.parse(response);
                        if (data.status == 'success') {
                            alert('Donación eliminada correctamente');
                            window.location.reload(); // Recargar la página para reflejar cambios
                        } else {
                            alert('Error al eliminar la donación: ' + data.message);
                        }
                    } catch (error) {
                        console.error('Error al analizar JSON:', error);
                        console.log('Respuesta completa del servidor:', response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error en la solicitud AJAX:', error);
                }
            });
        }
    }
</script>

<?php pie() ?>