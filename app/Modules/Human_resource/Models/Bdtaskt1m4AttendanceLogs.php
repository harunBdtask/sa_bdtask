<?php namespace App\Modules\Human_resource\Models;
use CodeIgniter\Model;
class Bdtaskt1m4AttendanceLogs extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'hrm_attendance_history';
    protected $primaryKey           = 'atten_his_id ';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDelete        = false;
    protected $protectFields        = true;
    protected $allowedFields        = [];

    // Dates
    protected $useTimestamps        = false;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks       = true;
    protected $beforeInsert         = [];
    protected $afterInsert          = [];
    protected $beforeUpdate         = [];
    protected $afterUpdate          = [];
    protected $beforeFind           = [];
    protected $afterFind            = [];
    protected $beforeDelete         = [];
    protected $afterDelete          = [];

    // public function __construct()
    // {
    //     $this->db = db_connect();
    //     if(session('defaultLang')=='english'){
    //         $this->langColumn = 'nameE';
    //     }else{
    //         $this->langColumn = 'nameA';
    //     }

    // }

}
?>