
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html><head>
<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type"><title>DebtNagger</title>
<link rel="stylesheet" href="<?php echo base_url();?>css/styles.css">

</head>
<body>

<?php 
/*if($this->session->userdata('is_mobile')==FALSE){

	echo '<div class="sidecolumn">&nbsp;</div>';
	}*/
?>
<div id="middlecolumn">
  <div id="headerbar">
    <div id="headerlogo"></div>
    <div id="admindropdown">
      
<?php
$label="Logout - ".$this->session->userdata('nick'); 
echo form_open('session/logout');
echo form_submit('logout',$label);
echo form_close();
?>
    </div>
  </div>
    <div id="topmenu"><!-- InstanceBeginEditable name="topmenu" -->
    <a class="menuitem_selected" href=""><span class="menuitemspan_selected">Dashboard</span></a>
    <a class="menuitem" href="<?php echo base_url().'index.php/main/addcontact';?>"><span class="menuitemspan">Manage contacts</span></a>

  </div>
  
<div id="content">

<?php 
if(!empty($tasks)){
$counter=0;
	?>
<div class="contentitem">Process Claimed Payments
  <div class="contentsplitter"></div>
  <table class="fancytable" >
    
    <thead>
      
      <tr>
        <th scope="col">Name</th>
        
        <th scope="col">Date</th>
        <th scope="col">Message</th>
        
        <th scope="col">Sum</th>
        <th scope="col"></th>
        <th scope="col"></th>

        </tr>
      
      </thead>
    
    <tbody>
        <?php 
    

    	$totalsum=0;		
    foreach ($tasks as $row){
			$id=$row->ID;
			$nick=$row->NICK;
			$desc=$row->DESCRIPTION;
			$contact=$row->FK_CONTACT_ID;
			$sum=$row->SUM;
			$date=$row->DATE;
			
			//$sum=$contactSum[$id];
			$totalsum=$totalsum+$sum;
			

		?>
      
      <tr <?php if($counter==0)
      echo 'class="odd"';?>>
        
        <td> <?php echo $nick;?></td>
        
        <td>
        <?php 
		
		$date2=date("d.m.Y", strtotime($date));
		
		echo $date2;
		
		?>
        
        </td>

        <td>
        <?php 
		echo $desc;
		?>
        </td>
        
        <td>
        <?php 
		echo round($sum,2);
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
		<?php 
    	if($counter==0)
    	$counter=1;
    	else
    	$counter=0;
    
    	}?>

      

      </tbody>
    
  </table>
</div>
<?php 
}
?>
    <div class="contentitem">
    		<?php
		//echo "<br>"; 
 echo '<div class="red">'.$this->session->flashdata('error2').'</div>';
?>
    Debt Overview 
    
      <div class="actionitemright">
<?php 
//echo form_open('main/addcontact');
//echo form_hidden('initial','initial');
//echo form_submit('addcontact','Manage Contacts');
//echo form_close();
?>
       <!-- <a class="menuitem" href="createidea.html"><span class="menuitemspan">Manage Contacts</span></a> -->
       
      </div>

       <div class="contentsplitter"></div> 
<table class="fancytable" id="sortable">
  
  <thead>

    	<tr>
			<th scope="col">Name</th>

        	<th scope="col">Sum</th>

            <th scope="col">Auto NAG</th>
            <th scope="col">Send NAG</th>



        </tr>

    </thead>

    <tbody>
    <?php

    if(isset($contacts)){
    	$counter=0;
    	$totalsum=0;
    	foreach ($contacts as $row){
    		$id=$row->ID;
    		$nick=$row->NICK;
    		$email=$row->EMAIL;
    		$nag=$row->NAG;
    		$sum=$contactSum[$id];
    		$totalsum=$totalsum+$sum;
    			

    		?>

      <tr <?php if($counter==0)
      echo 'class="odd"';?>>
      
      		<td>
		
		
		<?php 
		echo '<a href="'; 
		echo site_url("main/details"."/".$id.'" ');
		echo 'title="'.$email.'">';
		echo $nick;
		echo '</a>'; 
		?>
		
		</td>
		<td><?php echo round($sum,2)?></td>	
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
		<?php 
		if($counter==0)
    	$counter=1;
    	else
    	$counter=0;
    	
    	}}?>
    	
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

    </tbody>

</table>

  </div>
</div>
  
  <div id="footer">¨debtnagger</div>
  </div>

<?php 
/*if($this->session->userdata('is_mobile')==FALSE){

	echo '<div class="sidecolumn">&nbsp;</div>';
	}*/
?>

</body>
</html>