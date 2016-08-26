MATA CMS FAQ
==========================================

![MATA CMS Module](https://s3-eu-west-1.amazonaws.com/qi-interactive/assets/mata-cms/gear-mata-logo%402x.png)


FAQ module allows to manage FAQs in MATA CMS.


Installation
------------

- Add the module using composer:

```json
"matacms/matacms-faq": "~1.0.0"
```

-  Run migrations
```
php yii migrate/up --migrationPath=@vendor/matacms/matacms-faq/migrations
```


Client
------

FAQ Client extends [`matacms\clients`](https://github.com/qi-interactive/matacms-base/blob/development/clients/SimpleClient.php).

Changelog
---------

## 1.0.0.2-alpha, August 26, 2016

- Bugfixes

## 1.0.0.1-alpha, August 25, 2016

- Updated module icon

## 1.0.0-alpha, August 24, 2016

- Initial release.
