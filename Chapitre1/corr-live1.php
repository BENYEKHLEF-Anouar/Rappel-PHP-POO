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

$testCases = [
    // Test Case 1: Data kamla m3a values valid
    [
        'title'     => '  Test Article  ', // string m3a spaces zaydin
        'excerpt'   => '  Summary  ',      // string m3a spaces
        'views'     => '500',             // number k string
        'published' => false,             // bool
        'author'    => '  John  '         // string m3a spaces
    ],
    // Test Case 2: Keys missing w values null
    [
        'views'     => null,              // views null
        'excerpt'   => ''                 // excerpt khawi
    ],
    // Test Case 3: Values zero w strings khawya
    [
        'title'     => '',                // string khawi
        'views'     => '0',               // zero k string
        'author'    => '   '              // string fih spaces b7al
    ],
    // Test Case 4: Extra case m3a values ma-valid-sh
    [
        'views'     => '-10',             // number salbi
        'excerpt'   => '   ',             // string fih spaces b7al
        'published' => 'yes'              // value ma-bool-sh
    ]
];

// Kan-loopiw 3la kol test case w kan-applyiw l-function w n-printiw l-result
foreach ($testCases as $index => $case) {
    echo "Test Case " . ($index + 1) . ":\n"; // Kan-printiw number dial l-case
    print_r(buildArticle($case));             // Kan-printiw l-output dial l-function
    echo "\n";                                
}
