<?php

    return [
        // Since my test files was based on a title that contains number like ulist13, I explode 
        // file titles from the first number and use that part as name of the pattern.
        "file_title" => [
            // Table name, this key will contain the name of the table where data will be inserted
            '_table' => '#', 
            // In keys you can insert the keys you have in database, these will help you to
            // avoid duplicates and find value to edit
            '_keys' => ['codice_articolo', 'codice_listino'],
            // Soft delete column name
            '_softdelete' => 'deleted_at',
            // Action key is used to know which type of operation the script will need to do.
            // At the moment 'i' stands for insert, 'm' for edit and 'a' for soft delete.
            'action' => [1, 1],
            // Custom keys where you put the columns to scraper, inserting the start position
            // and the leght of the content.
            'custom_column' => [START_POSITION, LENGHT_OF_THE_CONTENT],
        ],
    ]

?>