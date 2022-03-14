[[_TOC_]]

### Introduction

This module provides a block which loads a list of Pac 12 Videos on Demand via AJAX according to these [specifications](https://docs.google.com/document/d/1AYhUe6AtDEEXRzXm1h39r43u7LclSVybLLl7KcgJUNE/edit#).

### Requierments
This module requires no modules outside of Drupal core.

### Installation
* Install as you would normally install a contributed Drupal module. Visit
   https://www.drupal.org/node/1897420 for further information.

### Configuration
Once installed a new block named `Video Feed Ajax Block` will be available. Place that block on any page on your Drupal 9 site to display a list of Videos on Demand.

### Architecture
This module uses a Views Query Plugin to make the Pac 12 VoD API available as the backend of a view. It also creates a separate custom block to render the view via ajax.

### Development Notes
* The first thing I had to do for this project was setup my tooling for Drupal.
  * For a local development environment I downloaded and installed Lando (a wrapper around Docker which makes it easy to develop using containers locally).
  * For an IDE I used VSCode. I configued it to according to [these recommendations](https://www.drupal.org/docs/develop/development-tools/configuring-visual-studio-code). For the linter I opted to just use the phpcs `Drupal` standard.
* I considered a few alternate ways to approach this problem:
  * Migrate the content into Drupal entities and leverage out-of-the-box Drupal tools to meet the rest of the requriements.
  * Write a service for consuming the API and use that service to populate a custom block.
  * Use javascript to hit the API and only rely on Drupal for the configuration options.
* In the end my thinking was that creating a Query Plugin demonstrated many aspects
  of Drupal development and satisfies the requirments in the spirit they
  were asked.

