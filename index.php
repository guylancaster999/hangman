<?php session_start(); ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="description" content="Hangman"/>
    <meta name="author" content="Guy Lancaster"/>
    <meta name="keywords" content="Hangman">
    <title>Hangman</title>
    <link href="css/main.css" rel="stylesheet"/>
 </head>
 <body>
	<h1>Hangman</h1>
	<?
	// load last state-------------------------------------------------------------------
	$moveNo		= $_REQUEST["moveNo"];
	$chosen 	= $_REQUEST["chosen"];
	$selectable = $_SESSION["selectable"];
	$livesLost 	= $_SESSION["livesLost"];
	$got 		= $_SESSION["got"];
	$word		= $_SESSION["word"]		;

	if (!isset($moveNo))$moveNo	= 0;

	if ($moveNo==0)
	{	
		// startup---------------------------------------------------------
		$words					= array("AUTHORS","VIOLIN","SWORDS","OBJECT","ELEPHANT");
		$ptr					= rand(0,count($words)-1);
		$word					= $words[$ptr];
		$got 					= "";
		$selectable				="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$livesLost				= 0;
		for ($i=0;$i<strlen($word);$i++) $got.="-";
	}
	else
	{
		//process letter---------------------------------------------------------------
		$foundCtr 	= substr_count($word,$chosen);
		$newGot 	= "";

		if ($foundCtr>0) // good choice 
						 // replace letters in display of letters so far got
						 //(e.g. -A----  becomes BA---- for BADGER if B is chosen)	
		{
			for ($i=0; $i<strlen($word); $i++)
			{
				$c = substr($word,$i,1);
				$d = substr($got, $i,1);
				$newGot.= ($c==$chosen ? $c : $d);
			}
			$got 		= $newGot;
		}
		else //bad choice
		{	
			$livesLost++;
		}
		$selectable	= str_replace($chosen,"-",$selectable);
	}
	?>
	<!-- draw hangman -->
	<div class='graphic'>
		<a href="#" title="Hangman">
			<img src="img/hg<?php print $livesLost;?>.png" alt="Hangman" />
		</a>
	</div>
	<div class='txt'>
	<?php
	// display 
	$moveNo++;
	print "Move ".$moveNo."<br/><br/>";
	print  str_ireplace("-"," &ndash; ",$got)."<br/><br/>"; 

	// post session state
	$_SESSION["selectable"] = $selectable;
	$_SESSION["livesLost"]	= $livesLost;
	$_SESSION["got"]		= $got;
	$_SESSION["moveNo"]		= $moveNo;
	$_SESSION["word"]		= $word;

	//create a letter bar so you can choose a letter 
		$select="";	
		for ($i=0; $i<=25; $i++)
		{	
			$c = substr($selectable,$i,1);
		
			if ($c=="-")
			{
				$select.=" &ndash; ";
			}
			else
			{
				$link='index.php';
				if (($livesLost<6)|| (substr_count($got,"-")>0))
				{
					$link.='?moveNo='.$moveNo;
					$link.='&amp;chosen='.$c;
				}
				$select.='<a href="'.$link.'">'.$c.'</a>&nbsp;';
			}
		}
		print $select;
		
	// declare win or loose - and start again
		print "<br/><br/>";
		if ($livesLost>=6)
		{
			print '<form action="index.php">
					<input type="submit" value="You have lost - play again" />
				</from>';
		}
		if (substr_count($got,"-")==0)
		{
			print '<form action="index.php">
						<input type="submit" value="You won - play again" />
				  </from>';
		}	
		?>
		</div>
	</bodY>
</html>