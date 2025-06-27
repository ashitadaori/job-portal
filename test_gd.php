<?php
// Test if GD extension is loaded
if (extension_loaded('gd')) {
    echo "GD is loaded!\n";
    
    // Get GD info
    $gd_info = gd_info();
    echo "GD Version: " . $gd_info['GD Version'] . "\n";
    
    // Try to create a simple image
    $im = @imagecreate(200, 200);
    if ($im) {
        $background_color = imagecolorallocate($im, 255, 255, 255);
        $text_color = imagecolorallocate($im, 0, 0, 0);
        imagestring($im, 5, 50, 100, 'GD Works!', $text_color);
        
        // Save to file
        if (imagepng($im, 'gd_test.png')) {
            echo "Image saved successfully to gd_test.png\n";
        } else {
            echo "Failed to save image\n";
        }
        imagedestroy($im);
    } else {
        echo "Failed to create image\n";
    }
} else {
    echo "GD is NOT loaded!\n";
}
?> 