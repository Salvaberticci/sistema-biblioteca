<?php

class Donaciones extends Controllers
{
    public function __construct()
    {
        session_start();
        if (empty($_SESSION['activo'])) {
            header("location: " . base_url());
        }
        parent::__construct();
        $this->loadModel('DonacionesModel'); // Asegúrate de cargar el modelo correctamente
    }

    public function listar()
    {
        $data['donaciones'] = $this->model->obtenerDonaciones();
        $this->views->getView($this, "listar", $data);
    }

    public function registrar()
    {
        if ($_POST) {
            $nombre_donante = isset($_POST['nombre']) ? $_POST['nombre'] : '';
            $correo_donante = isset($_POST['correo']) ? $_POST['correo'] : '';
            $monto = isset($_POST['monto']) ? $_POST['monto'] : '';
            $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : '';

            if (!empty($nombre_donante) && !empty($correo_donante) && !empty($monto) && !empty($fecha)) {
                $this->model->insertarDonacion($nombre_donante, $correo_donante, $monto, $fecha);
                header("location: " . base_url() . "donaciones/listar");
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Por favor completa todos los campos']);
                exit;
            }
        }
        $this->views->getView($this, "registrar");
    }

    public function obtener($id)
    {
        $donacion = $this->model->obtenerDonacionPorId($id);
        if ($donacion) {
            echo json_encode(['status' => 'success', 'data' => $donacion]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Donación no encontrada']);
        }
    }

    public function editar()
    {
        if ($_POST) {
            $id = $_POST['id'];
            $nombre_donante = $_POST['nombre'];
            $correo_donante = $_POST['correo'];
            $monto = $_POST['monto'];
            $fecha = $_POST['fecha'];

            if (!empty($id) && !empty($nombre_donante) && !empty($correo_donante) && !empty($monto) && !empty($fecha)) {
                $this->model->actualizarDonacion($id, $nombre_donante, $correo_donante, $monto, $fecha);
                echo json_encode(['status' => 'success', 'message' => 'Donación actualizada correctamente']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Por favor completa todos los campos']);
            }
        }
    }

    public function eliminar()
    {
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $id = $_POST['id'];
            $this->loadModel('DonacionesModel');
            $result = $this->model->eliminarDonacion($id);
            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Donación eliminada correctamente']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al eliminar la donación']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID no proporcionado']);
        }
    }
}
