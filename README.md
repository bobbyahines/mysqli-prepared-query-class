# mysqli-prepared-query-class
A drop in class for prepared mysqli queries to MySQL and MariaDB RDBMs.

# Queries

**Example:**

```php
$sql = 'SELECT * FROM my_database LIMIT ?;';
$types = 'i';
$params = [10];

$db = new Database($dbName)
$query = $db->preparedQuery($sql, $types, $params, $dbName);
```
