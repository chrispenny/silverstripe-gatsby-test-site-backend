# {Vendor} {Project} Overview

 * [Project Confluence Page]({add link to project confluence})

## Local Installation

you can use [Vagrant](https://www.vagrantup.com/):
```
cp Vagrantfile.dist Vagrantfile
vagrant up
vagrant ssh
```

### Build steps:
```
# Run composer
composer install

# install yarn dependencies
yarn

# create a .env file from the template
cp .env.dist .env

# run a dev/build
vendor/bin/sake dev/build flush=1
```

## Available Build Tools

* `yarn` install dependencies
* `yarn dev` builds dev js and scss
* `yarn watch` same as `yarn dev` but watches for changes
* `yarn production` minifies production files
* `yarn prod` alias for `yarn production`
* `yarn lint` lints js and scss
* `yarn hot` **unsupported** hot module reloading

### Other tools and commands

* `composer silverstripe-standards` run all coding standards checks, including linters (php, js, scss)
* `composer phpcs-fix` run code-sniffer fixer

## Further Documentation and How to
* [Acceptance Testing with Nightwatch](docs/nightwatch.md)
* [Building Components with Vue](docs/vue.md)
* [Vue Unit Testing](docs/vuetest.md)
* [Maintaining a reusable component Library with Storybook](docs/storybook.md)
* [Adding CWP and/or watea theme](docs/cwp.md)
