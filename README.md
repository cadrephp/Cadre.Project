# Cadre.Project

This is a project skeleton for me to use when starting new projects.

## Preconfigured Libraries

- [Atlas.ORM](https://github.com/atlasphp/Atlas.Orm)
- [Bootstrap for Sass](http://getbootstrap.com/) HTML, CSS, and JS framework
- [Cadre.CliAdr](https://github.com/cadrephp/Cadre.CliAdr) Command line Action-Domain-Responder (ADR) implementation
- [Cadre.DomainSession](https://github.com/cadrephp/Cadre.DomainSession) Library for tracking session data within the domain
- [Cadre.Module](https://github.com/cadrephp/Cadre.Module) Aura.Di compatible extension for related modules
- [Composer](https://getcomposer.org/) PHP dependency manager
- [FIG Cookies](https://github.com/dflydev/dflydev-fig-cookies) Managing Cookies for PSR-7 Requests and Responses
- [jQuery](https://jquery.com/) Feature-rich JavaScript library
- [Phing](https://www.phing.info/) PHP build system
- [Phinx](https://phinx.org/) PHP Database Migrations
- [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
- [PHP Debug Bar](http://phpdebugbar.com/)
- [PHPStan](https://github.com/phpstan/phpstan) PHP Static Analysis Tool
- [PHPUnit](https://phpunit.de/) for testing and code coverage
- [Radar](https://github.com/radarphp/Radar.Project) ADR system
- [Sass](http://sass-lang.com/) CSS extension language
- [Twig](http://twig.sensiolabs.org/)
- [Webpack Encore](https://github.com/symfony/webpack-encore) Module bundler

## Setup

```bash
# Setup new project repo
composer create-project -s dev cadre/project example-project --repository-url=https://packages.cadrephp.com
cd example-project
git init .

# Install dependencies
composer install
npm install

# Compile assets once
npm run dev 

# Compile assets automatically when files change
npm run watch

# Compile assets, but also minify & optimize them
npm run prod

# Run lint, phpcs, and phpunit
vendor/bin/phing build

# Run tests and generate HTML coverage report (in build/coverage)
vendor/bin/phing coverage
```
