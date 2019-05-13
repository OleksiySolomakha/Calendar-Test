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
<h2> Тестирование регулярных выражений </h2>

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
echo str_replace("regular", "<em>regular</em>", $string);

// use function preg_replace(search, replace, subject) for show it in work
echo preg_replace("/regular/", "<em>regular</em>", $string);




?>
</body>
</html>