<?php

namespace Cache;

class Cache
{
    private static $time = '1 minute';

    private $folder;

    public function __construct($folder = null)
    {
        $this->setFolder(!is_null($folder)
            ? $folder
            : sys_get_temp_dir());
    }

    public function get($key, $closure, $time = '30 seconds') {
        $data = $this->read($key);

        if (!$data) $this->save($key, $closure(), $time);

        return $data;
    }

    protected function setFolder($folder)
    {
        if (file_exists($folder) && is_dir($folder) && is_writable($folder)) {
            $this->folder = $folder;
        } else {
          trigger_error('Não foi possível acessar a pasta de cache', E_USER_ERROR);
        }
    }

    protected function generateFileLocation($key) {
        return $this->folder . DIRECTORY_SEPARATOR . sha1($key) . '.tmp';
    }

    protected function createCacheFile($key, $content)
    {
        $filename = $this->generateFileLocation($key);

        return file_put_contents($filename, $content)
                OR trigger_error('Não foi possível criar o arquivo de cache', E_USER_ERROR);
    }

    public function save($key, $content, $time = null)
    {
        $time = strtotime(!is_null($time) ? $time : self::$time);

        return $this->createCacheFile($key, serialize([
          'expires' => $time,
          'content' => $content,
        ]));
    }

    public function read($key)
    {
        $filename = $this->generateFileLocation($key);

        if(file_exists($filename) && is_readable($filename)) {
          $cache = unserialize(file_get_contents($filename));

          if ($cache['expires'] > time()) {
            return $cache['content'];
          } else {
            unlink($filename);
          }
        }
    }
}
