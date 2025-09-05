<?php
	// PREV
	echo "<table align=left border=1 cellpadding=5 cellspacing=0><tr><td align='left'>";
	if($back >=0) {
		print "<a href='view-c.php?start=$back'><font face='Verdana' size='2'>PREV</font></a>";
	}
	else {
		print "<font face='Verdana' size='2'>PREV</font>";
	}
	
	// NUMBERS
	echo "</td><td align=center>";
	$i=0;
	$l=1;
	for ($i=0; $i < $num_rows_in_db; $i=$i+$limit) {
		if($i <> $start) {
			echo " <a href='view-c.php?start=$i'><font face='Verdana' size='2'>$l</font></a> \n";
		}
		else {
			echo "<font face='Verdana' size='4' color=red>$l</font> \n";
		} /// Current page is not displayed as link and given font color red
		$l=$l+1;
	}
	// NEXT
	echo "</td><td align='right'>";
	if ($thispage < $num_rows_in_db) {
		print "<a href='view-c.php?start=$nextpage'><font face='Verdana' size='2'>NEXT</font></a>";
	}
	echo "\n</td></tr></table>\n";
?>
