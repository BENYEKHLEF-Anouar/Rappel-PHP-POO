<?php

declare(strict_types=1);

class Article
{

    // readonly → une fois initialisé dans le constructeur, on ne peut plus modifier id.
    // private → protège l’état (title, slug, tags) → on doit passer par les méthodes de l’API publique.
    // static $count → variable commune à toutes les instances → sert de compteur d’articles.

    public readonly int $id;          // immutable after construction
    private string $title;            // encapsulated
    private string $slug;             // derived
    private array $tags = [];         // encapsulated

    // private static int $count = 0;    // partagé
    //step 4 : delete the $count and replace it with ArticleRepository

    public function __construct(int $id, string $title, array $tags = [])
    {
        if ($id <= 0) throw new InvalidArgumentException("ID must be greater than 0.");
        $this->id = $id;
        $this->setTitle($title);
        $this->tags = $tags;
    }

    /** Factory with LSB: returns the correct subclass if called from it */

    // Permet de créer rapidement un article avec juste id et title.
    // Utilise static (Late Static Binding) → si appelée depuis une sous-classe, c’est la sous-classe qui sera instanciée.

    public static function fromTitle(int $id, string $title): static
    {
        return new static($id, $title);
    }

    // Getters (minimal public API) → lecture sécurisée de l’état.
    public function title(): string
    {
        return $this->title;
    }
    public function slug(): string
    {
        return $this->slug;
    }
    public function tags(): array
    {
        return $this->tags;
    }

    /** Setter encapsulating validation + slug update */
    public function setTitle(string $title): void
    {
        $title = trim($title);
        if ($title === '')
            throw new InvalidArgumentException("Title is required.");

        $this->title = $title;
        $this->slug = static::slugify($title);
    }

    public function addTag(string $tag): void
    {
        $t = trim($tag);
        if ($t === '') throw new InvalidArgumentException("Tag cannot be empty.");
        $this->tags[] = $t;
    }

    /** Export to array for JSON preparation */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'tags' => $this->tags,
        ];
    }

    // Retourne le nombre total d’articles créés.
    // public static function count(): int { return self::$count; }


    /** Protected: can be overridden in subclass */
    protected static function slugify(string $value): string
    {
        $s = strtolower($value);
        $s = preg_replace('/[^a-z0-9]+/i', '-', $s) ?? '';
        return trim($s, '-');
    }
}

/** Subclass: specialization via protected and LSB */
class FeaturedArticle extends Article
{
    protected static function slugify(string $value): string
    {
        return 'featured-' . parent::slugify($value);
    }
}

/** Dépôt en mémoire (Étape 4) */
class ArticleRepository
{
    /** @var array<string,Article> indexé par slug */

    // A static array that holds all saved Article objects.
    // The array is keyed by slug, so each slug must be unique.
    // This simulates an in-memory database.
    private static array $articles = [];

    // When saving an Article, it checks if another article with the same slug already exists.
    // If yes → throws a DomainException.
    // If no → saves it into the repository.
    public static function save(Article $a): void
    {
        $slug = $a->slug();
        if (isset(self::$articles[$slug])) {
            throw new DomainException("Slug déjà existant : $slug");
        }
        self::$articles[$slug] = $a;
    }

    // Returns how many articles are currently stored.
    // Replaces the old Article::$count, which was global mutable state inside the entity.
    public static function count(): int
    {
        return count(self::$articles);
    }

    // Returns all stored Article objects as a numeric array (values only, slugs removed from keys).
    // Useful for iterating, exporting to JSON, etc.
    public static function all(): array
    {
        return array_values(self::$articles);
    }
}

// Demo

// Étape 1 — Test readonly (doit planter si décommenté)
// $a = new Article(1, "Test readnly");
// $a->id = 99; // Erreur attendue : Cannot modify readonly property

// 
// $a = Article::fromTitle(1, 'Test readonly');
// $a->id = 99; // → devrait lancer une erreur : Cannot modify readonly property

// Étape 2 + 3 — Générer 3 articles et les afficher
$a1 = Article::fromTitle(1, "Encapsulation & visibilité en PHP");
$a2 = FeaturedArticle::fromTitle(2, "Lire moins, comprendre plus");
$a3 = Article::fromTitle(3, "Programmation orientée objet");

$a2->addTag("best");
$a3->addTag("oop");

// Sauvegarde dans le dépôt (contrainte unicité slug)
ArticleRepository::save($a1);
ArticleRepository::save($a2);
ArticleRepository::save($a3);

// Affichage du tableau (préparation JSON)
print_r(array_map(fn($a) => $a->toArray(), ArticleRepository::all()));

// Affichage du nombre d’articles
echo "Total articles : " . ArticleRepository::count() . PHP_EOL;

// Étape 4 — Test contrainte unicité
// ArticleRepository::save(Article::fromTitle(4, "Lire moins, comprendre plus")); // Erreur DomainException
