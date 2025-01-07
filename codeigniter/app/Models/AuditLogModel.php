<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditLogModel extends Model {
    // Table name
    protected $table      = 'auditlog';
    
    // Primary key
    protected $primaryKey = 'id';

    // Auto increment
    protected $useAutoIncrement = true;

    // Return type
    protected $returnType     = 'object';

    // Soft deletes
    protected $useSoftDeletes = false;

    // Fields allowed to be inserted or updated
    protected $allowedFields = ['datetime', 'action', 'user_id', 'name', 'logs'];

    // Empty inserts are not allowed
    protected bool $allowEmptyInserts = false;

    // Only update changed fields
    protected bool $updateOnlyChanged = true;

    // Date format settings
    protected $useTimestamps = false;  // You are not using timestamps like created_at/updated_at
    protected $dateFormat    = 'datetime';  // Using the datetime format
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation rules (if needed, you can define rules here)
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}

?>
