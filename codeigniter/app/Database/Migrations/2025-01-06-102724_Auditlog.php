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
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'datetime' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'logs' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('auditlog');
    }

    public function down()
    {
        $this->forge->dropTable('auditlog');
    }
}
