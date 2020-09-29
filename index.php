<?php

    require 'vendor/autoload.php';

    use Medoo\Medoo;

    function main() {
        $database = new Medoo([
            'database_type' => 'mysql',
            'database_name' => 'adhoc',
            'server' => 'localhost',
            'username' => 'root',
            'password' => '',
            'option' => [
                PDO::ATTR_CASE => PDO::CASE_NATURAL
            ],
        ]);
        
        $patterns = require('patterns.php');
        // Path where are the file to process
        $PATH_FILE_TO_PROCESS    = 'FileDaElaborare';
        // Path where are moved file processed
        $PATH_FILE_PROCESSED = 'FileElaborati';

        $files = array_diff(scandir($PATH_FILE_TO_PROCESS), array('.', '..'));

        foreach ($files as $file_name) {
            $file = fopen($PATH_FILE_TO_PROCESS. '/' . $file_name, "r") or die("Unable to open file!");
            $pattern_name = preg_split('/(?=\d)/', $file_name)[0];

            // Check if there is a pattern for the file
            // if not it will not process the file
            if (!isset($patterns[$pattern_name])) {
                 echo 'There is no pattern for the file "' . $pattern_name . "\"<br>";
                 continue;
            }
                
            $pattern = $patterns[$pattern_name]; 

            $table_name = $pattern['_table'];

            // Output one line until end-of-file
            while(!feof($file)) {
                $line = fgets($file);

                // If the line is empty, skip it
                if (!strlen($line))
                    continue;

                $obj = [];
                $where = [];

                // Do a loop for each value of the pattern array
                foreach ($pattern as $key=>$column) {
                    // If the value is a "private" value, skip it
                    if (substr($key, 0, 1) == "_") 
                        continue;
                    // Thanks to substr i get the value.
                    $value = substr($line, $column[0]-1, $column[1]);
                    // If it has an action value that set if the record
                    // needs to be insert, updated or deleted.
                    if ($key == "action") {
                        $action = substr($line, $column[0]-1, $column[1]);
                        continue;
                    }
                    // If the key of the column is between keys attributes
                    // it will put it inside a "where" variable used for 
                    // an eventual update of the record.
                    if (in_array($key, $pattern["_keys"]))
                        $where[$key] = $value;
                    // Save the value inside a temporany object
                    $obj[$key] = $value;
                }

                switch(strtolower($action) ?? NULL) {
                    case NULL:
                    case 'i': // insert
                    case 'm':
                        if ($database->has($table_name, $where)) {
                            $database->update($table_name, $obj, $where);
                        } else {
                            $database->insert($table_name, $obj);
                        }
                        break;
                    case 'a': // delete 
                        $database->update($table_name, [$pattern['_softdelete'] => date('Y-m-d H:i:s')], $where);
                        break;
                    default:
                        break;
                }
                
            }

            fclose($file);

            // Move files inside files processed directory
            // so it will not be processed again.
            rename($PATH_FILE_TO_PROCESS . '/' . $file_name, $PATH_FILE_PROCESSED . '/' . $file_name);
        }        
    }

    main();
?>