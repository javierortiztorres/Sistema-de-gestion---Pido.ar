<?php

namespace App\Controllers;

class BackupController extends BaseController
{
    public function index()
    {
        $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        
        // Database configuration
        $db = \Config\Database::connect();
        $hostname = $db->hostname;
        $username = $db->username;
        $password = $db->password;
        $database = $db->database;
        $port     = $db->port;

        // Command to execute mysqldump
        // Note: Using --column-statistics=0 for compatibility with some server versions
        $command = "mysqldump --host={$hostname} --port={$port} --user={$username} --password={$password} --column-statistics=0 {$database}";

        // set headers to force download
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . $filename . "\"");

        // Execute command and output directly to browser
        passthru($command);
        exit;
    }
}
