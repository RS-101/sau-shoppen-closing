<?php

	$code = trim(preg_replace('/\s\s+/', ' ', $_POST['c']));

	$url = trim(preg_replace('/\s\s+/', ' ', utf8_encode(file_get_contents('https://skema.ku.dk/tt/tt.asp?SDB=SUND2526&language=DK&folder=Reporting&style=textspreadsheet&type=programme+of+study&idtype=id&id=' . $_POST['a'] . '&weeks=27-52&days=1-5&periods=1-68&width=0&height=0&template=SWSCUST2+programme+of+study+textspreadsheet'))));

	$count = 0;
	while ($count <= 1) {
    $count = substr_count($url, $code);
		$code = substr($code, 0, strlen($code)-1);
	}
  $code = substr($code, 0, strlen($code)-1);
  $count = substr_count($url, $code);
	$count += 1;

	$activities = array();
	$start = 0;
	for ($i = 0; $i < $count; $i++) {
		$start = strpos(strtolower($url), strtolower($code), $start) - 9;
		$stop = strpos(strtolower($url), '</tr>', $start + 20) + 5;
		$len = $stop - $start;
		array_push($activities, substr($url, $start, $len));
		$start += 20;
	}

	echo(json_encode($activities));

?>
