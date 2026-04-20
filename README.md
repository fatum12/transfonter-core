# @font-face generator

Modern and simple css @font-face generator and subsetter. Inspirited by https://github.com/zoltan-dulac/css3FontConverter

This package is part of https://transfonter.org

## Dependencies

* [FontForge](http://fontforge.github.io/)
* [ttf2eot](https://code.google.com/p/ttf2eot/)
* [ttfautohint](http://www.freetype.org/ttfautohint/)
* [pyftsubset](https://github.com/fonttools/fonttools)

## Installation

The minimum required PHP version is 7.1

Installing via [Composer](https://getcomposer.org):

* update `composer.json`

```json
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
