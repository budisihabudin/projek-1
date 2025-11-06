<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_migrations_table extends CI_Migration {

    public function up() {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'version' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'null' => FALSE
            ],
            'class' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE
            ],
            'method' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE
            ],
            'time' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => FALSE,
                'default' => 0
            ]
        ]);

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('migrations');

        echo "✓ Created migrations table\n";
    }

    public function down() {
        $this->dbforge->drop_table('migrations');
        echo "✗ Dropped migrations table\n";
    }
}