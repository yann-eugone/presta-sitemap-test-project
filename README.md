

## Install

**Require dependencies**

```bash
$ composer install
```

**Prepare database entities**

```bash
$ bin/console doctrine:database:drop --if-exists --force
$ bin/console doctrine:database:create
$ bin/console doctrine:schema:create
$ bin/console doctrine:fixtures:load
$ bin/console doctrine:fixtures:load --no-interaction
```

**Run built-in server**

```bash
$ bin/console server:run
```

Now you can visit [http://localhost:8000/](http://localhost:8000/).

Urls of sitemaps :

    - [sitemap.xml](http://localhost:8000/sitemap.xml)
    - [sitemap.default.xml](http://localhost:8000/sitemap.default.xml)
    - [sitemap.blog.xml](http://localhost:8000/sitemap.blog.xml)
    - [sitemap.misc.xml](http://localhost:8000/sitemap.misc.xml)
    - [sitemap.yml.xml](http://localhost:8000/sitemap.yml.xml)


## Dump the sitemap

```bash
$ bin/console presta:sitemaps:dump
```
