<?php
declare(strict_types=1);

$input = [
  'title'     => 'PHP 8 en pratique',
  'excerpt'   => '',
  'views'     => '300',
  // 'published' absent
  'author'    => 'Yassine'
];

// 1
function strOrNull(?string $s): ?string {
    $s = $s !== null ? trim($s) : null;
    return $s === '' ? null : $s;
}

function intOrZero(int|string|null $v): int {
    return max(0, (int)($v ?? 0));
}


// 2
$normalized = [
  'title'     => trim((string)($input['title'] ?? 'Sans titre')),
  'excerpt'   => strOrNull($input['excerpt'] ?? null),
  'views'     => intOrZero($input['views'] ?? null),
  'published' => $input['published'] ?? true, // défaut si non défini
  'author'    => trim((string)($input['author'] ?? 'N/A')),
];

print_r($normalized);
/*
Array
(
  [title] => PHP 8 en pratique
  [excerpt] =>
  [views] => 300
  [published] => 1
  [author] => Yassine
)
*/

// 3
$defaults = [
  'per_page' => 10,
  'sort'     => 'created_desc',
];

$userQuery = ['per_page' => null]; // simulateur d'entrée
$userQuery['per_page'] ??= $defaults['per_page']; // 10
$userQuery['sort']     ??= $defaults['sort'];     // 'created_desc'

?>



<!-- Livrable -->
<?php
// declare(strict_types=1);
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

// print_r($data);

// Send the result to the JavaScript console
echo "<script>console.log(" . json_encode($result) . ");</script>";
?>


<!-- php -S localhost:8000 -->
