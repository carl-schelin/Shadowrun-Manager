### Overview

This is a Shadowrun RPG Fixer and Character Manager. Years ago I created an AD&D character manager called 
The Computerized Dungeon Master (see my Memorbelia repo) and thought I could create something similar for 
my Shadowrun games. I actually started one based on my TCDM program but it never got further than the skills 
matrix.

To learn Javascript, for Shadowrun 4th, I created some javascript based scripts specifically for combat 
encounters plus a set of scripts that validated PreGen characters.

I learned PHP programming for another application and used that knowledge to create this one for Shadowrun 
5th. With the release of Shadowrun 6th, I extended the program to work with it and then started looking back 
to 5th, 4th, and even back to 1st. In large part because I wanted to compare stats between editions. How 
much does a Limo cost in 1st compared to 4th compared to 6th. What big changes happened between versions 
such as Cyberdecks and even Cyberjacks.

### Goals

I have two goals for the application. The obvious thing is managing characters. A player can use this, enter in their 
character, and even manage their social information. As a Fixer app, the idea is characters can be used by other GMs 
to have them available as encounter teams or even NPCs in their game. The GM can update the character history so 
the player can see that they do other things in the couple of months between runs.

### Progress

I need to review the code to align with the new coding requirements I've set for myself. Some are probably already here 
as I continue to learn as I create and maintain various sites. The coding standards are documented in the wiki just FYI.

### Installation

In the 'install' directory is a mysql.sql file. This lists all the tables that are used by the system. This file 
will always be current. If you have a running system, there's also a CHANGEME file in the same directorry that shows 
the changes from the original 1.0 mysql installation forward so you can apply them.

There should also be a data file. This file contains various commands and information needed in order to have the site start 
up properly. Accounts, drop down elements, etc. Eventually I'll have an installation script. Eventually.

In addition, there is a settings.php file that is linked into each subdirectory off of the root directory of the site. 
You'll need to update that to add the necessary values for accessing mysql on your server. See the settings.md file 
for details.

In the css directory is a readme.md file that provides information on the jQuery version I'm using.

And you'll need the few images from the img directory for things like the Shadowrun logo and waiting graphics. 
See http://manager.shadowrun.industries/imgs for the files I have.

For the PDF tool, I'm using tcpdf in the DocumentRoot directory called, 'tcpdf.manager'. The pdf/character.manager.php 
file is the only place this is called in case you want to have it in a different directory.

Finally there's an option to display some information as a 'Guest'. The '/guest.php' file is imported everywhere 
when when building, you'll either copy the login.php or nologin.php script over guest.php depending on your 
requirements.

### Data

I did ask Catalyst back when I was working on it for 5th and the response was basically as long as I don't replicate 
the books, I'm good. So the data is from the tables, however expanded descriptions are in the books and the data 
in the code provides the book and page number.

The system was originally written for 5th Edition. I've been expanding it so it has data from 1st through 6th.

The data is taken from the 1st Edition on up through 6th. I've added code in order to accommodate various differences in 
the different systems, in part because I want to see if some gear has been there from the beginning and what the price 
changes might be (and other stats).

For the data elements, where you as the admin enter data, you'll have blanks for all options. For the characters however, 
they should only see data for the specific Edition their character was created in.

Anyway, keep an eye here as I get the system working. Since I use it for my games, it should be reasonably accurate. 
Some things just can't be added or I hand wave it for now while I work on the code. I'm mainly looking to add things 
that a character might need to find in the books.

