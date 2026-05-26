<?php
require_once __DIR__ . '/includes/products-data.php';

$imagesDir = __DIR__ . '/images/products/';

// Create directory if it doesn't exist
if (!is_dir($imagesDir)) {
    mkdir($imagesDir, 0777, true);
}

foreach ($products as $id => $product) {
    // Get the path relative to the script
    $filePath = __DIR__ . '/' . $product['image'];
    
    // Make sure the directory for the file exists
    $fileDir = dirname($filePath);
    if (!is_dir($fileDir)) {
        mkdir($fileDir, 0777, true);
    }

// Generate a valid JPEG placeholder
    if (extension_loaded('gd')) {
        $im = imagecreatetruecolor(400, 500);
        $bg = imagecolorallocate($im, 245, 240, 234); // Elegant light beige (#f5f0ea)
        $gold = imagecolorallocate($im, 212, 175, 55); // Gold (#d4af37)
        $dark = imagecolorallocate($im, 26, 26, 26);
        
        imagefill($im, 0, 0, $bg);
        
        // Draw a minimalist chain (a simple curve / arc)
        // Center x=200, y=-50, width=300, height=450
        imagearc($im, 200, -50, 300, 450, 0, 180, $gold);
        
        // Draw pendant
        imagefilledellipse($im, 200, 250, 25, 25, $gold);
        imagefilledellipse($im, 200, 250, 15, 15, $bg);
        
        // Draw small gold diamond shape
        $points = [
            200, 240, // top
            205, 250, // right
            200, 260, // bottom
            195, 250  // left
        ];
        imagefilledpolygon($im, $points, 4, $gold);
        
        // Draw product name text
        $text = $product['name'];
        $fontSize = 3;
        $textWidth = imagefontwidth($fontSize) * strlen($text);
        $textX = (400 - $textWidth) / 2;
        imagestring($im, $fontSize, $textX, 320, $text, $dark);
        
        imagejpeg($im, $filePath, 90);
        imagedestroy($im);
    } else {
        // Fallback to basic file content
        $svgContent = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 250" width="100%" height="100%">
    <!-- Chain -->
    <path d="M 40 10 Q 100 220 160 10" fill="none" stroke="#D4AF37" stroke-width="1.5" stroke-dasharray="4 2" />
    <!-- Pendant -->
    <circle cx="100" cy="115" r="10" fill="#F4E8C1" stroke="#D4AF37" stroke-width="1.5"/>
    <path d="M 100 100 L 105 110 L 95 110 Z" fill="#D4AF37" />
</svg>
SVG;
        file_put_contents($filePath, $svgContent);
    }
}

echo "Successfully generated " . count($products) . " product images in the /images/products/ folder!";