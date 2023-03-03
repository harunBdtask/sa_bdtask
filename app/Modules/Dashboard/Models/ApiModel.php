<?php 
namespace App\Modules\Dashboard\Models;
use CodeIgniter\Model;

class ApiModel extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id';
    protected $allowedFields = ['emp_id','fullname', 'email','username','password'];
}