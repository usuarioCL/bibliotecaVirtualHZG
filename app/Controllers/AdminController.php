<?php

namespace App\Controllers;

class AdminController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function dashboard()
    {
        return view('Administrador/dashboard/index');
    }

    public function login()
    {
        return view('Administrador/dashboard/authentication-login');
    }

    public function register()
    {
        return view('Administrador/dashboard/authentication-register');
    }

    // Vista para usuarios y roles
    public function VistaUsuariosRoles()
    {
        $query = $this->db->query("
            SELECT u.nomuser, u.nivelacceso, p.nombres, p.apellidos, p.email
            FROM usuarios u
            JOIN personas p ON u.idpersona = p.idpersona
        ");
        $data['usuarios'] = $query->getResult();
        return view('Administrador/vistas/UsuariosRoles', $data);
    }

    // Vista para préstamos y alumnos
    public function VistaPrestamosAlumnos()
    {
        $query = $this->db->query("
            SELECT p.fechaprestamo, per.nombres, per.apellidos, r.titulo
            FROM prestamos p
            INNER JOIN matriculas m ON p.idmatricula = m.idmatricula
            INNER JOIN personas per ON m.idpersona = per.idpersona
            INNER JOIN recursos r ON p.idrecurso = r.idrecurso
        ");
        $data['prestamos'] = $query->getResult();
        return view('Administrador/vistas/PrestamosAlumnos', $data);
    }

    public function VistaReaccionesUsuarios()
    {
        $query = $this->db->query("
            SELECT u.nomuser, r.titulo, re.tiporeaccion
            FROM reacciones re
            INNER JOIN usuarios u ON re.idusuario = u.idusuario
            INNER JOIN recursos r ON re.idrecurso = r.idrecurso
        ");
        $data['reacciones'] = $query->getResult();
        return view('Administrador/vistas/ReaccionesUsuarios', $data);
    }

    public function VistaAlumnosSancionados()
    {
        $query = $this->db->query("
            SELECT s.detallesancion, ts.tiposancion, per.nombres, per.apellidos
            FROM sanciones s
            INNER JOIN tiposancion ts ON s.idtiposancion = ts.idtiposancion
            INNER JOIN personas per ON s.idpersona = per.idpersona
        ");
        $data['sancionados'] = $query->getResult();
        return view('Administrador/vistas/AlumnosSancionados', $data);
    }
}