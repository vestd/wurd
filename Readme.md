# Setup
This is still in progress and as such is not a public package.

# Cache Provider
This api uses Flysystem for caching the language files either locally or remotely. If you wish to use your own provider simply implement the CacheProviderInterface.

To use Flysystem, pass it an adapter (in this case Local for local disk storage). Then pass Flysystem on to the cache provider, and load that in to Wurd. 
You may also specify how long (in minutes) the cache will remain before expiring. If the cache is older than this value then the next request will retrieve and cache the latest version from Wurd.io. 

```
$adapter = new League\Flysystem\Adapter\Local('/path/to/storage/folder/');
$filesystem = new League\Flysystem\Filesystem($adapter);
$cacheProvider = new FlysystemCacheProvider($filesystem, 60);
$wurd = new Wurd('yourWurdAppName', $cacheProvider);
```

# Usage
To get a full language file, use `$wurd->language('en');`. Specifying no language will return the default language (eg `$wurd->language();`)

To get a single page, use `$wurd->page('pageIWant');`. If you want a page from the non-default language, use `$wurd->page('pageIWant', 'languageName');`.

# To do
- Wurd Tests - decide how best to handle extracting the real API
- Cache Tests

# Laravel example

```
$wurd = new \Vestd\Wurd\Wurd(
    'yourWurdAppName',
    new \Vestd\Wurd\CacheProvider\LaravelCacheProvider(
        \Illuminate\Support\Facades\Storage::getDriver(),
        '/folder-within-storage/',
        60
    )
);
```