<?php

function cleanInt($input)
{
	if(isset($input))
	{
		//get rid of html, php tags. Remove whitespace and beginning and end of input
		$output = htmlspecialchars(strip_tags(trim($input), ENT_NOQUOTES)); 

		$output2 = (int)$output;
		$output3 = abs($output2);
		if($output2 == 0)
		{
			return false;
			exit();
		}		
		else
		{
			return $output3;
		}
	}
}

function cleanString($input)
{
	if(isset($input))
	{
		$output = htmlspecialchars(strip_tags(trim($input), ENT_NOQUOTES));
		return $output;
	}
}

?>