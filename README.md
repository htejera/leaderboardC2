#LeaderboardC2

__LeaderboardC2__ is a self hosted Open Source PHP Leaderboard for [Construct2](https://www.scirra.com/construct2). Post, fetch and display high scores.

## Requirements

- PHP 5.4+  with at least one library extension that deals with SQLite is required (either PDO, SQLite3, or SQLiteDatabase). PHP version 5.3.0 and greater usually comes with the SQLite3 extension installed and enabled by default so no custom action is necessary. 

To determine your PHP version, create a new file with this PHP code: `<?php echo PHP_VERSION; // version.php`. This will print your version number to the screen.

## Install

### Backend installation

1. Insure that you have the required components.
2. Download LeaderboardC2 or cloning this Github repo.
3. Upload LeaderboardC2 through FTP/SFTP or whatever upload method you prefer to the public-facing directory of your site.
4. Ensure that the permissions for the `data` folder and `yourbase.sqlite` file are set to `0777`.

### Plugin installation

#### Manual 

1. Close Construct 2.
2. Checkout the 'master' branch from this repository.
3. Copy the contensts of the folder __addon/files/leaderboard/__ into __CONSTRUCT2_INSTALLATION_FOLDER\exporters\html5\plugins\leaderboard__.

#### Automatic

Drag and drop the [leaderboard.c2addon](https://github.com/ohtejera/leaderboardC2/raw/master/addon/leaderboard.c2addon)
 in to the Construct2 window to install it. 
 
[Installing third-party addons](https://www.scirra.com/manual/158/third-party-addons)

## Plugin configuration

The plugin can be configured by selecting the object __Leaderboard__  from the __Object types__ list inside Construct2.

### Plugin Properties

+ __Leaderboard service URL__ (The Leaderboard service URL)
+ __Game ID__ (The game id. Should be equal to the SQLite file name without the .sqlite extension)
+ __Magic number__ (The magic number. Should be equal to the 'magic_number' property in the [config.ini](https://github.com/ohtejera/leaderboardC2/blob/master/config.ini) file)
+ __Magic key__ (The magic key. Should be equal to the 'magic_key' property in the [config.ini](https://github.com/ohtejera/leaderboardC2/blob/master/config.ini) file)
+ __Log requests__ (Sends request URLs into console. For debugging purposes)


![plugin](https://cloud.githubusercontent.com/assets/3797402/6205781/2e1612e8-b561-11e4-811d-af0b15383a17.jpg)

### Plugin Actions

+ __Set timeout__ (Set the maximum time before a request is considered to have failed)
+ __Set request header__ (Set a HTTP header on the next request that is made)
+ __Request Top Scores__ (Top Scores request returns an array of scores to your function where you can display the data in your Leaderboard)
+ __Get the player's last rank__ (Get the player's last rank)
+ __Get the player's best rank__ (Get the player's best rank)
+ __Submit score__ (Submit player's score)

![actions](https://cloud.githubusercontent.com/assets/3797402/6205836/20560490-b563-11e4-9556-ac87686c9d3a.jpg)

### Plugin Conditions

+ __On Top Scores completed__ (Triggered when a Top Scores request completes successfully)
+ __On Get Best Rank completed__ (Triggered when a Get Best Rank request completes successfully)
+ __On Get Last Rank completed__ (Triggered when a Get Last Rank request completes successfully)
+ __On Add Score completed__ (Triggered when a Add Score request completes successfully)

![events](https://cloud.githubusercontent.com/assets/3797402/6205887/a1f05e00-b564-11e4-822e-227d24862bf9.jpg)

## Examples

### Live demo

http://htejera.ukelelestudio.com/leaderboardc2/demo/

### Construct2 project

This project comes with a .capx as a reference to know how to use it, you can download it from [here](https://github.com/ohtejera/leaderboardC2/raw/master/c2Example/Leaderboard.capx).

### Some projects that use LeaderboardC2

+ Integers [Google Play](https://play.google.com/store/apps/details?id=com.ukelelestudio.integers)
+ Simple   [Web](http://games.ukelelestudio.com/simple/)


## Do you need a SQLite manager?

Try [phpLiteAdmin](https://code.google.com/p/phpliteadmin/)!

__phpLiteAdmin__ is a web-based SQLite database admin tool written in PHP with support for SQLite3 and SQLite2. 

## Need help?

If you need to get in touch with me, you can reach me at __henrytejera At gmail.com__

### Construct 2 forum

https://www.scirra.com/forum/plugin-a-self-hosted-open-source-php-leaderboard-for-con_t125116


## Contribution Guidelines

Please submit issues to __ohtejera/leaderboardC2__ and pull requests to *-dev branches.

![gauchoiwantyou2](https://cloud.githubusercontent.com/assets/3797402/6204483/3f8bddbe-b534-11e4-9966-fbc78e8d8161.gif)

## Powered by

[Fat-Free framework](http://fatfreeframework.com/home). A powerful yet easy-to-use PHP micro-framework .

## License

The MIT License (MIT)

Copyright (c) 2015 Henry Tejera

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:
The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.


