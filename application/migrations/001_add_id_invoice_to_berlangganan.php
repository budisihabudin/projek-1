<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_id_invoice_to_berlangganan extends CI_Migration {

    public function up() {
        // Add id_invoice column
        $this->dbforge->add_column('berlangganan', [
            'id_invoice' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => TRUE,
                'after' => 'bukti_bayar'
            ]
        ]);

        // Add index for performance
        $this->dbforge->add_key('berlangganan', 'id_invoice', FALSE);

        // Update existing records with invoice format
        $this->db->query("
            UPDATE berlangganan
            SET id_invoice = CONCAT('INV-', DATE_FORMAT(tgl_mulai, '%Y%m'), '-', LPAD(id_customer, 4, '0'), '-', LPAD(id_berlangganan, 3, '0'))
            WHERE id_invoice IS NULL
        ");

        echo "✓ Added id_invoice column to berlangganan table\n";
        echo "✓ Added index for id_invoice\n";
        echo "✓ Updated existing records with invoice format\n";
    }

    public function down() {
        // Remove index
        $this->dbforge->drop_key('berlangganan', 'id_invoice');

        // Remove column
        $this->dbforge->drop_column('berlangganan', 'id_invoice');

        echo "✗ Removed id_invoice column from berlangganan table\n";
    }
}