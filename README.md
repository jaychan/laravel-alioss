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
AliyunOSS::setBucket('my-bucket');
AliyunOSS::uploadFile('key', '/path/to/your/file');

// 获取OSSClient
AliyunOSS::getClient();
```

> 注意：获取OSSClient后，可以调用更多

## License

MIT
