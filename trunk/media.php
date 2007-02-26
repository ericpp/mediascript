<?
######################################
#      Eric's Media Script v1.2-php  #
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
# Remove the # to enable
#$accesslog = "access.log";

# Name of the file which will log errors from this script
# Remove the # to enable
#$errorlog = "error.log";

function Playfile() {
	global $mediadat,$HTTP_SERVER_VARS;
	if(!$HTTP_SERVER_VARS['QUERY_STRING']) {
		print "<title>Error</title>\n";
		print "<center><font face=Arial,Helvetica><h1>Error</h1></center></font><p>\n";
		print "<center><font face=Arial,Helvetica>You must enter the name of the file</font></center>\n";
		LogError("You must enter the name of the file");
		exit;
	}
	else {
		$fd = fopen($mediadat,"r") or die(LogError("Unable to open data file"));
		while($Line = chop(fgets($fd,4096))) {
       	 	list($name, $mime, $locat) = explode("|",$Line);      
			if($HTTP_SERVER_VARS['QUERY_STRING'] == $name) {
				$salt = rand(0,1000000000);
				header("Content-type: $mime\nContent-Disposition: inline; filename=media$salt.ram\n\n");
				print $locat;
				LogAccess("Access: $name -> $locat");
				exit;
			}
		}
	}
	print "<title>Error</title>\n";
	print "<center><font face=Arial,Helvetica><h1>Error</h1></center></font><p>\n";
	print "<center><font face=Arial,Helvetica>The name '".$HTTP_SERVER_VARS['QUERY_STRING']."' was not found in the data file.</center></font><p>\n";
	LogError("The name '".$HTTP_SERVER_VARS['QUERY_STRING']."' wasn't found in the data file");
	exit;
	return 1;
}

function Leech() {
	global $HTTP_SERVER_VARS;
        print "<title>Sorry</title>\n";
        print "<center><font face=Arial,Helvetica><h1>Sorry</h1></center></font><p>\n";
        print "<center><font face=Arial,Helvetica>You may not link to my media files</font></center>\n";
	LogError("Leech: ".$HTTP_SERVER_VARS['HTTP_REFERER']);
	return 1;
}

function LogAccess($accessmsg) {
	global $accesslog,$HTTP_SERVER_VARS;
	if($accesslog) {
		$dtime = strftime("[%d/%b/%Y:%H:%M:%S]",time());  
		if(!$accessmsg) { return 0; }
		$ad = @fopen($accesslog,"a") or die(LogError("Unable to open access log: $accesslog"));
		flock($ad,2);
		fputs($ad,$HTTP_SERVER_VARS['REMOTE_ADDR']." - - $dtime \"$accessmsg\"\r\n");
		flock($ad,0);
		fclose($ad);
	}
	return 1;
}

function LogError($errormsg) {
	global $errormsg,$HTTP_SERVER_VARS;
	if($errorlog) {
		$dtime = strftime("[%d/%b/%Y:%H:%M:%S]",time());  
		if(!$errormsg) { return 0; }
		$ed = fopen($errorlog,"a");
		flock($ed,2);
		fputs($ed,$HTTP_SERVER_VARS['REMOTE_ADDR']." - - $dtime \"$errormsg\"\r\n");
		flock($ed,0);
		fclose($ed);
	}
	return 1;
}

$af = fopen($anticfg,"r") or die(LogError("Unable to open domain file: $anticfg"));
while($urlname = chop(fgets($af,4096))) {
	preg_replace("/\|$/","//",$urlname);
	if(preg_match("|(\w+)://([^/:]+)(:\d+)?/(.*)|",$HTTP_SERVER_VARS['HTTP_REFERER'],$r)) {
		if($urlname == $r[2]) {
			Playfile();
			exit;
		}
	}
	if(($urlname && preg_match("|^".$urlname."|",$HTTP_SERVER_VARS['HTTP_REFRER'])) || $HTTP_SERVER_VARS['HTTP_REFERER'] == $urlname) {
		Playfile();
		exit;
	}
}
if(preg_match("/^http:\/\/".$HTTP_SERVER_VARS['SERVER_NAME']."/",$HTTP_SERVER_VARS['HTTP_REFERER'])){
	Playfile();
	exit;
}
fclose($af);

if ($antiopt == "yes") {
	Leech();
	exit;
}
else {
	Playfile();
	exit;
}
?>
