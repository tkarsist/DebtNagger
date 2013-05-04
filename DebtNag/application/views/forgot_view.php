<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html><head>
<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type"><title>DebtNagger</title>
<link rel="stylesheet" href="<?php echo base_url();?>css/styles.css">

</head>
<body class="background">
<h2>Forgot password</h2>
<?php
 
/*if (isset($error)){
echo $error;
}*/
?>

		<?php
		 
 echo '<div class="red">'.$this->session->flashdata('error2').'</div><br>';
?>
<?php echo form_open('session/forgotSend'); ?>
 
 <b>   
        <?php echo form_label('Insert your Email', 'forgot_email'); ?>
        </b>
        <br>
        <?php echo form_input('email','');
         ?>
		
        
    


<?php echo form_submit('submit', 'Submit'); ?>


<?php echo form_close(); ?>

</body>
</html>