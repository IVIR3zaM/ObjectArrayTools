# ObjectArrayTools

[![Build Status](https://travis-ci.org/IVIR3zaM/ObjectArrayTools.svg?branch=master)](https://travis-ci.org/IVIR3zaM/ObjectArrayTools) [![Code Climate](https://codeclimate.com/github/IVIR3zaM/ObjectArrayTools/badges/gpa.svg)](https://codeclimate.com/github/IVIR3zaM/ObjectArrayTools) [![Issue Count](https://codeclimate.com/github/IVIR3zaM/ObjectArrayTools/badges/issue_count.svg)](https://codeclimate.com/github/IVIR3zaM/ObjectArrayTools) [![Test Coverage](https://codeclimate.com/github/IVIR3zaM/ObjectArrayTools/badges/coverage.svg)](https://codeclimate.com/github/IVIR3zaM/ObjectArrayTools/coverage)

this library helps you to have an object as an active array with hooks for updating, inserting, deleting, sanitizing and filtering elements.

## Technical Details
this library is different from builtin [ArrayObject](http://php.net/manual/en/class.arrayobject.php) class in PHP, and this deference is that this library is based on traits so you can use it in your classes that already extending some other classes and other differences is about hooks that help you to implement Observer Pattern for your ObjectArray, and also you have many methods equivalents to php native [Array Functions](http://php.net/manual/en/ref.array.php)

## Installation

The recommended way to install ObjectArrayTools is through
[Composer](http://getcomposer.org).

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
```

Next, run the Composer command to install the latest stable version of Guzzle:

```bash
php composer.phar require ivir3zam/object-array-tools
```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```

You can then later update ObjectArrayTools using composer:

 ```bash
composer.phar update
 ```

## Examples
this library helps you to use your objects as an storage and it don't affect your current object functionality

### Basic Array Usage
this example only show you the basic usage of this library
```php
use IVIR3aM\ObjectArrayTools\AbstractActiveArray;

class ActiveArray extends AbstractActiveArray {}

$object = new ActiveArray(['Hello', 'World']);

echo 'Number of elements: ' . count($object) . "\n";

foreach ($object as $string) {
    echo $string  . ' ';
}

echo "\n";

$object[1] = 'Visitor';

foreach ($object as $string) {
    echo $string  . ' ';
}

echo "\n";

/*
OUTPUT:

Number of elements: 2
Hello World
Hello Visitor

/*
```

### Save input in database and show output in browser
in this example we try to have an array that when you write data in it, filter input for database and when you read from it, sanitize for html output
```php
use IVIR3aM\ObjectArrayTools\AbstractActiveArray;

class DatabaseRecord extends AbstractActiveArray
{
    protected function sanitizeInputHook($offset, $data)
    {
        // some strong operation of sanitizing $data needed, this is only a sample
        return mysqli_real_escape_string($link, $data);
    }

    protected function sanitizeOutputHook($offset, $data)
    {
        return htmlspecialchars($data);
    }

    protected function updateHook($offset, $data, $oldData)
    {
        // some strong operation of updating database process needed, this is only a sample
        mysqli_query($link, "UPDATE SomeTable SET `Data` = '{$data}' WHERE `ID` = " . intval($offset));
    }

    protected function removeHook($offset, $oldData)
    {
        // some strong operation of updating database process needed, this is only a sample
        mysqli_query($link, "DELETE FROM SomeTable WHERE `ID` = " . intval($offset));
    }

    protected function insertHook($offset, $data)
    {
        mysqli_query($link, "INSERT INTO SomeTable SET `ID` = " . intval($offset) . ", `Data` = '{$data}'");
    }
}

$db = new DatabaseRecord();
$db[1] = "Lorem Ipsum <some>script</some>'; DELETE FROM SomeTable";
// mysqli_query($link, "INSERT INTO SomeTable SET `ID` = 1, `Data` = 'Lorem Ipsum <some>script</some>\'; DELETE FROM SomeTable'");

echo $db[1];
// Lorem Ipsum &lt;some&gt;script&lt;/some&gt;'; DELETE FROM SomeTable
```

### Sorting an array
you can see sorting functionality of this library here. there is a complete list of supported functions in IVIR3aM\ObjectArrayTools\AbstractActiveArray class documentation

```php
use IVIR3aM\ObjectArrayTools\AbstractActiveArray;

class ActiveArray extends AbstractActiveArray {}

$object = new ActiveArray(['How', 'Are', 'You']);

print_r($object->getData());
/*
Array
(
    [0] => How
    [1] => Are
    [2] => You
)
*/

$object->sort();

print_r($object->getData());
/*
Array
(
    [0] => Are
    [1] => How
    [2] => You
)
*/
```