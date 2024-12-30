## Database Module

#### Usage
This module leverages the `doctrine/dbal` package to handle connecting to and querying of databases. No ORM package is provided, and as such, this package requires SQL queries to be written by hand for all supported databases. The default supported database is SQLite. An additional adapter is provided for PostgreSQL.

The primary interface defined here is [DatabaseInterface](/src/Database/DatabaseInterface.php) which defines 2 methods:
1. `read(string $query, array $params = []): array`
2. `write(string $query, array $params = []): bool`

A provided implementation of this interface is [SQLDatabaseAdapter](/src/Database/Adapter/SQLDatabaseAdapter.php), which accepts a `\Doctrine\DBAL\Connection` instance wrapped by the [`\MySchema\Database\Connection`](/src/Database/Connection.php) class.

The provided adapters [SQLiteAdapter](/src/Database/Adapter/SQLiteAdapter.php) and [PostgresAdapter](/src/Database/Adapter/PostgresAdapter.php) extend [SQLDatabaseAdapter](/src/Database/Adapter/SQLDatabaseAdapter.php) and are meant to signal to the calling code which database server is being used in a connection. From there, appropriate SQL queries are written for the relevant database. 
