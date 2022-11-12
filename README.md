# PDO Query Builder

```
// Database Config
$config = \App\Utilities\Config::get('database', 'mysql');

// Create Database Connection
$pdoConnection = new \App\Databases\PDODatabaseConnection($config);

// Create PDOQueryBuilder Instance
$queryBuilder = new \App\QueryBuilder\PDOQueryBuilder($pdoConnection->connect());
```

## Quick Access

[Create](#Create)
<br>
[Get All Records](#Get-All-Records)
<br>
[Get](#Get)
<br>
[First](#First)
<br>
[Where Not](#Where-Not)
<br>
[Where Like](#Where-Like)
<br>
[Order By](#Order-By)
<br>
[Find By ID](#Find-By-ID)
<br>
[Find By](#Find-By)
<br>
[Between](#Between)
<br>
[Update](#Update)
<br>
[Delete By ID](#Delete-By-ID)
<br>
[Delete](#Delete)
<br>
[Truncate](#Truncate)

## Create

```
$queryBuilder->table('users')
    ->create([
        'first_name' => 'Armin',
        'last_name' => 'Sadeghian',
        'email' => 'armin@gmail.com',
        'mobile' => '09111111111',
        'age' => '25',
    ]);
```

## Get All Records
```
$queryBuilder->table('users')->all();
```

## Get
```
$queryBuilder->table('users')
    ->where('first_name', 'Armin')
    ->get();
```

## First

```
$queryBuilder->table('users')
    ->where('first_name', 'Armin')
    ->first();
```

## Where Not

```
$queryBuilder->table('users')
    ->whereNot('first_name', 'Armin')
    ->first();
```

## Where Like

```
$queryBuilder->table('users')->whereLike('first_name', 'A%', ['*']);
```

## Order By

```
$queryBuilder->table('users')->orderBy('created_at', 'ASC');
```

## Find By ID

```
$queryBuilder->table('users')->findById(1);
```

## Find By

```
$queryBuilder->table('users')->findBy('first_name', 'Armin');
```

## Between

```
$queryBuilder->table('users')->between('age', [25, 30]);
```

## Update

```
$queryBuilder->table('users')
    ->where('id', '1')
    ->update([
        'email' => 'dummy@gmail.com'
    ]);
```

## Delete By ID

```
$queryBuilder->table('users')->deleteById(1);
```

## Delete

```
$queryBuilder->table('users')
    ->where('first_name', 'Armin')
    ->delete();
```

## Truncate

```
$queryBuilder->table('users')->truncate();
```