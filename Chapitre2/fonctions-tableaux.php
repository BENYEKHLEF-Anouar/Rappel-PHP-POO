<?php
declare(strict_types=1);

$articles = [
  ['id'=>1,'title'=>'Intro Laravel','category'=>'php','views'=>120,'author'=>'Amina','published'=>true,  'tags'=>['php','laravel']],
  ['id'=>2,'title'=>'PHP 8 en pratique','category'=>'php','views'=>300,'author'=>'Yassine','published'=>true,  'tags'=>['php']],
  ['id'=>3,'title'=>'Composer & Autoload','category'=>'outils','views'=>90,'author'=>'Amina','published'=>false, 'tags'=>['composer','php']],
  ['id'=>4,'title'=>'Validation FormRequest','category'=>'laravel','views'=>210,'author'=>'Sara','published'=>true,  'tags'=>['laravel','validation']],
];

// 1
function slugify(string $title): string {
    $slug = strtolower($title);
    $slug = preg_replace('/[^a-z0-9]+/i', '-', $slug);
    return trim($slug, '-');
}

// 2
$published = array_values(
  array_filter($articles, fn(array $a) => $a['published'] ?? false)
);

// Intro Laravel            → intro-laravel
// PHP 8 en pratique        → php-8-en-pratique
// Composer & Autoload      → composer-autoload
// Validation FormRequest   → validation-formrequest


// [
//   [
//     'id' => 1,
//     'title' => 'Intro Laravel',
//     'category' => 'php',
//     'views' => 120,
//     'author' => 'Amina',
//     'published' => true,
//     'tags' => ['php','laravel']
//   ],
//   [
//     'id' => 2,
//     'title' => 'PHP 8 en pratique',
//     'category' => 'php',
//     'views' => 300,
//     'author' => 'Yassine',
//     'published' => true,
//     'tags' => ['php']
//   ],
//   [
//     'id' => 4,
//     'title' => 'Validation FormRequest',
//     'category' => 'laravel',
//     'views' => 210,
//     'author' => 'Sara',
//     'published' => true,
//     'tags' => ['laravel','validation']
//   ]
// ]




// 3
$light = array_map(
  fn(array $a) => [
    'id'    => $a['id'],
    'title' => $a['title'],
    'slug'  => slugify($a['title']),
    'views' => $a['views'],
  ],
  $published
);

// [
//   [
//     'id' => 1,
//     'title' => 'Intro Laravel',
//     'slug' => 'intro-laravel',
//     'views' => 120
//   ],
//   [
//     'id' => 2,
//     'title' => 'PHP 8 en pratique',
//     'slug' => 'php-8-en-pratique',
//     'views' => 300
//   ],
//   [
//     'id' => 4,
//     'title' => 'Validation FormRequest',
//     'slug' => 'validation-formrequest',
//     'views' => 210
//   ]
// ]





// 4
$top = $light;
usort($top, fn($a, $b) => $b['views'] <=> $a['views']);
$top3 = array_slice($top, 0, 3);

// [
//   [
//     'id' => 2,
//     'title' => 'PHP 8 en pratique',
//     'slug' => 'php-8-en-pratique',
//     'views' => 300
//   ],
//   [
//     'id' => 4,
//     'title' => 'Validation FormRequest',
//     'slug' => 'validation-formrequest',
//     'views' => 210
//   ],
//   [
//     'id' => 1,
//     'title' => 'Intro Laravel',
//     'slug' => 'intro-laravel',
//     'views' => 120
//   ]
// ]


// 5
$byAuthor = array_reduce(
  $published,
  function(array $acc, array $a): array {
      $author = $a['author'];
      $acc[$author] = ($acc[$author] ?? 0) + 1;
      return $acc;
  },
  []
);


// [
//   'Amina'   => 1,
//   'Yassine' => 1,
//   'Sara'    => 1
// ]



// 6
$allTags = array_merge(...array_map(fn($a) => $a['tags'], $published));

$tagFreq = array_reduce(
  $allTags,
  function(array $acc, string $tag): array {
      $acc[$tag] = ($acc[$tag] ?? 0) + 1;
      return $acc;
  },
  []
);

// $allTags = ['php','laravel','php','laravel','validation'];

// Array
// (
//     [php] => 2
//     [laravel] => 2
//     [validation] => 1
// 

// print_r($tagFreq);



//7
echo "Top 3 (views):\n";
foreach ($top3 as $a) {
  echo "- {$a['title']} ({$a['views']} vues) — {$a['slug']}\n";
}

echo "\nPar auteur:\n";
foreach ($byAuthor as $author => $count) {
  echo "- $author: $count article(s)\n";
}

echo "\nTags:\n";
foreach ($tagFreq as $tag => $count) {
  echo "- $tag: $count\n";
}


// Top 3 (views):
// - PHP 8 en pratique (300 vues) — php-8-en-pratique
// - Validation FormRequest (210 vues) — validation-formrequest
// - Intro Laravel (120 vues) — intro-laravel

// --------

// [
//   'Amina'   => 1,
//   'Yassine' => 1,
//   'Sara'    => 1
// ]

// Par auteur:
// - Amina: 1 article(s)
// - Yassine: 1 article(s)
// - Sara: 1 article(s)

// -------

// [
//   'php' => 2,
//   'laravel' => 2,
//   'validation' => 1
// ]

// Tags:
// - php: 2
// - laravel: 2
// - validation: 1

// ---------


// <!-- Livrable -->

// declare(strict_types=1);
$published = array_values(array_filter($articles, fn($a) => $a['published'] ?? false));

$normalized = array_map(
  fn($a) => [
    'id'       => $a['id'],
    'slug'     => slugify($a['title']),
    'views'    => $a['views'],
    'author'   => $a['author'],
    'category' => $a['category'],
  ],
  $published
);

usort($normalized, fn($x, $y) => $y['views'] <=> $x['views']);

$summary = array_reduce(
  $published,
  function(array $acc, array $a): array {
      $acc['count']      = ($acc['count'] ?? 0) + 1;
      $acc['views_sum']  = ($acc['views_sum'] ?? 0) + $a['views'];
      $cat = $a['category'];
      $acc['by_category'][$cat] = ($acc['by_category'][$cat] ?? 0) + 1;
      return $acc;
  },
  ['count'=>0, 'views_sum'=>0, 'by_category'=>[]]
);

print_r($normalized);
print_r($summary);

// $normalized
// Array
// (
//     [0] => Array
//         (
//             [id] => 3
//             [slug] => third-article
//             [views] => 300
//             [author] => Charlie
//             [category] => Tech
//         )
//     [1] => Array
//         (
//             [id] => 4
//             [slug] => fourth-article
//             [views] => 200
//             [author] => Dave
//             [category] => Lifestyle
//         )
//     [2] => Array
//         (
//             [id] => 1
//             [slug] => first-article
//             [views] => 100
//             [author] => Alice
//             [category] => Tech
//         )
// )


// $summary
// Array
// (
//     [count] => 3
//     [views_sum] => 600
//     [by_category] => Array
//         (
//             [Tech] => 2
//             [Lifestyle] => 1
//         )
// )