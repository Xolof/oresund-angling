# oresund-angling

[![Xolof](https://circleci.com/gh/Xolof/oresund-angling.svg?style=svg)](https://app.circleci.com/pipelines/github/Xolof/oresund-angling)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/Xolof/oresund-angling/badges/code-intelligence.svg?b=main)](https://scrutinizer-ci.com/code-intelligence)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/Xolof/oresund-angling/badges/code-intelligence.svg?b=main)](https://scrutinizer-ci.com/code-intelligence)
[![Build Status](https://scrutinizer-ci.com/g/Xolof/oresund-angling/badges/build.png?b=main)](https://scrutinizer-ci.com/g/Xolof/oresund-angling/build-status/main)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/Xolof/oresund-angling/badges/code-intelligence.svg?b=main)](https://scrutinizer-ci.com/code-intelligence)

A forum for asking questions. This is a project from the course ramverk1 at Blekinge Institute of Technology.

## How to install

### Clone the repo

Clone the repo from the commandline into a directory in your web root.

`git clone https://github.com/Xolof/oresund-angling.git oresund-angling`

`cd oresund angling`

Install dependencies with composer.

`composer install`

Change the file permissions for the cache.

`chmod -R 777 cache/*`

In `htdocs/.htaccess`, replace `kmom10` with `oresund-angling`.
Replace `oljh19` with your own acronym.

### Database

#### Setup the SQL

Enter your mysql client

`mysql -u<USER> -p<password>`

Then create the database.

`CREATE DATABASE IF NOT EXISTS qadb;`

`USE qadb;`

Read the sql-files to create tables.

`source sql/ddl/answer_mysql.sql;`

`source sql/ddl/question_mysql.sql;`

`source sql/ddl/answer-comment_mysql.sql;`

`source sql/ddl/question-comment_mysql.sql;`

`source sql/ddl/user_mysql.sql;`

`source sql/ddl/user-profile_mysql.sql;`

`source sql/ddl/tag_mysql.sql;`

`source sql/ddl/tag-to-question_mysql.sql;`

#### Edit config file

`cp config/database_sample.php config/database.php`

Edit `config/database.php` with your details.
