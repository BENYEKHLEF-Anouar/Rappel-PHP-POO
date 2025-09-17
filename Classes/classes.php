<?php
declare(strict_types=1);

$raw = [
  ['id'=>1,'title'=>'Intro Laravel','excerpt'=>null,'views'=>120],
  ['id'=>2,'title'=>'PHP 8 en pratique','excerpt'=>'Tour des nouveautés','views'=>300],
  ['id'=>3,'title'=>'Composer & Autoload','excerpt'=>null,'views'=>90],
];

// 
class Article {
    public function __construct(
        public int $id,
        public string $title,
        public ?string $excerpt = null,
        public int $views = 0,
    ) {}

    public function slug(): string {
        $s = strtolower($this->title);
        $s = preg_replace('/[^a-z0-9]+/i', '-', $s);
        return trim($s, '-');
    }

    public function toArray(): array {
        return [
            'id'      => $this->id,
            'title'   => $this->title,
            'excerpt' => $this->excerpt,
            'views'   => $this->views,
            'slug'    => $this->slug(),
        ];
    }
}

// 
class ArticleFactory {
    public static function fromArray(array $a): Article {
        // Valeurs par défaut + contrôles simples
        $id      = (int)($a['id'] ?? 0);
        $title   = trim((string)($a['title'] ?? 'Sans titre'));
        $excerpt = isset($a['excerpt']) ? (string)$a['excerpt'] : null;
        $views   = (int)($a['views'] ?? 0);

        return new Article($id, $title, $excerpt, $views);
    }
}


// 
$articles = array_map(
  fn(array $a) => ArticleFactory::fromArray($a),
  $raw
);

// Afficher un mini-rapport
foreach ($articles as $art) {
    $data = $art->toArray();
    echo "- {$data['title']} ({$data['views']} vues) — slug: {$data['slug']}\n";
}









// <--- Livrable ---> 

// declare(strict_types=1);

// Class User
class User {
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public ?string $bio = null,
        public int $articlesCount = 0,
    ) {}

    // Method to return initials of the user name
    public function initials(): string {
        $parts = preg_split('/\s+/', trim($this->name));
        $letters = array_map(fn($p) => strtoupper(substr($p, 0, 1)), $parts);
        return implode('', $letters);
    }

    // Reminder: PHP lab from Abdelhay

    // Method to convert the object into an associative array
    public function toArray(): array {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'email'         => $this->email,
            'bio'           => $this->bio,
            'articlesCount' => $this->articlesCount,
            'initials'      => $this->initials(),
        ];
    }
}


// Factory: UserFactory
class UserFactory {
    public static function fromArray(array $u): User {
        $id    = max(1, (int)($u['id'] ?? 0)); // ensure id >= 1
        $name  = trim((string)($u['name'] ?? 'Inconnu'));
        $email = trim((string)($u['email'] ?? ''));
        if ($email === '') {
            throw new InvalidArgumentException('email requis');
        }
        $bio   = isset($u['bio']) ? (string)$u['bio'] : null;
        $count = (int)($u['articlesCount'] ?? 0);

        return new User($id, $name, $email, $bio, $count);
    }
}


// Input dataset

$authors = [
    ['id'=>1,'name'=>'Amina Zouhair','email'=>'amina@example.com','bio'=>'Laravel fan','articlesCount'=>5],
    ['id'=>2,'name'=>'Yassine Mallouli','email'=>'yassine@example.com','bio'=>null,'articlesCount'=>3],
    ['id'=>3,'name'=>'Fatima Benali','email'=>'fatima@example.com','articlesCount'=>7],
];


// Convert raw data → objects
$users = array_map(
    fn(array $u) => UserFactory::fromArray($u),
    $authors
);


// Display report

foreach ($users as $user) {
    $data = $user->toArray();
    echo "- {$data['name']} ({$data['initials']}) — Articles: {$data['articlesCount']}\n";
}