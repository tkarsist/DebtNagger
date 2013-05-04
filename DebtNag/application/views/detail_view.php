
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
    <a class="menuitem" href="<?php echo base_url();?>"><span class="menuitemspan">Back to main</span></a>
    <!-- <a class="menuitem" href="<?php echo base_url().'index.php/main/addcontact';?>"><span class="menuitemspan">Manage contacts</span></a>-->

  </div>
  
<div id="content">

      <?php 
		/* 
		echo form_open('main');

		echo form_submit("back","Back to main");
		echo form_close();
*/
?>

    <div class="contentitem">

    <div>
    <?php 
    if($this->session->flashdata('error2')!=NULL){
    	echo '<div class="red">'.$this->session->flashdata('error2').'</div>';
    }
    
echo "Add debt for ".$contact[0]->NICK." - ".$contact[0]->EMAIL;
		
?>
</div>

    <div class="contentsplitter"></div>

    
    
<table class="fancytable">
  <?php 
  
  echo form_open('main/addDebt');
	?>
  <thead>
	<tr>
	  <th scope="col">Description</th>
  <th scope="col">Sum*</th>
  <th scope="col"></th>
  </tr>
    </thead>
	<tbody>

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
       if(validation_errors()!=NULL)
       $sum=set_value('sum');
       else
       $sum='';
       
       $data=array(
       'name'=>'sum',
       'value'=>$sum,
       'size'=>'6'
       );
  	  echo form_input($data);
 
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
    </tbody>
    </table>
    
    
    <?php 
    echo form_close();
    ?>
 <?php 

  echo '<div class="red">'.validation_errors().'</div>';


  ?>  
  


  </div>
        <?php 
    if($debtdetails!=NULL){
    	    		$counter=0;
    	    		
    	    		?>

    <div class="contentitem">Debt details 
    


       <div class="contentsplitter"></div> 
<table class="fancytable" id="sortable">
  
  <thead>

    	<tr>
			<th scope="col">Date</th>

        	<th scope="col">Desc</th>

            <th scope="col">Sum</th>
            <th scope="col"></th>



        </tr>

    </thead>

    <tbody>
    
<?php 		
    foreach ($debtdetails as $row){
			$id=$row->ID;
			$description=$row->DESCRIPTION;
			$sum=$row->SUM;
			$date=$row->DATE;
			
			
			

		?>
			<tr <?php if($counter==0)
      echo 'class="odd"';?>>
		<td>
		
		<?php 

		$date2=date("d.m.Y", strtotime($date));
		echo $date2;
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
     					<?php 
		if($counter==0)
    	$counter=1;
    	else
    	$counter=0;
    	
    	}?>		
	<tr>
	<td>
	</td>
	<td>
	</td>
	<td>
	<b><?php echo round($debtsum,2);?></b>
	</td>
	<td>
	</td>
	</tr>

</tbody>

</table>
		<?php
		echo "<br>";

		//else{
		if($contact[0]->NAG==1){
      	echo form_open('main/nagmail');
      	echo form_hidden('contact',$contact[0]->ID);
      	echo form_hidden('details','details');
      	echo form_submit('nagmail','Nag NOW!');
      	echo form_close();
		}
		//}
		echo "<br>";
		 

 echo '<div> <b>Last NAG sent: </b>'.$contact[0]->LASTNAG.'</div>';
 if($contact[0]->NAG==1)
 echo '<div> <b>Next NAG to be sent: </b>'.$contact[0]->NEXTNAG.'</div>';

?>
<?php 
    }
?>
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


