<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ClubesModel extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function obtenerClubes() {
        $query = $this->db->get('clubes');
        return $query->result();
    }

    public function insertarClub($data) {
        return $this->db->insert('clubes', $data);
    }

    public function obtenerMiembrosPorClub($club_id) {
        $this->db->where('club_id', $club_id);
        $query = $this->db->get('miembros');
        return $query->result();
    }

    public function programarReunion($data) {
        return $this->db->insert('reuniones', $data);
    }

    public function obtenerReunionesPorClub($club_id) {
        $this->db->where('club_id', $club_id);
        $query = $this->db->get('reuniones');
        return $query->result();
    }

    public function insertarNoticia($data) {
        return $this->db->insert('noticias', $data);
    }

    public function obtenerNoticiasPorClub($club_id) {
        $this->db->where('club_id', $club_id);
        $query = $this->db->get('noticias');
        return $query->result();
    }
}
