<?php

declare(strict_types=1);

// 1 
function validateArticle(array $a): void
{
    if (!isset($a['title']) || !is_string($a['title']) || $a['title'] === '') {
        throw new DomainException("Article invalide: 'title' requis.");
    }
    if (!isset($a['slug']) || !is_string($a['slug']) || $a['slug'] === '') {
        throw new DomainException("Article invalide: 'slug' requis.");
    }
}

// 2 

function loadJson(string $path): array
{
    $raw = @file_get_contents($path);
    if ($raw === false) {
        throw new RuntimeException("Fichier introuvable ou illisible: $path");
    }
    try {
        $data = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException $je) {
        throw new RuntimeException("JSON invalide: $path", previous: $je);
    }
    if (!is_array($data)) {
        throw new UnexpectedValueException("Le JSON doit contenir un tableau racine.");
    }
    return $data;
}

// 3 

function main(array $argv): int
{
    $path = $argv[1] ?? 'articles.input.json';
    $articles = loadJson($path);
    foreach ($articles as $i => $a) {
        validateArticle($a);
    }
    echo "[OK] $path: " . count($articles) . " article(s) valides." . PHP_EOL;
    return 0;
}


// 4

try {
    exit(main($argv));
} catch (Throwable $e) {
    fwrite(STDERR, "[ERR] " . $e->getMessage() . PHP_EOL);
    if ($e->getPrevious()) {
        fwrite(STDERR, "Cause: " . get_class($e->getPrevious()) . " — " . $e->getPrevious()->getMessage() . PHP_EOL);
    }
    exit(1);
}


?>


<!-- Key points  -->

Exception: Used for recoverable conditions, part of the traditional exception handling system.
Error: Represents critical issues (e.g., runtime errors), catchable since PHP 7.
Throwable: The common interface for both, allowing unified error handling in try-catch blocks.

Exceptions are used at the right granularity:

DomainException: for invalid article data,

RuntimeException: for file/JSON issues,

UnexpectedValueException: for unexpected structure.

Safe JSON decoding with JSON_THROW_ON_ERROR.

Propagation + rethrow: You catch JsonException, add context, then rethrow.

Top-level catch with Throwable: no uncontrolled crash, every error gets reported.

Exit codes:

0 → success,

1 → failure.

This is exactly the recommended structure for robust CLI (command line interface) PHP scripts.


A command line interface (CLI) is a text-based interface
where you can input commands that interact with a computer's operating system.
The CLI operates with the help of the default shell,
which is between the operating system and the user.

<!-- ********************************** -->
Standard Output (STDOUT) and Standard Error (STDERR) in CLI Applications
In a command-line interface (CLI), programs communicate with the user or other processes through two primary output streams:

Standard Output (STDOUT):
Purpose: Used for the primary output of a program, such as successful results, data, or informational messages intended for the user or for piping to another process.
Usage in CLI: In PHP, echo, print, or fwrite(STDOUT, ...) write to STDOUT. This is where you typically display the expected results of a command.
Example in your script: echo "[OK] $path: " . count($articles) . " article(s) valides." . PHP_EOL;
This line writes a success message to STDOUT, indicating that the JSON file was processed and how many articles were validated. The use of PHP_EOL ensures platform-independent line endings, making the output clean and consistent across operating systems.


Standard Error (STDERR):
Purpose: Used for error messages, warnings, or diagnostic information that should be separated from the primary output. This allows errors to be redirected independently (e.g., to a log file) without mixing with STDOUT.
Usage in CLI: In PHP, fwrite(STDERR, ...) writes to STDERR. This is ideal for error reporting, as it ensures errors are not confused with regular output, especially when STDOUT is piped to another command.
Example in your script: 

fwrite(STDERR, "[ERR] " . $e->getMessage() . PHP_EOL);
if ($e->getPrevious()) {
    fwrite(STDERR, "Cause: " . get_class($e->getPrevious()) . " — " . $e->getPrevious()->getMessage() . PHP_EOL);
}

These lines write error messages to STDERR when an exception occurs, including the main error message and, if applicable, the cause (previous exception). 


Key Differences:
STDOUT: For expected, successful output (e.g., results, confirmations).
STDERR: For errors, warnings, or diagnostics that indicate something went wrongThis separation ensures that error messages don’t interfere with STDOUT, which is reserved for successful operation output.