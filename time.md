# Development Timings

## Stage 1 - Planning

Upon reading the brief it was clear that the assignment was for a very simple web app so my initial thought was it wouldn't be too tricky to build it using basic building blocks and not to use a framework.  Doing it without a framework would have shown you my abilities of writing something from scratch and maybe highlighted a greater range of knowledge and skills.  However I decided to use a framework because I thought it would be silly to do something differently in this sort of assessment than I would choose to do in a real life situation where a framework lets you hit the ground running with a lot of stable code ready and waiting.

Symfony 3 was my chosen framework as I've worked with it a lot and know it is a solid option.  It provides some helper commands for creating entity classes and for generating CRUD code to act on the entity which will get the development started nice and quickly.  Beyond that a few tweaks and I should have an app to fit the brief.

``` 
Time taken: 30 minutes
```

## Stage 2 - Initial Installation and Set Up

I already had the [Symfony Installer](https://github.com/symfony/symfony-installer) on my local machine so the first step was to create a new project.

```bash
$ symfony new rss-feed-reader 3.4
```

I knew I'd need an entity to store each of the RSS Feeds in.  Each entity would need a URL field to hold the feed url and a name field to identify the feed should the URL be a bit obscure.  So I used the symfony console to generate such an entity for me.  

```bash
$ bin/console doctrine:generate:entity
```
That's an interactive command where you can specify the entity name and fields.  So I named it RSSFeed and added the name and url fields.  I also added created and updated timestamp fields as they're often useful.

The brief requires that the feeds should be listed, created, updated, deleted.  That's basically standard CRUD code so I made use of the symfony console command again to generate that code for me.

```bash
$ bin/console doctrine:generate:crud
```
That asks which entity to use and then creates a controller, form type, test code and twig templates for you.

I used the ```bin/console doctrine:schema:create``` command to update the database with my new entity schema and then started the development web server with the ```bin/console server:start``` command.

On viewing ```http://localhost:8000``` I was presented with the default Symfony page for a new install.  My RSS Feed list was at ```http://localhost:8000/rss``` so I changed the DefaultController to redirect to /rss as the homepage isn't much use to us.

I ran phpunit to check that the test scripts that had been generated were running successfully.

I now had a working app where I could add, edit, delete and view my RSS Feed entities.  The first obvious problem was that when viewing the feeds I was only presented with the display of the entity fields not the data from the feed, so I now needed to actually do a bit of work.

```
Time taken: 30 mins
```

## Stage 3 - Main Development Stage

### Files of interest

```
src/AppBundle/Controller/RSSFeedController.php
src/AppBundle/Entity/RSSFeed.php
src/AppBundle/Form/RSSFeedType.php

app/Resources/views/rssfeed/index.html.twig
app/Resources/views/rssfeed/new.html.twig
app/Resources/views/rssfeed/edit.html.twig
app/Resources/views/rssfeed/show.html.twig
```

I needed to do a request for the actual feed to get the feed items and parse it into something usable.  To do that I looked around for a library that could do that for me.  The one I chose was the debril/rss-atom-bundle bundle.  I added it to the project using composer and did the necessary configuration to get it hooked into the project.

```bash
$ composer require debril/rss-atom-bundle
```
I could now use that bundle in the showAction method in my RSSFeedController to do a request for the rss feed data and pass that data to the twig template.  I then updated the twig template to loop over feed items and output them as a list.  I added the striptags filter to the description fields to tidy up some of the data that was coming through.

By default the symfony templates don't have any style, so I now decided to add a link to the bootstrap 4 CDN to my base template.  I also set the config twig form theme to use bootstrap_4_layout.html.twig so that my add/edit forms would look right.

Now that I had bootstrap style I updated all the templates with the classes and markup to make them look better and rearranged the layout a little.

I noticed that the CRUD generator had used the entity ID in the url paths, it's always nicer to use some sort of slug.  So I installed the stof/doctrine-extensions-bundle bundle which has a Sluggable option that can be added to your entity class to generate a slug from one or more fields.  I set it to generate it from the existing name field.  A couple of tweaks to the RSSFeedController and the templates and the IDs were replaced with slugs in the URL paths.

Running phpunit again now failed.  I'd changed button labels in the templates so the crawler was no longer able to match what it needed to find.  So I made some updates to the test scripts and added an extra one for the RSSFeedType form type class.  These now run successfully.

```
Time taken: 2.5 hours
```

## Stage 4 - Documentation

The final stage was writing the README.md and instructions.md as well as this document.

```
Time taken: 1.5 hours
```