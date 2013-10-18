<?php
/*
http://www.serial-graphics.be
info@serial-graphics.be
*/





// START CONFIGURATION
	$domain = $_SERVER[SERVER_NAME];
// END CONFIGURATION




// START SITE_VARS
	$dir=dirname($HTTP_SERVER_VARS[PHP_SELF]);	//browsing dirname
// START SITE_VARS




// START FUNCTIONS

Function makesize($size)	//	make filesize 0KB/KB/MB/GB
	{
	if ($size == 0)
		return ("0K");
	elseif ($size < 1024)
		return ("1KB");	//if filesize is < 1K size is 1K
	elseif (@floor($size / 1024) < 1024)
		return @floor($size / 1024)."KB";
	elseif (@floor(($size / 1024)/1024) < 1024)
		return @floor(($size / 1024)/ 1024)."MB";
	else
		return @floor((($size / 1024)/ 1024)/ 1024)."GB";
	}

// END FUNCTIONS


// creating 2 arrays $dirs  $files 

$countdirs = 0;
$countfiles = 0;
$dirs = array();
$files = array();

if ($handle = @opendir("."))
	{
	while (false !== ($file = readdir($handle)))
		{
		if (is_dir($file))
			{
			if ($file != ".svn" && $file != ".")
				{
				$dirs[$countdirs]=$file;
				$countdirs++;
				}
			}
		elseif (is_file($file))
			{
			$allowed_ext = array("jpg","gif","png");
			$check_ext = strtolower( substr(strrchr($file, "."), 1) );	// read extension in lowercase
			
			if ( in_array($check_ext,$allowed_ext) )	// check if extension is in allowed list
	        	{
	       		$files[$countfiles]=$file;    // write file in the array
				$countfiles++;    //filecount +1
				}
			}
		}
	}



?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<!--

copyright:
http://www.serial-graphics.be
info@serial-graphics.be

-->

<html>
<head>
	<title><?php echo ("Browsing http://$domain$dir/"); ?></title>
	
	<style>
		body{ background-color: #000000;  font-size: 8px;  font-family: Arial, Helvetica, sans-serif; }
		td {font-size:10px;}
		td.titel {color:#FFFFFF;font-size:12px; font-weight:bold;}
		tr.kop {color:#FFFFFF; background-color:#666666;}
		tr.file0 {color:#000000; background-color:#CCCCCC;}
		tr.file1 {color:#FFFFFF; background-color:#AAAAAA;}
		a { color: #000000;  text-decoration: none;  }
		a:hover { color: #FFFFFF; }
	</style>
</head>

<body>

<table cellspacing="0" border="0">
	<tr>
		<td colspan="4" class="titel"><?php echo ("index of http://$domain$dir/");?></td>
	</tr>
	<tr class="kop">
		<td width="20">&nbsp;</td><td width="200">name</td><td width="200">Last modified</td><td width="50">size</td>
	</tr>
<?php
if (is_array($dirs))
	{
	// sort array
		asort($dirs); 
		reset($dirs);
	// show dir list
		while(list ($key, $name) = each ($dirs)) 
			{
		    echo ("
			<tr class='file0' onmouseover=\"this.style.background='#AAAAAA'\" onmouseout=\"this.style.background='#CCCCCC'\" onclick=\"location.href='$name'\">
				<td></td><td><a href='$name/'>$name</a></td><td></td><td></td>
			</tr>
			");
			}
	}

// files sorteren
if (is_array($files) && count($files)>0)
	{ 
	// sort array
		asort($files);
		reset($files); }
	// show file list
	while(list ($key, $name) = each ($files)) 
		{
		list ($Src['Width'],$Src['Height']) = getImageSize($name);
		$grootte = filesize($name);
		$filedate= date("d-m-Y", fileatime($name))."&nbsp;&nbsp;&nbsp;".  date("H:i:s", fileatime($name));
		$grootte = makesize($grootte);
    	echo ("
			<tr class='file0'  onmouseover=\"this.style.background='#AAAAAA'\" onmouseout=\"this.style.background='#CCCCCC'\" onclick=\"location.href='$name'\">
				<td><img src=\"$name\" width=\"".$Src['Width']."\" height=\"".$Src['Height']."\" alt=\"$name\"></td><td><a href='$name'>$name</a></td><td>$filedate</td><td>$grootte</td>
			</tr>
			");
	}

?>
</table>

</body>
</html>