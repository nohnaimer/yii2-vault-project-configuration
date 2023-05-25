yii2-vault-project-configuration
==========================

[![Latest Stable Version](https://poser.pugx.org/nohnaimer/yii2-vault-project-configuration/v/stable)](https://packagist.org/packages/nohnaimer/yii2-vault-project-configuration)
[![License](https://poser.pugx.org/nohnaimer/yii2-vault-project-configuration/license)](https://packagist.org/packages/nohnaimer/yii2-vault-project-configuration)
[![Total Downloads](https://poser.pugx.org/nohnaimer/yii2-vault-project-configuration/downloads)](https://packagist.org/packages/nohnaimer/yii2-vault-project-configuration)

This extension helps you to easily store and retrieve settings for your project.


## Installation

The preferred way to install this extension through [composer](http://getcomposer.org/download/).

You can set the console

```
~$ composer require "nohnaimer/yii2-vault-project-configuration" --prefer-dist
```

or add

```
"require": {
    "nohnaimer/yii2-vault-project-configuration": "1.0.*"
}
```

in ```require``` section in `composer.json` file.

## Configuration

For store php-fpm environment variables from system (macOS, Linux, Unix) need to uncomment clear_env = no string in /etc/php/php-fpm.d/www.conf 

Need add environment variables:
```yaml
VAULT_URL=https://vault.url/
VAULT_TOKEN=token
VAULT_KV_PATH=/kv
```

docker-compose example:
```yaml
...
php:
  image: php:latest
  container_name: php
  restart: on-failure
  working_dir: /var/www
  environment:
    VAULT_URL: https://127:0:0:1:8200/
    VAULT_TOKEN: hvs.hrpvk3rEpD2HaHckeb976Ppw
  volumes:
    - .:/var/www:cached
  depends_on:
    - postgres
...
```

And need to init key value storage in Hashicorp Vault use api or web gui with VAULT_KV_PATH string.

## Using

```php
$db_name = config('/db_name', 'site-db-name');
$db_host = config('/db_host', 'localhost');

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => "mysql:host={$db_host};dbname={$db_name}",
            'username' => config('/db_username', 'root'),
            'password' => config('/db_password', '****'),
            'enableSchemaCache' => true,
            'charset' => 'utf8',
        ],
    ],
];
```

## Management

Use yii2-setting classes to add or delete data from vault use yii2 migrations.

### Use yii2 migrations

```php
class m221103_161325_vault_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $client = new Client([
            'url' => 'url',
            'token' => 'token',
            'kvPath' => '/kv',
        ]);

        $vault = new VaultStorage([
            'client' => $client,
        ]);
        
        //add
        $vault->setValue('/my/secret/key', 'value');
        
        //delete
        $vault->deleteValue('/my/secret/key');
    }
}
```
Or if you configure vault in project like this:
```php
return [
    'components' => [
        'vault' => [
            'class' => lav45\settings\storage\VaultStorage::class,
            'client' => [
                'class' => lav45\settings\storage\vault\Client::class,
                'url' => 'url',
                'token' => 'token',
                'kvPath' => '/kv',
            ],
        ],
    ],
];
```
You can use something like this:
```php
class m221103_161325_vault_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $vault = Yii::$app->vault;        
        //add
        $vault->setValue('/my/secret/key', 'value');
        
        //delete secret with all keys
        $vault->deleteValue('/my/secret/key');
    }
}
```

## License

**yii2-vault-project-configuration** it is available under a BSD 3-Clause License. Detailed information can be found in the `LICENSE.md`.
