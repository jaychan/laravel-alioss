# 阿里云OSS SDK for Laravel 5

## 安装

1. 安装包文件
  ```shell
  composer require jaychan/laravel-alioss
  ```

2. 添加 `ServiceProvider` 到您项目 `config/app.php` 中的 `providers` 部分:

  ```php
  JayChan\Aliyun\OSS\ServiceProvider::class,
  ```

3. 创建配置文件：

  ```shell
  php artisan vendor:publish
  ```
4. 请修改应用根目录下的 `config/alioss.php` 中对应的项即可；

5. （可选）添加外观到 `config/app.php` 中的 `aliases` 部分:

  ```php
  'AliyunOSS' => JayChan\Aliyun\OSS\Facade::class,
  ```

## 使用

```php
// 设置Bucket
AliyunOSS::setBucket('my-bucket');
// 上传文件
AliyunOSS::uploadFile('key', $file = '/path/to/your/file', $bucket = null);
// 上传内容
AliyunOSS::uploadContent('key', $content = 'content', $bucket = null);
// 设置Bucket前缀
AliyunOSS::setBucketPrefix('prefix_');
// 获取资源请求URL
AliyunOSS::getUrl($key, $expire = 3600, $bucket = null);
```

## License

MIT
