<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Accesslevels extends Migration
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
            'roles' => [
                'type' => 'ENUM',
                'constraint' => ['admin','agent', 'teamLeader', 'supervisor'],
                'null' => false,
                'default' => 'admin' 
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('accesslevels');
    }

    public function down()
    {
        $this->forge->dropTable('accesslevels');
    }
}
