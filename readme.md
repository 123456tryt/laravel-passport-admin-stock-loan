## 相关文档
- [Laravel 5.5中文文档](https://d.laravel-china.org/docs/5.5/routing)
- [vuejs2中文文档](https://cn.vuejs.org/v2/guide/installation.html)
- [vue-router中文文档](https://router.vuejs.org/zh-cn/)
- [vuejs管理后台模板地址](https://github.com/lin-xin/vue-manage-system)
- [laravel注释跳转生成包](https://github.com/barryvdh/laravel-ide-helper)

## 提交代码注意事项
- 提交代码之前需要使用phpstorm格式化代码
- commit 之后需要rebase 在push到远程分支
- master分支正式部署的代码. dev分支是团队开发提交的代码
- api文档请写到相应的gitlab厂库wiki里面去

## 映利PHP-API项目(使用Laravel5.5框架,vuejs2前端框架)
Laravel 框架对系统有一些要求。所有这些要求 Laravel Homestead 虚拟机都能满足，因此强烈建议你使用 Homestead 作为你本地的 Laravel 开发环境。
- PHP >= 7.0.0
- PHP OpenSSL 扩展
- PHP PDO 扩展
- PHP Mbstring 扩展
- PHP Tokenizer 扩展
- PHP XML 扩展

## 环境

- mysql 版本5.7 数据库地址:10.10.1.101:3306 用户名:yingli 密码:yingli
- redis 版本5.7 数据库地址:10.10.1.101/127.0.0.1 端口:6379 用户名:空 密码:空
- nginx-php-fpm框架
- composer PHP依赖管理工具
- composer 已近安装laravel之外的包:predis(条用redis需要使用到)
- 前端页面使用vue-cli工具生成前端页面vusj + vue-router + webpack ...

## 本地开发环境

- php版本:7.1.9
- phpstorm安装相应的断点调试工具

## 第三方composer包
- [Laravel 的 API 认证系统 Passport](https://d.laravel-china.org/docs/5.5/passport)
- [predis](https://github.com/nrk/predis) `composer require predis/predis`