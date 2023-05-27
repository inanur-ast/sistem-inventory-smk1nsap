<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Kondisi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'konid' => [
                'type' => 'int',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'konnama' => [
                'type' => 'varchar',
                'constraint' => '50'
            ]
        ]);
        $this->forge->addKey('konid');
        $this->forge->createTable('kondisi');
    }

    public function down()
    {
        $this->forge->dropTable('kondisi');
    }
}
