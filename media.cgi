#!/usr/bin/perl
######################################
#      Eric's Media Script v1.2      #
# http://ericpp.n3.net/scripts/media #
#         ericpp@bigfoot.com         #
######################################


# Name of the configuration file
$mediadat = "data.m";

# Set $antiopt = "yes" to protect your files
# from being linked to other people's sites
$antiopt = "yes";

# Name of the file that contains the names of the
# domains that are allowed to link to the script
$anticfg = "allow.m";

# Name of the file which will log accesses from this script
$accesslog = "access.log";

# Name of the file which will log errors from this script
$errorlog = "error.log";

#################################
#   Don't edit past this line   #
#################################

use POSIX qw(strftime);
use POSIX qw/ceil/;

sub Playfile {
	open(FILE,"$mediadat") or die(&LogError("Unable to open data file"));
	@Data = <FILE>;
	close(FILE);
	foreach $Line (@Data) {
		chomp($Line);
        	($name, $mime, $locat) = split(/\|/,$Line);      
		if($ENV{'QUERY_STRING'} eq "$name") {
			$salt = ceil(rand()*1000000000000000);
			print "Content-type: $mime\n";
			print "Content-Disposition: inline; filename=media$salt.ram\n\n";
			print "$locat";
			&LogAccess("Access: $name -> $locat");
			exit;
		}
	}

	if($ENV{'QUERY_STRING'} eq "") {
		print "Content-type: text/html\n\n";
		print "<title>Error</title>\n";
		print "<center><font face=Arial,Helvetica><h1>Error</h1></center></font><p>\n";
		print "<center><font face=Arial,Helvetica>You must enter the name of the file</font></center>\n";
		&LogError("You must enter the name of the file");
		exit;
	}
	else {
		print "Content-type: text/html\n\n";
		print "<title>Error</title>\n";
		print "<center><font face=Arial,Helvetica><h1>Error</h1></center></font><p>\n";
		print "<center><font face=Arial,Helvetica>The name '$ENV{'QUERY_STRING'}' was not found in the data file.</center></font><p>\n";
		&LogError("The name '$ENV{'QUERY_STRING'}' wasn't found in the data file");
		exit;
        }
}

sub Leech {
        print "Content-type: text/html\n\n";
        print "<title>Sorry</title>\n";
        print "<center><font face=Arial,Helvetica><h1>Sorry</h1></center></font><p>\n";
        print "<center><font face=Arial,Helvetica>You may not link to my media files</font></center>\n";
	&LogError("Leech: $ENV{'HTTP_REFERER'}");
}

sub LogAccess {
	if($accesslog) {
		$accessmsg = shift(@_);
		$dtime = strftime "[%d/%b/%Y:%H:%M:%S]", localtime;  
		if(!$accessmsg) { return 0; }
		open(ACCESS,">>$accesslog") or die(&LogError("Unable to open access log: $accesslog"));
		flock(ACCESS,2);
		print ACCESS "$ENV{'REMOTE_ADDR'} - - $dtime \"$accessmsg\"\r\n";
		flock(ACCESS,8);
		close(ACCESS);
	}
}

sub LogError {
	if($errorlog) {
		$errormsg = shift(@_);
		$dtime = strftime "[%d/%b/%Y:%H:%M:%S]", localtime;  
		if(!$errormsg) { return 0; }
		open(ERROR,">>$errorlog");
		flock(ERROR,2);
		print ERROR "$ENV{'REMOTE_ADDR'} - - $dtime \"$errormsg\"\r\n";
		flock(ERROR,8);
		close(ERROR);
	}
}

open(AFILE,"$anticfg") or die(&LogError("Unable to open domain file: $anticfg"));
if(eof(AFILE)) {
	if($ENV{'HTTP_REFERER'} =~ "^http:\/\/$ENV{'SERVER_NAME'}(.*)"){
		&Playfile;
		exit;
	}
}
else {
	foreach $urlname (<AFILE>) {
		chomp($urlname);
                $urlname =~ s/\|$//;
		$ENV{'HTTP_REFERER'} =~ m|(\w+)://([^/:]+)(:\d+)?/(.*)|;
		if($urlname eq $2) {
			&Playfile;
			exit;
		} 
		if($ENV{'HTTP_REFERER'} =~ "^$urlname(.*)" || $ENV{'HTTP_REFERER'} eq $urlname) {
			&Playfile;
			exit;
		}
	}
}
close(AFILE);

if ($antiopt eq "yes") {
	&Leech;
	exit;
}

else {
	&Playfile;
}


