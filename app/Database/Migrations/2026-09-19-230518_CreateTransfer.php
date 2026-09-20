<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransfer extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => false,
            ],
            'account_from_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => false,
            ],
            'account_to_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => false
            ],
            'amount' => [
                'type' => 'BIGINT',
                'constraint' => 255,
                'null' => false,
            ],
            'transfer_date' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('account_from_id');
        $this->forge->addKey('account_to_id');
        $this->forge->addKey('transfer_date');

        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('account_from_id', 'accounts', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('account_to_id', 'accounts', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('transfers');
    }

    public function down()
    {
        $this->forge->dropTable('transfers');
    }
}
