
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html><head>
<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type"><title>DebtNagger</title>
<link rel="stylesheet" href="<?php echo base_url();?>css/styles.css">

</head>
<body class="background">
<table class="table">
<tbody>
<tr>
<?php if($this->session->userdata('is_mobile')==FALSE){

	echo '<td class="sideCell"></td>';
	
}?>


<td class="middleCell">
<table class="table">

<tbody>
<tr>
<td class="header">
<div>

<?php
$label="Logout - ".$this->session->userdata('nick'); 
echo form_open('session/logout');
echo form_submit('logout',$label);
echo form_close();
?>
</div>

<div>
<h1>DebtNagger</h1>
</div>
</td>
</tr>
<tr>
<td class="area">
<table class="innerTable">
<tbody>
<tr class="spaceTR">
<td class="areaSideCell"></td>
<td class="areaCenterCell">
<?php 
		 
		echo form_open('main');

		echo form_submit("back","Back to main");
		echo form_close();

?>
</td>
<td class="areaSideCell"></td>
</tr>
<tr class="basicInfoTR">

<td class="areaSideCell"></td>
<td class="areaCenterCellInput2">

<h2>
<?php 
echo "Add debt for: ".$contact[0]->NICK." - ".$contact[0]->EMAIL;
		
?>
</h2>



<?php 
//var_dump($debtdetails);
//var_dump($debtsum);


?>
<?php 
  //if($this->session->flashdata('error2')!=NULL){
	//echo '<div class="red">'.$this->session->flashdata('error2').'</div>';  	
  //}
  echo '<div class="red">'.validation_errors().'</div>';

  echo form_open('main/addDebt');
  ?>
  <table class="toptable"><tr>
  <td><b>Description</b></td>
  <td><b>Sum*</b></td>
  <td>
  </td>
  </tr>
  <tr>
 
  <td>
  <?php 
    if(validation_errors()!=NULL){
  echo form_input('description', set_value('description'));	
  }
  else{
  	  echo form_input('description','');
  }
  //echo form_input('description', set_value('description'));
  echo form_hidden("contact",$contact[0]->ID);
  ?>

   
   &nbsp; 
   </td>
   <td>
   <?php
       if(validation_errors()!=NULL){
  echo form_input('sum', set_value('sum'));	
  }
  else{
  	  echo form_input('sum','');
  } 
   //echo form_input('sum', set_value('sum'));
   ?>
   
   &nbsp; 
   </td>
   <td>
    <?php 
            echo form_submit("adddebt", 'Add debt');
    ?>

    </td>
    </tr>
    </table>
    <?php 
    echo form_close();
    ?>
      
  <br>
  </td>
<td class="areaSideCell"></td>
</tr>
<tr class="spaceTR">
<td class="areaSideCell"></td>
<td class="areaCenterCell"></td>
<td class="areaSideCell"></td>
</tr>
<tr class="membersTR">

<td class="areaSideCell"></td>
<td class="areaCenterCellInput2"><h2>Debt Details</h2>

  <div id="members_area">
  <table class="prestable">
  <tr>
  	<td>
  		<b>Date</b>
  	</td>
  	<td>
  		<b>Desc</b>
  	</td>
  	<td>
		<b>SUM</b>
    </td>
      	<td>
		<b>Remove</b>
    </td>

    </tr>

    <?php 
    if(isset($debtdetails)){		
    foreach ($debtdetails as $row){
			$id=$row->ID;
			$description=$row->DESCRIPTION;
			$sum=$row->SUM;
			$date=$row->DATE;
			
			
			

		?>
		<tr>
		<td>
		
		<?php 
		
		//echo $date;
		//echo date("l jS \of F Y h:i:s A", strtotime($date)); // Monday 8th of August 2005 03:12:46 PM
		$date2=date("d.m.Y", strtotime($date));
		
		
		//$date3=strtotime(date("d.m.Y", strtotime($date)) . " +2 week");
		//$date = strtotime(date("Y-m-d", strtotime($date)) . " +2 week");
		echo $date2;
		//echo date("d.m.Y",$date3);
			
		//$datestring="Year: %Y Month: %m Day: %d - %h:%i %a";
		//echo mdate($datestring,$date)
		?>
		
		</td>
		<td><?php echo $description?></td>	
  	<td>
		<?php 
		echo $sum;
		?>
    </td>
      	<td>
      	<?php 
      	  echo form_open('main/delDebt');
      	  echo form_hidden('contact',$contact[0]->ID);
      	  echo form_hidden('debtid',$id);
      	  echo form_submit('deldebt','Delete');
      	  echo form_close();
      	?>

    </td>
     </tr>		
		<?php }}?>
	<tr>
	<td>
	</td>
	<td>
	</td>
	<td>
	<b><?php echo $debtsum;?></b>
	</td>
	<td>
	</td>
	</tr>	
    </table>
  </div>
		<?php
		echo "<br>";
		if($this->session->flashdata('error2')!=NULL){
					echo '<div class="red">'.$this->session->flashdata('error2').'</div>';
		}
		else{
      	echo form_open('main/nagmail');
      	echo form_hidden('contact',$contact[0]->ID);
      	echo form_hidden('details','details');
      	echo form_submit('nagmail','Nag NOW!');
      	echo form_close();
		}
		echo "<br>";
		 
// echo '<div> <b>Last NAG sent: </b>'.date("d.m.Y h:i:s", strtotime($contact[0]->LASTNAG)).'</div>';
 echo '<div> <b>Last NAG sent: </b>'.$contact[0]->LASTNAG.'</div>';
 if($contact[0]->NAG==1)
 echo '<div> <b>Next NAG to be sent: </b>'.$contact[0]->NEXTNAG.'</div>';

?>

&nbsp;
</td>
<td class="areaSideCell"></td>
</tr>
<tr class="space2TR">
<td class="areaSideCell"></td>
<td class="areaCenterCell"></td>
<td class="areaSideCell"></td>
</tr>

<tr>
<td class="areaSideCell"></td>
<td class="areaCenterCell"> 

</td>
<td class="areaSideCell"></td>
</tr>



</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
<?php if($this->session->userdata('is_mobile')==FALSE){

	echo '<td class="sideCell"></td>';
	
}?>

</tr>
</tbody>
</table>
<br>
</body></html>








