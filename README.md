# @font-face generator

Modern and simple css @font-face generator and subsetter. Based on https://github.com/zoltan-dulac/css3FontConverter

This component used on https://transfonter.org

## Dependencies

* [FontForge](http://fontforge.github.io/)
* [ttf2eot](https://code.google.com/p/ttf2eot/)
* [woff2_compress](https://github.com/google/woff2)
* [ttfautohint](http://www.freetype.org/ttfautohint/)
* [pyftsubset](https://github.com/behdad/fonttools)

## Installation

The minimum required PHP version is 5.6

Installing via [Composer](https://getcomposer.org):

* update `composer.json`

```
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://bitbucket.org/fatum12/transfonter-core.git"
    }
  ],
  "require": {
    "fatum12/transfonter-core": "dev-master"
  }
}
```

* run `composer install`