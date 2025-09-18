<!-- 
Interface : contrat (méthodes sans implémentation) que des classes s’engagent à respecter (implements).
Trait : bloc de réutilisation de code (méthodes/propriétés) injecté dans une classe (use).
Namespace : espace de nommage pour organiser et éviter les collisions (namespace App\Domain;).
FQCN : Fully Qualified Class Name — nom complet incluant le namespace (App\Domain\Article).
::class : constante retournant le FQCN de la classe (Article::class).
PSR-4 : convention d’autoloading mappant un préfixe de namespace vers un dossier ("App\\": "src/"). -->