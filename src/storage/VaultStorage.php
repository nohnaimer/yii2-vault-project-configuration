<?php

namespace nohnaimer\config\storage;

use yii\di\Instance;
use yii\base\BaseObject;
use nohnaimer\vault\services\KVInterface;
use lav45\settings\storage\StorageInterface;

/**
 * Class VaultStorage
 * @package nohnaimer\config\storage
 */
class VaultStorage extends BaseObject implements StorageInterface
{
    /** @var string|array|KVInterface */
    public $kv = 'kv';

    /**
     * Initializes the application component.
     */
    public function init()
    {
        parent::init();

        $this->kv = Instance::ensure($this->kv, KVInterface::class);
    }

    /**
     * @param string $key
     * @return false|string|null
     */
    public function getValue($key)
    {
        [$key, $secret] = $this->getKeySecret($key);

        return $this->kv->get($secret)['data'][$key] ?? false;
    }

    /**
     * @param string $key
     * @return array
     */
    public function getValues($key)
    {
        return $this->kv->list($key);
    }

    /**
     * @param string $key
     * @param string $value
     * @return bool|array
     */
    public function setValue($key, $value)
    {
        [$key, $secret] = $this->getKeySecret($key);

        $data = [$key => $value];

        return $this->kv->post($secret, $data);
    }

    /**
     * @param string $key
     * @return bool|array
     */
    public function deleteValue($key)
    {
        [$key, $secret] = $this->getKeySecret($key);

        return $this->kv->delete($secret);
    }

    /**
     * @param string $value
     * @return array
     */
    private function getKeySecret(string $value)
    {
        $data = explode('.', $value);

        $key = array_pop($data);
        $path = '/' . implode('/', $data);

        return [$key, $path];
    }
}