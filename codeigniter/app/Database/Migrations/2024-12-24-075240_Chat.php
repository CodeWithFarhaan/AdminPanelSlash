<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Chat extends Migration
{
    public function up()
    {
        // Define the fields for the table
        $this->forge->addField([
            'c_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'c_resource_id' => [
                'type' => 'INT',
            ],
            'c_user_id' => [
                'type' => 'INT',
            ],
            'c_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
        ]);

        // Set the primary key to the correct column (c_id)
        $this->forge->addPrimaryKey('c_id');

        // Create the table
        $this->forge->createTable('chat');
    }

    public function down()
    {
        // Drop the chat table
        $this->forge->dropTable('chat');
    }
}
