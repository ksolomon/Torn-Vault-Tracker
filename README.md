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
* Edit `vault.csv` to set initial balances for each user (see notes below).
* Open the tracker in any web browser.
* Style as you see fit in `style.css` (Optional, but if you make something cool, let me know!).

### Initial vault balance(s)
Currently, there's no way to get past vault transactions via the API.  If you're starting new, you can use the vault balance before your spouse adds their first deposit.  For existing vaults, you'll need to figure out the balances the hard way.  Once you have the balances, put them into the `vault.csv` with the proper user names.  The timestamp field doesn't matter, and can be left at the currnet value.

### Automating transaction updates

I recommend using an external site monitoring service such as [cronitor.io](https://cronitor.io) set to check the URL of your server.  A check rate of 15 minutes should be more than enough, since you can always force a refresh by simply opening the site in your browser.

## Authors

Keith Solomon / [ZarathosX75](https://www.torn.com/profiles.php?XID=2606457) on [Torn](https://torn.com/)

## Version History

* 0.1
    * Initial Release
