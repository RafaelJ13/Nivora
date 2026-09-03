<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNameToUsers extends Migration
{
    public function up()
    {
        if ($this->db->fieldExists('name', 'users')) {
            return;
        }

        $this->forge->addColumn('users', [
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'username',
            ],
        ]);
    }

    public function down()
    {
        if (! $this->db->fieldExists('name', 'users')) {
            return;
        }

        $this->forge->dropColumn('users', 'name');
    }
}