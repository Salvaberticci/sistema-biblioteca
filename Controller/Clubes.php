<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clubes extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('ClubesModel');
    }

    public function index() {
        $data['clubes'] = $this->ClubesModel->obtenerClubes();
        $this->load->view('clubes/index', $data);
    }

    public function crear() {
        if ($this->input->post()) {
            $data = [
                'nombre' => $this->input->post('nombreClub'),
                'descripcion' => $this->input->post('descripcionClub')
            ];
            $this->ClubesModel->insertarClub($data);
            redirect('clubes');
        }
        $this->load->view('clubes/crear');
    }
}
