<?php

class DonacionesModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function obtenerDonaciones()
    {
        $sql = "SELECT * FROM donaciones";
        $request = $this->select_all($sql);
        return $request;
    }

    public function insertarDonacion($nombre_donante, $correo_donante, $monto, $fecha)
    {
        $sql = "INSERT INTO donaciones (nombre_donante, correo_donante, monto, fecha_donacion) VALUES (?, ?, ?, ?)";
        $arrData = array($nombre_donante, $correo_donante, $monto, $fecha);
        $request = $this->insert($sql, $arrData);
        return $request;
    }

    public function obtenerDonacionPorId($id)
    {
        $sql = "SELECT * FROM donaciones WHERE id = ?";
        $arrData = array($id);
        $request = $this->select($sql, $arrData);
        return $request;
    }

    public function actualizarDonacion($id, $nombre_donante, $correo_donante, $monto, $fecha)
    {
        $sql = "UPDATE donaciones SET nombre_donante = ?, correo_donante = ?, monto = ?, fecha_donacion = ? WHERE id = ?";
        $arrData = array($nombre_donante, $correo_donante, $monto, $fecha, $id);
        $request = $this->update($sql, $arrData);
        return $request;
    }
    public function eliminarDonacion($id)
    {
        $sql = "DELETE FROM donaciones WHERE id = ?";
        $arrData = array($id);
        return $this->delete($sql, $arrData);
    }
}
