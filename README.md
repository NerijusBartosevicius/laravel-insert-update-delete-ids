# Laravel data handling after insert, delete and update

## Instalation

To install via composer:

```sh
composer require neriba/laravel-insert-update-delete-ids
```

## What It Does

This package allows you to get data after event with database.

`insertGetIds method inserts and returns array of data. By default returning array with id value, but you can change to any existing fields in the table.`

```php
// Inserting and returning every inserted record id. |id|
DB::table('roles')->insertGetIds(
    [
        ['name' => 'Role 1', 'guard_name' => 'Guard 1'],
        ['name' => 'Role 2', 'Guard 1'],
    ]
);

// Inserting and returning every inserted record id with alias and name. |role_id|name|
DB::table('roles')->insertGetIds(
    [
        ['name' => 'Role 1', 'guard_name' => 'Guard 1'],
        ['name' => 'Role 2', 'Guard 1'],
    ],
    ['id as role_id', 'name']
);

// All fields of each record are inserted and returned. |id|name|guard_name|
DB::table('roles')->insertGetIds(
    [
        ['name' => 'Role 1', 'guard_name' => 'Guard 1'],
        ['name' => 'Role 2', 'Guard 1'],
    ],
    ['*']
);
```

`updateGetIds method updates and returns array of data. By default returning array with id value, but you can change to any existing fields in the table.`

```php
// Updating and returning every updated record id. |id|
DB::table('roles')->where('id', '>', 3645)->updateGetIds(['name' => 'updated']);

// Updating and returning every updated record id with alias role_id and name. |role_id|name|
DB::table('roles')->where('id', '>', 3645)->updateGetIds(['name' => 'updated'],['id as role_id', 'name']);

// Updating and returning every updated record all columns. |id|name|guard_name|
DB::table('roles')->where('id', '>', 3645)->updateGetIds(['name' => 'updated'],['*']);
```

`deleteGetIds method deletes and returns array of data. By default returning array with id value, but you can change to any existing fields in the table.`

```php
// Deleting and returning every deleted record id. |id|
DB::table('roles')->deleteGetIds(3657);

// Deleting and returning every deleted record id with alias role_id and name. |role_id|name|
DB::table('roles')->deleteGetIds(3657,['id as role_id', 'name']);

// Deleting and returning every deleted record all columns. |id|name|guard_name|
DB::table('roles')->deleteGetIds(3657,['*']);
```

# License

The MIT License (MIT). Please see [License File](LICENSE) for more information.