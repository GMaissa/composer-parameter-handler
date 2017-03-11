# Remove your parameters files generated with Incenteev/ParameterHandler package

## About

This bundle provides tools to remove files generated with Incenteev/ParameterHandler package, from which it uses the configuration.

It provides a script, to be used in composer group of scripts.

## Installation

With composer :

    php composer.phar require gmaissa/composer-remove-parameter-handler

You can than add the provided script to your composer.json scripts section :

    ...
    "scripts": {
        ...
        "post-install-cmd": [
            ...,
            "GMaissa\\ComposerParameterHandler\\Composer\\ScriptHandler::removeHandledFiles",
            ...
        ],
        "post-update-cmd": [
            ...,
            "GMaissa\\ComposerParameterHandler\\Composer\\ScriptHandler::removeHandledFiles",
            ...
        ]
    }


## Contributing

In order to be accepted, your contribution needs to pass a few controls : 

* PHP files should be valid
* PHP files should follow the [PSR-2](http://www.php-fig.org/psr/psr-2/) standard
* PHP files should be [phpmd](https://phpmd.org) and [phpcpd](https://github.com/sebastianbergmann/phpcpd) warning/error free

To ease the validation process, install the [pre-commit framework](http://pre-commit.com) and install the repository pre-commit hook :

    pre-commit install

Finally, in order to homogenize commit messages across contributors (and to ease generation of the CHANGELOG), please apply this [git commit message hook](https://gist.github.com/GMaissa/f008b2ffca417c09c7b8) onto your local repository. 
