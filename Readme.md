# Setup

This api by default uses Flysystem for caching the language files either locally or remotely. If you wish to use your own provider simply implement the CacheProviderInterface.

# Instantiation

```
$wurd = new Wurd('yourWurdAppName');
```

# Usage
## Single page/pages

To get a single page, use `$wurd->pages('pageIWant');`. 

For multiple pages, use `$wurd->pages(['pageIWant', 'anotherPage']);`

If you want pages from the non-default language, specify the language as the second parameter `$wurd->pages('pageIWant', 'languageName');`.

## Entire language file

To get a full language file, use `$wurd->language('en');`. 

Specifying no language will return the default language (eg `$wurd->language();`)

# Cache Provider

For more control over Flysystem and the CacheProvider, pass Flysystem your own choice of adapter (in this case Local for local disk storage). Then pass Flysystem on to the cache provider, and load that in to Wurd. 

You may also specify how long (in minutes) the cache will remain before expiring. If the cache is older than this value then the next request will retrieve and cache the latest version from Wurd.io. 

```
$timeToLive = 60; // overwrite cache after 60 minutes
$adapter = new League\Flysystem\Adapter\Local('/path/to/storage/folder/');
$filesystem = new League\Flysystem\Filesystem($adapter);
$cacheProvider = new FlysystemCacheProvider($filesystem, $timeToLive);
$wurd = new Wurd('yourWurdAppName', $cacheProvider);
```

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