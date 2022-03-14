### Introduction

This module provides a block which loads a list of Pac 12 Videos on Demand via AJAX according to these [specifications](https://docs.google.com/document/d/1AYhUe6AtDEEXRzXm1h39r43u7LclSVybLLl7KcgJUNE/edit#).

### Requierments
This module requires no modules outside of Drupal core. Tested for Drupal 9.3.7.

### Installation
* Install as you would normally install a contributed Drupal module. Visit
   https://www.drupal.org/node/1897420 for further information.

### Notes
* I added a sleep(3) to the backend of my block to help demonstrate the AJAX capabilites.
* I spun up a Drupal site using AWS lightsail to demonstrate the module. [It is available here](http://18.211.184.30)

### Configuration
* Once installed a new block named `Video Feed Ajax Block` will be available. Place that block on any page on your Drupal 9 site to display a list of Videos on Demand.
* A Drupal admin can configure the following aspects of the content:
  * Number of results can be adjusted using the query settings on the view.
  * Thumbnail image size can be adjusted on the thumbnail field of the view.
  * The sport to display can be set as a filter on the view.
  * The HTML output of the content can be configured and adjusted using views as usual.


### Architecture
This module uses a Views Query Plugin to make the Pac 12 VoD API available as the backend of a view. It also creates a separate custom block to render the view via ajax. The view can be used to add the content anywhere on the site but if you want to use ajax to load the vidoes you must use the Ajax wrapper block.

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
* In total I spent about 10 hours on this project. Much of the time was spent getting my development environment setup for Drupal and refreshing myself on several Drupal concepts.

### Questions
* What kind of caching do we want for the VoD View (Tag based vs Time-based)?
* I didn't spend anytime on the theming of the View? Might it make sense for me to add some basic theming? You might notice that the content is not actually wrapped in DIVS as specified in the requirements. I found that displaying it in a views table was the most legible and quickest way to set it up. It would be trivial to adjust this to instead display the content within DIVS using views and add a template to theme the output.
