
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

  </div>
<div id="content">



    <div class="contentitem">Debt Detail 
    


       <div class="contentsplitter"></div> 
<table class="fancytable" id="sortable">
  
  <thead>

    	<tr>
			<th scope="col">Date</th>

        	<th scope="col">Description</th>

            <th scope="col">Sum</th>
            



        </tr>

    </thead>

    <tbody>
    
        <?php 
    if(isset($debtdetails)){
    	$counter=0;		
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
		echo round($sum,2);
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
	</td>
	<td>
	<b><?php echo $debtsum;?></b>
	</td>

	</tr>

    </tbody>

</table>


    
<?php 
  //if($this->session->flashdata('error2')!=NULL){
	//echo '<div class="red">'.$this->session->flashdata('error2').'</div>';  	
  //}
  echo '<div class="red">'.validation_errors().'</div>';

  echo form_open('claimdebt/paid');
  echo form_hidden('user',$userid);
  echo form_hidden('contact',$contact[0]->ID);
  echo form_hidden('hash',$hash);
  ?>
    
    
<table class="fancytable">

	<thead>
    </thead>
	<tbody>
	<tr>
  <td><b>Your message</b></td>
  <td><b>Paid sum*</b></td>
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
  	  echo form_input('sum',$debtsum);
  } 
   //echo form_input('sum', set_value('sum'));
   ?>
   
   &nbsp; 
   </td>
   <td>
    <?php 
            echo form_submit("paidsum", 'I Paid the SUM');
    ?>

    </td>
  
 
    </tr>
    </tbody>
    </table>
    
    
    <?php 
    echo form_close();
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









