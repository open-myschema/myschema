## Server Runtime

An abstraction of PHP runtimes. Uses the [RuntimeDetector](/RuntimeDetector.php) to resolve the active PHP process manager.

Currently supported runtimes include:
1. [Apache2Handler](/Provider/Apache2Handler.php)
2. [Command line interface](/Provider/CliRuntime.php)
