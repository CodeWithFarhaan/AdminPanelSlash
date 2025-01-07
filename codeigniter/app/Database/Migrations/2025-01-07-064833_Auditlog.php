<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Auditlog extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11, // Changed to 11 to allow for larger IDs
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'datetime' => [
                'type' => 'DATETIME',
            ],
            'action' => [
                'type' => 'ENUM',
                'constraint' => ['create', 'update', 'delete'],
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'entity' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'entity_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'details' => [
                'type' => 'TEXT',
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE'); // Foreign key to users table
        $this->forge->createTable('auditlog');
    }

    public function down()
    {
        $this->forge->dropTable('auditlog');
    }
}
