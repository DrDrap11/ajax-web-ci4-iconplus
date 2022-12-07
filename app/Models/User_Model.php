<?php

namespace App\Models;

use CodeIgniter\Model;

class User_model extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $allowedFields = ['user_id', 'user_name', 'user_email', 'status', 'user_password', 'role', 'created_at', 'updated_at', 'deleted_at'];

    protected $useSoftDeletes   = TRUE;
    protected $dateFormat       = 'datetime';
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';

    public function getUser($id)
    {

        $builder = $this->db->table($this->table);

        $query = $builder->getWhere(['user_id' => $id]);
        return $query->getRow();
    }
}