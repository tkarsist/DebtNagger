
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html><head>
<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type"><title>DebtNagger</title>
<link rel="stylesheet" href="<?php echo base_url();?>css/styles.css">

</head>
<body class="background">
<table class="table">
<tbody>
<tr>
<td class="sideCell"></td>
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
<tr class="basicInfoTR">

<td class="areaSideCell"></td>
<td class="areaCenterCellInput2">

<h2>Add person</h2>
<?php 
  /*if(isset($error)){
	echo '<p class="red">'.$error.'</p>';  	
  }
*/
  echo form_open('main/addContact');
  ?>
  <?php 
 
  echo '<div class="red">'.validation_errors().'</div>';
  ?>
  <table class="toptable"><tr>
  <td><b>Name*</b></td>
  <td><b>email*</b></td>
  <td>
  </td>
  </tr>
  <tr>
 
  <td>
  <?php 
  if(validation_errors()!=NULL){
  echo form_input('nick', set_value('nick'));	
  }
  else{
  	  echo form_input('nick','');
  }
  
  //echo set_value('nick');

  ?>

   
   &nbsp; 
   </td>
   <td>
   <?php 
  if(validation_errors()!=NULL){
  echo form_input('email', set_value('email'));	
  }
  else{
  	  echo form_input('email','');
  }
   
   //echo form_input('email', set_value('email'));
   ?>
   
   &nbsp; 
   </td>
   <td>
    <?php 
            echo form_submit("add", 'Add person');
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
<td class="areaCenterCellInput2"><h2>Debt Overview</h2>


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
		<b>NAG</b>
    </td>
      	<td>
		<b>Send NAG</b>
    </td>
      	<td>
		<b>Delete User</b>
    </td>
    </tr>

    <?php 
    
    if(isset($contacts)){		
    foreach ($contacts as $row){
			$id=$row->ID;
			$nick=$row->NICK;
			$email=$row->EMAIL;
			$nag=$row->NAG;
			$sum=$contactSum[$id];
			

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
      	<td>
	
		<?php 
		echo form_open('main/delContact');
		$name="contact";
		echo form_hidden($name,$id);
		echo form_submit("delete","Delete contact");
		echo form_close();
		?>
		
	
    </td>
    </tr>		
		<?php }}?>
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
<td class="sideCell"></td>

</tr>
</tbody>
</table>
<br>
</body></html>








