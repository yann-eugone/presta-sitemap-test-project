

## Install

**Require dependencies**

```bash
git clone git@github.com:tacman/presta-sitemap-test-project.git presta && cd presta
composer install
bin/console doctrine:database:create
bin/console doctrine:schema:update --force --complete
bin/console doctrine:fixtures:load --no-interaction
# setup the proxy, see below
symfony server:start -d
symfony open:local --path=sitemap.xml
```

**Prepare database entities**

Sqlite (the default) doesn't support --if-exists, but add that for MySQL or Postgres

```bash
bin/console doctrine:database:drop --force
bin/console doctrine:database:create
bin/console doctrine:schema:update --force --complete
bin/console doctrine:fixtures:load --no-interaction
symfony open:local
```

**Setup Symfony Proxy**

```bash
symfony proxy:domain:attach presta
```

**Dump your sitemap**

If you don't want to dynamically create the sitemap on every request, dump it.

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

Or if you're using the Symfony CLI:

```bash
symfony open:local --path=/sitemap.xml
symfony open:local --path=/sitemap.default.xml
symfony open:local --path=/sitemap.blog.xml
symfony open:local --path=/sitemap.misc.xml
symfony open:local --path=/sitemap.yml.xml
```
