=====================================
|Media v1.2php                      |
|by Eric                            |
|http://ericpp.n3.net/scripts/media/|
|ericpp@bigfoot.com                 |
=====================================

This is the README for the PHP version of the Media script.
Many webhosting companies have stopped using Perl and have now moved to 
PHP which is why I have translated my script into PHP.

1. INTRODUCTION
---------------
This was my first publicly available script.
It solves the problem of having to create .ram, .mov, and other files
to use streaming media on your site.
It also has a simple leech killer built into it so others won't leech
off your files.

2. CONFIGURATION
----------------
There are three things you need to configure

MEDIA.PHP   Actual Script (PHP version)

ALLOW.M     File that contains the names of the domains
            to allow to link to your files

DATA.M      File that tells where your files are

ACCESS.LOG  File that contains records of accesses to script

ERROR.LOG   File that contains error records of script


A) The MEDIA.PHP file

   $mediadat = "data.m"       - Edit this line if you want to name
                                the data file something other than
                                data.m
                                I recommend placing this file in a folder
                                that cannot be accessed from the internet.

   $antiopt = "yes"           - Delete this line if you don't want
                                to use the leech killer

   #$anticfg = "allow.m"      - Edit this line if you want to name
                                the anti-link file something other
                                than allow.m
                                I recommend placing this file in a folder
                                that cannot be accessed from the internet.

   #$accesslog = "access.log" - Remove the  #  in front of this line to
                                enable the logging of accesses to this 
                                script.
                                Edit this line to change the name of the
                                log file.
                                I recommend placing this file in a folder
                                that cannot be accessed from the internet.

   #$errorlog = "error.log";  - Remove the  #  in front of this line to
                                enable the logging of errors from this 
                                script.
                                Edit this line to change the name of the
                                log file.
                                I recommend placing this file in a folder
                                that cannot be accessed from the internet.


B) The DATA.M file

   Here is an example of what should go in here:
   greenday|audio/x-pn-realaudio|http://www.server.com/~yoursite/greenday.ra
      ^             ^                               ^
      |             |                               |
    Name        Mime Type                    Where the file is

    The name is whatever you want to name it.

    The mime type depends on what type of file it is.
        RealAudio(.ra)  =  audio/x-pn-realaudio
        RealVideo(.rv)  =  video/vnd.rn-realvideo
        RealMedia(.rm)  =  application/vnd.rn-realmedia
        MediaPlyr(.asx) =  video/x-ms-asf
        Quicktime(.mov) =  video/quicktime
        MP3 files(.mp3) =  audio/x-mpegurl

        You can figure out the mime types in Windows 9x or XP by going 
        into 'My Computer' click 'View' then click 'Options'. Then click
        on the 'File Types' tab. Then highlight a file type and it will
        show the mime type on the bottom of the window.

    Seperate each part with a | (pipe)


C) The ALLOW.M file

   This is the file that says which pages or domains are allowed to link to
   the script.

   Here is an example of what could go in here:
   www.server.com
   http://www.server.com/
   http://www.server.com/~yoursite/
   http://www.server.com/~yoursite/page.html

   NOT server.com or .server.com

   Basically just list domains or partial addresses of all the pages
   that have your media files on them.
   You can leave the file blank if you are running the script from the
   same domain as your page.


D) Chmod

   Chmod these files in a UNIX shell or a FTP session

   chmod 644 allow.m
   chmod 644 data.m

   Depends on your server: 
   chmod 644 access.log  *OR*  chmod 666 access.log
   chmod 644 error.log  *OR*  chmod 666 error.log

E) Your page

   Make your links on your page point to
   media.php?songname
   (replace songname with what you named that particular song)

   Example:
   what used to be
   http://www.server.com/~yoursite/greenday.rm
   would now be
   http://www.server.com/~yoursite/media.php?greenday


3. END
------
Hopefully this was helpful in configuring my script.

