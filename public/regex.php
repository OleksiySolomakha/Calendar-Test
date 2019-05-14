<!DOCTYPE html>
<html>
<head>
	<meta lang="en", xml:lang="en"/>
	<title>Regular Expressions demonstration</title>
	<style type="text/css">

		em{
			background-color: #FF0;
			border-top: 1px solid #000;
			border-bottom:  1px solid #000;
		}

	</style>
</head>
<body>

<?php  

//  text example 
$string=<<<TEST_DATA
<h2> Regular Expression Testing</h2>

<p>
	In this document, there is a lot of text that can be matched using regex.
	The benefit of using a regular expression is much more flexible &mdash;
	syntax for text pattern matching.
</p>
<p>
	After you get the hang of regular expressions, also called regexes, they 
	will become a powerfull tool for pattern matching.
</p>
<hr />
TEST_DATA;

// use function str_replace(search, replace, subject) for show it in work
//modify by "i" make no sansetive to register

// $check1 = str_replace("regular", "<em>regular</em>", $string);
// echo str_replace("Regular", "<em>Regular</em>", $check1);

// // use function preg_replace(search, replace, subject) for show it in work
// //echo preg_replace("/regular/i", "<em>regular</em>", $string);

// echo preg_replace("/(regular)/i", "<em>$1</em>", $string);

// Using regular expressions for visible mark all letter a-c

// $pattern = "/([a-c])/i";

//word with 4 letters

//$pattern = "/(\b\w{4}\b)/";

//words with 4 , 6, 7 letters

// $pattern = "/(\b(\w{4}\b | \b\w{6,7})\b)/";

//show optional elements 

// $pattern = "/(expressions?)/i";

//show all functionality	

// $pattern = "/(reg(ular\s)?ex(pressions? | es)?)/i";

// echo preg_replace($pattern,"<em>$1</em>",$string);

// //show this pattern 

// echo "\n<p>We use pattern: <strong>$pattern</strong></p>";

// work with date from calendar
//some string for check work

$date[] = '2010-01-14 12:00:00';

$date[] = 'Saturday, May 14th at 7pm';

$date[] = '02/03/10 10:00pm';

$date[] = '2010-01-14 102:00:00';

//pattern for checking normal date

$pattern = "/^(\d{4}(-\d{2}){2} (\d{2})(:\d{2}){2})$/";  //{2}after \d{2} dublicate this pattern! for month

foreach ($date as $d) {

	echo "<p>", preg_replace($pattern, "<em>$1</em>", $d), "</p>";
	
}

echo "\n<p>We use pattern: <strong>$pattern</strong></p>";

?>
</body>
</html>