<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Allow CLI access
        if (!$this->input->is_cli_request()) {
            show_error('You are not allowed to access this page.', 403);
            return;
        }
        $this->load->library('migration');
    }

    public function index() {
        echo "=== Database Migration ===\n\n";

        // Set migration path
        $this->migration->set_path(APPPATH . 'migrations/');

        // Run migration
        if ($this->migration->latest() === FALSE) {
            show_error($this->migration->error_string());
        } else {
            echo "✅ Migration completed successfully!\n";
            echo "📋 Applied migrations:\n";

            // Get migration history
            $this->db->order_by('id', 'ASC');
            $migrations = $this->db->get('migrations')->result();

            foreach ($migrations as $migration) {
                echo "   - {$migration->version}\n";
            }

            echo "\n🎉 Database is up to date!\n";
        }
    }

    public function rollback($version = 0) {
        echo "=== Database Rollback ===\n\n";

        // Set migration path
        $this->migration->set_path(APPPATH . 'migrations/');

        // Rollback to specific version
        if ($this->migration->version($version) === FALSE) {
            show_error($this->migration->error_string());
        } else {
            echo "✅ Rollback completed successfully!\n";
            echo "📋 Database rolled back to version: {$version}\n";
        }
    }

    public function reset() {
        echo "=== Database Reset ===\n\n";

        // Set migration path
        $this->migration->set_path(APPPATH . 'migrations/');

        // Reset all migrations
        if ($this->migration->reset() === FALSE) {
            show_error($this->migration->error_string());
        } else {
            echo "✅ Database reset completed!\n";
            echo "🗑️ All migrations have been removed\n";
        }
    }

    public function current() {
        echo "=== Current Migration Status ===\n\n";

        // Set migration path
        $this->migration->set_path(APPPATH . 'migrations/');

        $current = $this->migration->current();
        echo "📊 Current migration version: {$current}\n";

        // Show migration history
        $this->db->order_by('id', 'ASC');
        $migrations = $this->db->get('migrations')->result();

        if (!empty($migrations)) {
            echo "\n📋 Applied migrations:\n";
            foreach ($migrations as $migration) {
                echo "   - {$migration->version}\n";
            }
        } else {
            echo "\n❌ No migrations applied yet\n";
        }
    }
}