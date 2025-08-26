<?php
namespace App\Models;
use CodeIgniter\Model;

class EditorialModel extends Model
{
    protected $table = 'editoriales';
    protected $primaryKey = 'ideditorial';
    protected $allowedFields = ['editorial'];

    public function buscar($query)
    {
        return $this->like('editorial', $query)->findAll();
    }
}