<?php
function buildArticle(array $row): array {
    $row['title']     ??= 'Sans titre';
    $row['author']    ??= 'N/A';
    $row['published'] ??= true;

    $title   = trim((string)$row['title']);
    $excerpt = isset($row['excerpt']) ? trim((string)$row['excerpt']) : null;
    $excerpt = ($excerpt === '') ? null : $excerpt;

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
    'title' => 'PHP 8 en pratique',
    'excerpt' => '',
    'views' => 300,
    'published' => 1,
    'author' => 'Yassine'
];

// Call the function and store the result
$result = buildArticle($data);

// Send the result to the JavaScript console
echo "<script>console.log(" . json_encode($result) . ");</script>";
?>
