<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html><head>
<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type"><title>DebtNagger</title>
<link rel="stylesheet" href="<?php echo base_url();?>css/styles.css">

<script type="text/javascript">
var passCheck = function() {
	if (document.getElementById("password_check")) {
		a = document.getElementById("password_check");
		if (isNaN(a.value) == true) {
			a.value = 0;
		} else {
			a.value = parseInt(a.value) + 1;
		}
	}
	setTimeout("passCheck()", 1000);
}

passCheck();
</script>
</head>
<body class="background">
<h2>Register</h2>
<?php
 
/*if (isset($error)){
echo $error;
}*/
?>

<?php 

echo $this->session->flashdata('error');

?>
<?php echo form_open('session/register'); ?>
 
   <?php 
 
  echo '<div class="red">'.validation_errors().'</div>';
  ?>		
 		<b>   
        <?php echo form_label('Nick', 'nick'); ?>
        </b>
        <br>
        <?php
        if(validation_errors()!=NULL){
        	echo form_input('nick', set_value('nick'));
        }
        else{
        	echo form_input('nick','');
        }
		?>
	<br>
 		<b>   
        <?php echo form_label('Email', 'email'); ?>
        </b>
        <br>
        <?php
        if(validation_errors()!=NULL){
        	echo form_input('email', set_value('email'));
        }
        else{
        	echo form_input('email','');
        }
		?>
		<br><b>
        <?php echo form_label('Password', 'user_password'); ?>
        </b>
        <br>
        <?php echo form_password('password',''); ?>
        <?php 
        $data=array(
        'name'=>'password_check',
        'id'=>'password_check',
        'value'=>'Password check',
        'type'=>'hidden'
        );
        echo form_input($data)?>
        
    
<br>

<?php echo form_submit('submit', 'Register'); ?>


<?php echo form_close(); ?>

</body>
</html>