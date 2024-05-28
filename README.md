## Install

**Require dependencies**

```bash
composer install
```

**Prepare database entities**

Sqlite (the default) doesn't support --if-exists, but add that for MySQL or Postgres

```bash
bin/console doctrine:database:drop --force
bin/console doctrine:database:create
bin/console doctrine:schema:update --force --complete
bin/console doctrine:fixtures:load --no-interaction
```

**Dump your sitemap**

The bundle does not require it, but you should definitively dump the sitemaps instead of generating it at every request.

```bash
bin/console presta:sitemaps:dump
```

**Run built-in server**

Follow symfony web server [documentation](https://symfony.com/doc/current/setup/symfony_server.html) to start your app.

Now you can visit [http://127.0.0.1/](http://127.0.0.1/).

Urls of sitemaps :

- [sitemap.xml](http://127.0.0.1/sitemap.xml)
- [sitemap.default.xml](http://127.0.0.1/sitemap.default.xml)
- [sitemap.blog.xml](http://127.0.0.1/sitemap.blog.xml)
- [sitemap.misc.xml](http://127.0.0.1/sitemap.misc.xml)
- [sitemap.yml.xml](http://127.0.0.1/sitemap.yml.xml)
