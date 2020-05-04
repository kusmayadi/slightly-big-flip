# Flip Recruitment Test
![Travis (.org)](https://img.shields.io/travis/kusmayadi/slightly-big-flip?style=flat-square)


This repo contains scripts as part of recruitment process at Flip.id.

Please note that the main program is under `src/` folder and I don't use any external libraries nor any frameworks for the main program.

I use phpunit and one external library, Faker, for unit testing purposes only. These external libraries managed by composer.

All the commands, as explained below, must be run from `src/` folder, except for test. Test must be run from root folder.

# Table of Contents
- [Flip Recruitment Test](#flip-recruitment-test)
- [Table of Contents](#table-of-contents)
  - [Configuration](#configuration)
  - [Migration](#migration)
  - [Usage](#usage)
    - [Single Disbursement](#single-disbursement)
    - [Multiple Disbursements](#multiple-disbursements)
    - [Check Disbursement Status](#check-disbursement-status)
  - [Test](#test)
    - [Requirements](#requirements)
    - [Installation](#installation)
    - [Running Tests](#running-tests)


## Configuration

Copy `.env.example` to `.env` then replace each environment variables with your local configuration, as well as API variables.

```
cp .env.example .env
```

## Migration

Before running **disburse** & **disburse_status** scripts, make sure you run migration first.

```
php migration.php up
```

If you want to rollback (drop table `disbursements`), you can run:

```
php migration.php down
```

## Usage

### Single Disbursement

For single disbursement, you can run:

```
php disburse.php
```

You will be prompted with required informations.

### Multiple Disbursements

For multiple disbursements, you can create a csv file containing disbursement data. Please take a look at [sample data](src/sample_data/sample.csv). To do multiple disbursements:

```
php disburse.php {path/to/file.csv}

```

### Check Disbursement Status

To check disbursement status, run:

```
php disburse_status.php {id}
```

`{id}` is the id of disbursement you want to check.

## Test

### Requirements

The test is using [phpunit 9.1](https://phpunit.de/), so make sure you have PHP 7.3 or PHP 7.4 installed.

It's also using composer for managing test dependency, so make sure you have [composer](https://getcomposer.org/) installed.

### Installation

Install required packages, which basically only [phpunit](https://phpunit.de/) & [Faker](https://github.com/fzaninotto/Faker), by running this following command inside root folder:

```
composer install
```
### Running Tests

Just run:

```
composer test
```
