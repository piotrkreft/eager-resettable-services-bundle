# Eager resettable services bundle

![CI](https://github.com/piotrkreft/eager-resettable-services-bundle/workflows/CI/badge.svg)
[![Coverage Status](https://coveralls.io/repos/github/piotrkreft/eager-resettable-services-bundle/badge.svg)](https://coveralls.io/github/piotrkreft/eager-resettable-services-bundle)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fpiotrkreft%2Feager-resettable-services-bundle%2Fmaster)](https://infection.github.io)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/piotrkreft/eager-resettable-services-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/piotrkreft/eager-resettable-services-bundle/?branch=master)

Symfony bundle for eager instantiating resettable services.

## Introduction
For some edge cases it might be required that service gets reset regardless of being referenced by other services.

An example of that would be `doctrine` Registry holding Entity Managers.
It does not reset managers unless it is being referenced by other services and therefore instantiated by the container.

This bundle by the configuration allows you to reconfigure services to be eagerly instantiated within Services Resetter.

## Example
[example configuration](tests/Fixtures/Resources/config/config.yaml)

Alternatively all services can be eager loaded wth `all_services` configuration flag.
