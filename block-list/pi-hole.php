<?php
/**
 * - Read file provided by command line
 * - Transform to pi-hole compatible blocklist file entries
 */

if (empty($argv[1])) {
    fwrite(STDERR, 'Missing file to parse' . PHP_EOL);
    die(1);
}

$file = $argv[1];
$data = json_decode(file_get_contents($file));

if (empty($data)) {
    fwrite(STDERR, 'Invalid file to parse: ' . $file . PHP_EOL);
    die(2);
}

// Make sure, each domain only once 
$unique = [];

foreach ($data->resources as $resource) {
    $domain = strtolower($resource->rule);
    
    if (!isset($unique[$domain])) {
        echo $resource->rule, PHP_EOL;
    
        $unique[$domain] = true;

    }
}
