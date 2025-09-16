<?php

declare(strict_types=1);
function buildArticle(array $row): array {
    // affectation conditionnelle
    $row['title']     ??= 'Sans titre'; 
    $row['author']    ??= 'N/A';
    $row['published'] ??= true;

    $title   = trim((string)$row['title']);
    // opérateur ternaire
    $excerpt = isset($row['excerpt']) ? trim((string)$row['excerpt']) : null;
    $excerpt = ($excerpt === '') ? null : $excerpt;

    // coalescence nulle 
    $views   = (int)($row['views'] ?? 0);
    $views   = max(0, $views);

    return [
        'title'     => $title,
        'excerpt'   => $excerpt,
        'views'     => $views,
        'published' => (bool)$row['published'],
        'author'    => trim((string)$row['author']),
    ];
}

// Example data to pass into the function
$data = [
    'title' => 'Harry Potter',
    'excerpt' => '',
    'views' => 3000000,
    'published' => 7,
    'author' => 'Rowling'
];

// Call the function and store the result
$result = buildArticle($data);

// Send the result to the JavaScript console
echo "<script>console.log(" . json_encode($result) . ");</script>";
?>


<!-- php -S localhost:8300 -->