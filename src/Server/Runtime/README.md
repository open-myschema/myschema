## Server Runtime

An abstraction of PHP runtimes. Uses the [RuntimeDetector](/src/Server/Runtime/RuntimeDetector.php) to resolve the active PHP process manager.

Currently supported runtimes include:
1. [Apache2Handler](/src/Server/Runtime/Provider/Apache2Handler.php)
2. [Command line interface](/src/Server/Runtime/Provider/CliRuntime.php)
