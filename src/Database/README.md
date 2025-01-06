## Database Module

#### Usage
This module leverages PHP's PDO class to handle connecting to and querying of databases. Only PostgreSQL is supported.

The main [Connection](/src/Database/Connection.php) class defines 2 methods:
1. `read(string $query, array $params = []): array`
2. `write(string $query, array $params = []): bool`
