<?php

namespace JayChan\Aliyun\OSS;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

/**
 * 服务提供者.
 *
 * @author JayChan <voidea@foxmail.com>
 *
 * @version 1.0
 */
class ServiceProvider extends LaravelServiceProvider
{
    public function boot()
    {
        // 扩展文件系统，增加AliOSS
        $this->app['filesystem']->extend('alioss', function () {
            return $this->app['filesystem.alioss'];
        });

        // 是否添加文件访问入口
        if (array_get($this->app['config'], 'alioss.file_entrance')) {
            $this->addFileEntrance();
        }

        $this->publishes([
            __DIR__.'/config/alioss.php' => config_path('alioss.php'),
        ], 'config');
    }

    public function register()
    {
        // 注册服务
        $this->app->singleton('filesystem.alioss', function () {
            $config = array_get($this->app['config'], 'alioss', '');

            return new AliyunOSS($config);
        });
    }

    /**
     * 添加文件访问入口.
     */
    private function addFileEntrance()
    {
        $domain = array_get($this->app['config'], 'alioss.domain', '');
        $this->app['router']->group($domain ? compact('domain') : [], function ($router) {
            $router->get('/{oss_bucket}/{file}', function ($bucket, $file) {
                $alioss = $this->app['filesystem.alioss'];
                $alioss->setBucket($bucket);
                @list($key, $ext) = explode('.', $file);
                $content = file_get_contents($alioss->getUrl($key));
                $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_buffer($fileInfo, $content);
                finfo_close($fileInfo);

                return response()->data($content)->header('Content-Type', $mime);
            });
        });
    }
}
