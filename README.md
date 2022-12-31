# Torn Vault Tracker

Track vault balances for shared private island vaults in [Torn City](https://torn.com/).

## Description

Uses the [Torn API](https://www.torn.com/api.html) to pul the most recent vault transactions for you and your spouse.  Note that in order to access the "Log" endpoint, the keys used must be created with Full Access permissions.

## Getting Started

### Dependencies

* Any reasonable LAMP stack (developed on Ubuntu 20.04 with Apache and PHP 8.1.10)

### Installing

* Create folder in your server's web root, for example, 'tvt'.
* Upload/clone this repo into your folder.
* Edit `functions.php` to add API keys and Torn user names for you and your spouse.
* Open the tracker in any web browser.
* Style as you see fit in style.css (Optional, but if you make something cool, let me know!).

### Automating transaction updates

I recommend using an external site monitoring service such as [cronitor.io](https://cronitor.io) set to check the URL of your server.  A check rate of 15 minutes should be more than enough, since you can always force a refresh by simply opening the site in your browser.

## Authors

Keith Solomon / [ZarathosX75](https://www.torn.com/profiles.php?XID=2606457) on [Torn](https://torn.com/)

## Version History

* 0.1
    * Initial Release
