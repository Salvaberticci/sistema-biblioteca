<?php encabezado() ?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <h1 class="mt-4">Gestión de Recursos Digitales</h1>
            <!-- Aquí va el contenido de la tabla o listado de recursos -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Descripción</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recursos as $recurso) { ?>
                                    <tr>
                                        <td><?php echo $recurso['nombre']; ?></td>
                                        <td><?php echo $recurso['tipo']; ?></td>
                                        <td><?php echo $recurso['descripcion']; ?></td>
                                        <td>
                                            <!-- Aquí van los botones de editar y eliminar -->
                                            <a href="#" class="btn btn-sm btn-primary">Editar</a>
                                            <a href="#" class="btn btn-sm btn-danger">Eliminar</a>
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

<?php pie() ?>
