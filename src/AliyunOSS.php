<?php

namespace JayChan\Aliyun\OSS;

use Aliyun\OSS\OSSClient;
use Aliyun\OSS\Models\OSSOptions;

/**
 * 阿里云OSS.
 *
 * @author JayChan <voidea@foxmail.com>
 *
 * @version 1.0
 */
class AliyunOSS
{
    /**
     * 阿里云OSS Client.
     *
     * @var Aliyun\OSS\OSSClient
     */
    private $client;

    /**
     * bucket 名称.
     *
     * @var string
     */
    private $bucket;

    public function __construct(array $config)
    {
        $endpoint = array_get($config, 'endpoint', '');
        if (!starts_with($endpoint, 'http://') || !starts_with($endpoint, 'https://')) {
            $endpoint = 'http://'.$endpoint;
        }
        $this->client = OSSClient::factory([
            OSSOptions::ACCESS_KEY_ID => array_get($config, 'access_key_id', ''),
            OSSOptions::ACCESS_KEY_SECRET => array_get($config, 'access_key_secret', ''),
            OSSOptions::ENDPOINT => $endpoint,
        ]);
        $this->setBucketPrefix(array_get($config, 'bucket_prefix', ''));
    }

    /**
     * 获取OSSClient.
     *
     * @return Aliyun\OSS\OSSClient
     */
    public function getClient()
    {
        return $this->client;
    }

    public function setBucketPrefix($prefix)
    {
        $this->bucketPrefix = $prefix;
    }

    public function getBucketPrefix()
    {
        return $this->bucketPrefix;
    }

    /**
     * 设置Bucket.
     *
     * @param string $bucket
     */
    public function setBucket($bucket)
    {
        $this->bucket = $bucket;
    }

    public function getBucket($bucket = null)
    {
        return $this->bucketPrefix.($bucket ?: $this->bucket);
    }

    /**
     * 获取请求Url.
     *
     * @param string $key
     * @param int    $expire
     * @param string $bucket
     *
     * @return string
     */
    public function getUrl($key, $expire = 3600, $bucket = null)
    {
        return $this->client->generatePresignedUrl([
            OSSOptions::BUCKET => $this->getBucket($bucket),
            OSSOptions::KEY => $key,
            OSSOptions::EXPIRES => $expire,
        ]);
    }

    /**
     * 上传内容.
     *
     * @param string $key
     * @param string $content
     * @param string $bucket
     */
    public function uploadContent($key, $content, $bucket = null)
    {
        $this->upload($key, $content, strlen($content), $bucket);
    }

    /**
     * 上传文件.
     *
     * @param string $key
     * @param string $file
     * @param string $bucket
     */
    public function uploadFile($key, $file, $bucket = null)
    {
        $this->upload($key, fopen($file, 'r'), filesize($file), $bucket);
    }

    /**
     * 上传.
     *
     * @param string $key
     * @param string $content
     * @param int    $size
     * @param string $bucket
     */
    public function upload($key, $content, $size, $bucket = null)
    {
        $this->client->putObject([
            OSSOptions::BUCKET => $this->getBucket($bucket),
            OSSOptions::KEY => $key,
            OSSOptions::CONTENT => $content,
            OSSOptions::CONTENT_LENGTH => $size,
        ]);
    }

    /**
     * 创建Bucket.
     *
     * @param string $bucket
     *
     * @return false
     */
    public function createBucket($bucket)
    {
        return $this->client->createBucket([OSSOptions::BUCKET => $this->getBucket($bucket)]);
    }

    /**
     * 获取所有的对象的Key.
     *
     * @param string $bucket
     *
     * @return array
     */
    public function getAllObjectKeys($bucket = null)
    {
        $objectLists = $this->client->listObjects([
            OSSOptions::BUCKET => $this->getBucket($bucket),
        ]);

        return array_map(function ($objectSummary) {
            return $objectSummary->getKey();
        }, $objectLists->getObjectSummarys());
    }

    /**
     * Handle dynamic method calls.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array([$this->client, $method], $parameters);
    }

    /**
     * Handle dynamic static method calls.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        return call_user_func_array([$this->client, $method], $parameters);
    }
}
