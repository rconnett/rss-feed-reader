# RSS Feed Reader

This is a Symfony 3 based RSS feed reader web app.

## Requirements

 - PHP 5.6+
 - Mysql server
 - Composer
 - PHPUnit (for running tests)
 
## Installation

```bash
# Clone this repo
$ git clone git@github.com:rconnett/rss-feed-reader.git

# Change into the new directory
$ cd rss-feed-reader

# Check that your machine has the necessary requirements
$ php bin/symfony_requirements

# Install all the dependencies and configure
# Towards the end of the install you will be asked for your database details
# The database doesn't need to exist but the user and password should be able
# to connect to the database server and if necessary have create rights for
# the next step.
$ composer install

# If your database doesn't exist create it with
$ bin/console doctrine:database:create

# Add the database schema into your chosen database
$ bin/console doctrine:schema:create

# Start a local web server
$ bin/console server:start
```

You should now be able to see the web app at http://localhost:8000.
 
## Installation with an external web server

To run this in production point your web server document root to the /web/ directory and enable overrides in the virtual host.

## Production installation

If this project were to be used in production then I'd recommend tagging the master branch at appropriate moments when features are ready and testing those tags in a test environment that matches the production environment before deploying to the production environment.

When deploying to test or production a ```composer install``` should be used to make sure the same versions of dependencies are used as have been used for development.

This should all be automated but the manual process should also be well understood so that issues can be resolved quickly if they occur.

## Testing

To run the tests you'll need to have PHPUnit installed.  Then from the project directory run

```bash
$ phpunit
```

License
----

MIT
