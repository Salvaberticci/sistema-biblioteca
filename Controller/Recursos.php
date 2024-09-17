<?php

class Recursos extends Controllers {
    
    public function __construct() {
        parent::__construct();
        $this->loadModel('RecursosModel'); // Cargar el modelo personalizado
    }
    
    public function index() {
        $data['recursos'] = $this->RecursosModel->obtenerRecursos();
        $this->views->getView($this, 'recursos/lista', $data); // Usar tu método personalizado para cargar vistas
    }
    
    // Aquí puedes agregar más métodos para manejar la creación, edición y eliminación de recursos
    
}
?>
