<?php

if (!function_exists('config')) {
    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function config($key, $default = null)
    {
        if (YII_ENV_DEV) {
            return  $default;
        }

        return settings()->get("{$key}", $default);
    }
}

if (!function_exists('settings')) {
    /**
     * @return \lav45\settings\Settings
     */
    function settings()
    {
        static $model;

        if ($model !== null) {
            return $model;
        }

        $model = new lav45\settings\Settings([
            'serializer' => false,
            'buildKey' => false,
            'storage' => [
                'class' => nohnaimer\config\storage\VaultStorage::class,
                'client' => [
                    'class' => nohnaimer\config\storage\vault\Client::class,
                    'url' => getenv('VAULT_ADDR'),
                    'token' => getenv('VAULT_TOKEN'),
                    'kvPath' => getenv('VAULT_KV_PATH') ?: '/kv',
                ],
            ],
        ]);

        return $model;
    }
}