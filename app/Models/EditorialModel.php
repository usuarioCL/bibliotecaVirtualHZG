<?php
namespace App\Models;
use CodeIgniter\Model;

class EditorialModel extends Model
{
    protected $table = 'editoriales';
    protected $primaryKey = 'ideditorial';
    protected $allowedFields = ['editorial'];
}