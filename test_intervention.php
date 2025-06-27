<?php
// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';

// Check if GD is available
if (!extension_loaded('gd')) {
    echo "GD extension is NOT loaded!\n";
    exit(1);
}

echo "GD extension is loaded.\n";
$gd_info = gd_info();
echo "GD Version: " . $gd_info['GD Version'] . "\n";

try {
    // Try to use Intervention Image with GD driver
    echo "Testing Intervention Image with GD driver...\n";
    
    // Create a simple test image using Intervention Image
    $img = new \Intervention\Image\ImageManager();
    $image = $img->make(__DIR__ . '/public/assets/images/avatar7.png');
    $image->resize(100, 100);
    $image->save('intervention_test_resize.png');
    
    echo "Test completed successfully. Image saved to intervention_test_resize.png\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
?> 