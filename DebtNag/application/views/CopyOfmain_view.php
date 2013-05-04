
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
<td class="areaCenterCell"></td>
<td class="areaSideCell"></td>
</tr>
<?php 
if(!empty($tasks)){
	//var_dump($tasks);
?>	
<tr class="basicInfoTR">

<td class="areaSideCell"></td>
<td class="areaCenterCellInput2">

<h2>Process Claimed Payments</h2>
 <div id="members_area">
  <table class="prestable">
  <tr>
  	<td>
  		<b>Name</b>
  	</td>
  	<td>
  		<b>Date</b>
  	</td>
  	<td>
		<b>SUM</b>
    </td>
      	<td>
	
    </td>
    <td>
    </td>

    </tr>

    <?php 
    

    	$totalsum=0;		
    foreach ($tasks as $row){
			$id=$row->ID;
			$nick=$row->NICK;
			$contact=$row->FK_CONTACT_ID;
			$sum=$row->SUM;
			$date=$row->DATE;
			
			//$sum=$contactSum[$id];
			$totalsum=$totalsum+$sum;
			

		?>
		<tr>
		<td>
		
		
		<?php 

		echo $nick;
 
		?>
		
		</td>
		<td><?php 
		
		$date2=date("d.m.Y", strtotime($date));
		
		echo $date2;
		
		?></td>	
  	<td>
		<?php 
echo $sum;
		?>
    </td>
      	<td>
      	<?php 
		echo form_open('main/processTask');		
		echo form_hidden("id",$id);
		echo form_hidden("contact",$contact);
		echo form_submit("accept",'Accept');
		echo form_close();
		?>

    </td>
    <td>
    	 
      	<?php 
		echo form_open('main/processTask');		
		echo form_hidden("id",$id);
		echo form_hidden("contact",$contact);
		echo form_submit("decline",'Decline');
		echo form_close();
		?>
      	
    </td>

    </tr>		
		<?php }?>
	<tr>
	<td>
	</td>
	<td>
	
	</td>
	<td>
	<?php echo "<b>".$totalsum."</b>"?>
	</td>
	<td>
	</td>
	<td>
	</td>
	</tr>
    </table>
  </div>
  <br>
  <tr class="spaceTR">
<td class="areaSideCell"></td>
<td class="areaCenterCell"></td>
<td class="areaSideCell"></td>
</tr>

<?php } ?>

<tr class="membersTR">

<td class="areaSideCell"></td>
<td class="areaCenterCellInput2">
<table>
<tr>
<td>
<h2>Debt Overview</h2>
</td>
<td>
<?php 
echo form_open('main/addcontact');
echo form_hidden('initial','initial');
echo form_submit('addcontact','Manage Contacts');
echo form_close();
?>
</td>
</tr>
</table>



  <div id="members_area">
  <table class="prestable">
  <tr>
  	<td>
  		<b>Name</b>
  	</td>
  	<td>
  		<b>Sum</b>
  	</td>
  	<td>
		<b>Automatic NAG</b>
    </td>
      	<td>
		<b>Send NAG</b>
    </td>

    </tr>

    <?php 
    
    if(isset($contacts)){
    	$totalsum=0;		
    foreach ($contacts as $row){
			$id=$row->ID;
			$nick=$row->NICK;
			$email=$row->EMAIL;
			$nag=$row->NAG;
			$sum=$contactSum[$id];
			$totalsum=$totalsum+$sum;
			

		?>
		<tr>
		<td>
		
		
		<?php 
		echo '<a href="'; 
		echo site_url("main/details"."/".$id.'" ');
		echo 'title="'.$email.'">';
		echo $nick;
		echo '</a>'; 
		?>
		
		</td>
		<td><?php echo $sum?></td>	
  	<td>
		<?php 
		if($nag==1){
			$nagval=0;
			$naglabel="ON";
		}
		else{
			$nagval=1;
			$naglabel="OFF";
		}
		echo form_open('main/nag');		
		echo form_hidden("contact",$id);
		echo form_hidden("nagval",$nagval);
		echo form_submit("nag",$naglabel);
		echo form_close();
		?>
    </td>
      	<td>
      	<?php 
      	echo form_open('main/nagmail');
      	echo form_hidden('contact',$id);
      	echo form_submit('nagmail','Nag NOW!');
      	echo form_close();
      	?>

    </td>

    </tr>		
		<?php }}?>
	<tr>
	<td>
	</td>
	<td>
	<?php 
	if(isset($totalsum))
	echo "<b>".$totalsum."</b>";
	?>
	</td>
	<td>
	</td>
	<td>
	</td>
	</tr>
    </table>
  </div>

		<?php
		echo "<br>"; 
 echo '<div class="red">'.$this->session->flashdata('error2').'</div>';
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








