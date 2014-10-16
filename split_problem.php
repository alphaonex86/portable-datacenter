<?php
// cron used: *       *       *       *       *       /usr/bin/wget /usr/bin/wget -q "http://munin/problems.html" -O /tmp/problems.html > /dev/null 2>&1 && /usr/bin/php -f split_problem.php > /dev/null 2>&1
function filewrite($file,$content)
{
	if($filecurs=fopen($file, 'w'))
	{
		if(fwrite($filecurs,$content) === false)
			die('Unable to write the file: '.$file);
		fclose($filecurs);
	}
	else
		die('Unable to write or create the file: '.$file);
}
$content=file_get_contents('/tmp/problems.html');
if($content=='')
{
	echo 'No content';
	exit(3);
}
if(preg_match('#^.*id="critical"(.*)id="warning".*$#isU',$content))
{
        preg_match_all('#<span class="nodetitle">'."[\n\r\t ]*".'<a href="[^"]*">'."[\n\r\t ]*".'(.*)</a>#isU',preg_replace('#^.*id="critical"(.*)id="warning".*$#isU','$1',$content),$out);
        $arr=array();
        foreach($out[1] as $entry)
                $arr[]=preg_replace("#[\n\r\t ]#",'',$entry);
	filewrite('/tmp/problems-critical.html',implode("\n",array_unique($arr)));
}
else
	unlink('/tmp/problems-critical.html');
if(preg_match('#^.*id="warning"(.*)id="unknown".*$#isU',$content))
{
	preg_match_all('#<span class="nodetitle">'."[\n\r\t ]*".'<a href="[^"]*">'."[\n\r\t ]*".'(.*)</a>#isU',preg_replace('#^.*id="warning"(.*)id="unknown".*$#isU','$1',$content),$out);
	$arr=array();
	foreach($out[1] as $entry)
		$arr[]=preg_replace("#[\n\r\t ]#",'',$entry);
        filewrite('/tmp/problems-warning.html',implode("\n",array_unique($arr)));
}
else
        unlink('/tmp/problems-warning.html');
if(preg_match('#^.*id="unknown"(.*)id="footer".*$#isU',$content))
{
        preg_match_all('#<span class="nodetitle">'."[\n\r\t ]*".'<a href="[^"]*">'."[\n\r\t ]*".'(.*)</a>#isU',preg_replace('#^.*id="unknown"(.*)id="footer".*$#isU','$1',$content),$out);
        $arr=array();
        foreach($out[1] as $entry)
                $arr[]=preg_replace("#[\n\r\t ]#",'',$entry);
        filewrite('/tmp/problems-unknown.html',implode("\n",array_unique($arr)));
}
else
        unlink('/tmp/problems-unknown.html');
