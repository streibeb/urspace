<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//bonusMarks function
//objective: take comments string, and replace all instances
//	of #text and @username and replace with a link to search that specific
//	item.
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function bonusMarks($input){
	
	// seperate input string into each individual word
	$array = explode(" ", $input);
	
	//cycle through all individual words
	foreach($array as &$value)
	{
		// if a word starts with # then add link for text search
		if(isset($value[0]) && $value[0] == "#")
		{
		
			$value = '<a href="search.php?search2='.ltrim($value, "#").'">'.$value.'</a>';
		}
		// if a word starts with @ then add link for user search
		
		if(isset($value[0]) && $value[0] == "@")
		{
			$value = '<a href="search.php?search1='.ltrim($value, "@").'">'.$value.'</a>';
		}	
	}
	
	return implode(" ",$array);

}



?>