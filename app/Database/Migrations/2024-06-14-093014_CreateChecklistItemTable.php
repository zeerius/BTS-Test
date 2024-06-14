<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateChecklistItemTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'checklist_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
            ],
            'item_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'status' => [
                'type'       => 'BOOLEAN',
                'default' => 1
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('checklist_id', 'checklists', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('checklist_items');
    }

    public function down()
    {
        $this->forge->dropTable('checklist_items');
    }
}
