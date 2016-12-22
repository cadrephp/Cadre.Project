# Cadre.Project

This is a project skeleton for me to use when starting new projects.

## Preconfigured Libraries

- [Composer](https://getcomposer.org/) PHP dependency manager
- [Radar](https://github.com/radarphp/Radar.Project) ADR system
- [Cadre.Module](https://github.com/cadrephp/Cadre.Module) support
- [Atlas.ORM](https://github.com/atlasphp/Atlas.Orm)
- [Twig](http://twig.sensiolabs.org/)
- [PHP Debug Bar](http://phpdebugbar.com/)
- [Phing](https://www.phing.info/) PHP build system
- [PHPUnit](https://phpunit.de/) for testing and code coverage
- [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
- [Phinx](https://phinx.org/) PHP Database Migrations
- [Gulp](http://gulpjs.com/) JS build system
- [Bootstrap](http://getbootstrap.com/) HTML, CSS, and JS framework
- [Less](http://lesscss.org/) CSS pre-processor
- [Bower](https://bower.io/) JS package manager

## Setup

```bash
# Setup new project repo
composer create-project -s dev cadre/project example-project --repository-url=https://packages.cadrephp.com
cd example-project
git init .

# Install dependencies
composer install
npm install
bower install
gulp less

# Run lint, phpcs, and phpunit
vendor/bin/phing build

# Run tests and generate HTML coverage report (in build/coverage)
vendor/bin/phing coverage
```
