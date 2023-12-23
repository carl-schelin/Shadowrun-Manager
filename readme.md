### Overview

This is a Shadowrun RPG Fixer and Character Manager. Years ago I created an AD&D character manager called 
The Computerized Dungeon Master (see my Memorbelia repo) and thought I could create something similar for 
my Shadowrun games. As always, it tends to extend further and further.

### Goals

I have two goals for the application. The obvious thing is managing characters. A player can use this, enter in their 
character, and even manage their social information. As a Fixer app, the idea is characters can be used by other GMs 
to have them available as encouter teams or even NPCs in their game. The GM can update the character history so 
the player can see that they do other things in the couple of months between runs.

### Progress

I need to review the code to align with the new coding requirements I've set for myself. Some are probably already here 
as I continue to learn as I create and maintain various sites. They're documented in the wiki just FYI.

### Installation

In the 'install' directory is a mysql.sql file. This is all the tables that are used by the system. This file 
will always be current. If you have a running system, there's also a CHANGEME file that shows the changes from 
the original 1.0 installation forward so you can apply them.

There should also be a data file. This file contains various commands and information needed in order to have the site start 
up properly. Eventually I'll have an installation script. Eventually.

In addition, there is a settings.php file that is linked into each subdirectory off of the root directory of the site. 
You'll need to update that to add the necessary values for accessing mysql on your server. See the settings.md file 
for details.

In the css directory is a readme.md file that provides information on the jQuery version I'm using.

And you'll need the few images from the img directory for things like the Shadowrun logo and waiting graphics. 
See http://manager.shadowrun.industries/imgs for the files I have.

### Data

The system was originally written for 5th Edition. I've been expanding it so it has data from 1st through 6th.

The data is taken from the 1st Edition on up through 6th. I've added bits in order to accommodate various differences in 
the different systems, in part because I want to see if some gear has been there from the beginning and what the price 
changes might be (and other stats).

Anyway, keep an eye here as I get the system working. Since I use it for my games, it should be reasonably accurate. 
Some things just can't be added. I'm mainly looking to add things that a character might need to know/see.

