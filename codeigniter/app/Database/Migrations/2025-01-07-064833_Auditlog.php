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
            'user_id' => [  // Changed from 'name' to 'user_id' to reference users table
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'logs' => [
                'type' => 'TEXT',
            ],
        ]);

        // Adding a foreign key to the 'user_id' to reference the 'id' from the 'users' table
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('auditlog');
    }

    public function down()
    {
        $this->forge->dropTable('auditlog');
    }
}
