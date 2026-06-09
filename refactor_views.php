<?php

$dir = new RecursiveDirectoryIterator('resources/views');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/^.+\.blade\.php$/i', RecursiveRegexIterator::GET_MATCH);

foreach ($files as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    
    // Replace User Model properties
    $content = str_replace('->user_id', '->id_user', $content);
    $content = str_replace('->user_name', '->id_user', $content);
    $content = str_replace('->role', '->getCurrentRole()', $content);
    
    // Replace form field names
    $content = str_replace('name="user_id"', 'name="id_user"', $content);
    $content = str_replace('name="user_name"', 'name="id_user"', $content);
    $content = str_replace('for="user_id"', 'for="id_user"', $content);
    $content = str_replace('for="user_name"', 'for="id_user"', $content);
    
    // Form value matching
    $content = str_replace('value="{{ old(\'user_id\') }}"', 'value="{{ old(\'id_user\') }}"', $content);
    $content = str_replace('value="{{ old(\'user_name\') }}"', 'value="{{ old(\'id_user\') }}"', $content);
    
    // Replace auth check variables
    $content = str_replace('Auth::user()->user_id', 'Auth::user()->id_user', $content);
    $content = str_replace('Auth::user()->user_name', 'Auth::user()->name', $content);
    
    file_put_contents($path, $content);
}
echo 'Views refactored successfully.';
