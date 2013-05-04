<?php
echo "---------------------------------------";
echo "\n";
echo "List of the debts to ".$user.":";
echo "\n";
echo "---------------------------------------";
echo "\n";
if(isset($debtdetails)){		
    foreach ($debtdetails as $row){
			$id=$row->ID;
			$description=$row->DESCRIPTION;
			$sum=$row->SUM;
			$date=date("d.m.Y", strtotime($row->DATE));
			
		echo "Date: ".$date."  Description: ".$description."  SUM: ".$sum;
		echo "\n";	
    }
    }


echo "\n";
echo "In total this makes : ".$debtsum;
echo "\n";
echo "\n";
echo "Please pay soonish!!";
echo "\n";
echo "\n";
echo "Please mark when you have paid:";
echo "\n";
echo $claimurl;
?>
