
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
    <a class="menuitem" href="<?php echo base_url();?>"><span class="menuitemspan">Dashboard</span></a>
    <a class="menuitem_selected" href=""><span class="menuitemspan_selected">Manage contacts</span></a>

  </div>
  
<div id="content">
    <div class="contentitem">
  
    
  Add Contact
    
        <?php 
//echo form_open('main');
//echo form_submit('close','Close Manage Contacts');
//echo form_close();
?>
        
    
  
    <div class="contentsplitter"></div>
    
    

    
    
<table class="fancytable">
<?php 
  echo form_open('main/addContact');
  ?>
	<thead>
    </thead>
	<tbody>
	<tr>
  <td><b>Name*</b></td>
  <td><b>Email*</b></td>
  <td>
  </td>
  </tr>
  <tr>
 
  <td>
  
    <?php 
  if(validation_errors()!=NULL)
  $nick=set_value('nick');	
  else
  $nick='';
  
  		$data=array(
  		'name'=>'nick',
  		'value'=>'',
  		'size'=>'11'
  		);
  		echo form_input($data);
  	  //echo form_input('nick','');
  
  
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
            echo form_submit("add", 'Add Contact');
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


    <div class="contentitem">Contact List 
    


       <div class="contentsplitter"></div> 
<table class="fancytable" id="sortable">
  
  <thead>

    	<tr>
			<th scope="col">Name</th>

        	<th scope="col">Email</th>

            <th scope="col">Sum</th>
            <th scope="col"></th>



        </tr>

    </thead>

    <tbody>
    <?php 
    
    if(isset($contacts)){
    		$counter=0;		
    foreach ($contacts as $row){
			$id=$row->ID;
			$nick=$row->NICK;
			$email=$row->EMAIL;
			$nag=$row->NAG;
			$sum=$contactSum[$id];
			

		?>
	<tr <?php if($counter==0)
      echo 'class="odd"';?>>
		<td>
		
		
		<?php 
		echo $nick;
				?>
		
		</td>
		<td><?php echo $email?></td>	
		<td><?php echo round($sum,2)?></td>	
  	

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
				<?php 
		if($counter==0)
    	$counter=1;
    	else
    	$counter=0;
    	
    	}}?>

    </tbody>

</table>
		<?php
		echo "<br>"; 
 echo '<div class="red">'.$this->session->flashdata('error2').'</div>';
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









