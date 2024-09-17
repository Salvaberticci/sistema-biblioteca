<?php
class RecursosModel extends Mysql {
    
    public function __construct() {
        parent::__construct();
        $this->load->database(); // Cargar la base de datos, ajustado a tu estructura
    }
    
    public function obtenerRecursos() {
        // Implementar lógica para obtener recursos desde la base de datos
        $query = $this->db->get('recursos');
        return $query->result_array();
    }
    
    // Puedes agregar más métodos según las operaciones que necesites realizar (crear, actualizar, eliminar)
    
}
?>
