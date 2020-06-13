## A CUID Generator

A CUID **(Collision Resistant Unique Identifier)** is a method of creating a unique identifier that was developed by Eric Elliott. The purpose is to create unique IDs for use in web applications to better support horizontal scaling and sequential lookup performance.

## Install
```bash
composer require calicastle/cuid
```

## Example Usages
```php
use CaliCastle\Cuid;

Cuid::make(); // ckbe1q3gi000001jsfnm9cm81
Cuid::make('u'); // ukbe1qos1000201js74bwas75
```

### Laravel Eloquent

```php
use CaliCastle\Concerns\HasCuid;

// Each user will have an id of something 
// like "ukbe1q3gi000001jsfnm9cm81"
class User extends Model 
{
   use HasCuid;

   /**
    * Get the CUID prefix.
    */
   public static function getCuidPrefix(): string
   {
       return 'u';
   }
}
```
