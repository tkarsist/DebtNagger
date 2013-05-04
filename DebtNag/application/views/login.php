<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html><head>
<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type"><title>DebtNagger</title>
<link rel="stylesheet" href="<?php echo base_url();?>css/styles.css">

</head>
<body class="background">
<div id="headerlogo"></div>
<div class="contentitem">
<!-- <h2>Login</h2> -->


<?php 
echo '<div class="red">'.$this->session->flashdata('error').'</div>'; 

?>
<?php echo form_open('session/authenticate'); ?>
 
 <b>   
        <?php echo form_label('Email', 'user_email'); ?>
        </b>
        <br>
        <?php echo form_input(array(
            'name' => 'user[email]', 
            'id' => 'user_email'
        )); ?>
		<br><b>
        <?php echo form_label('Password', 'user_password'); ?>
        </b>
        <br>
        <?php echo form_password(array(
            'name' => 'user[password]', 
            'id' => 'user_password'
        )); ?>
        
    
<br>

<?php echo form_submit('submit', 'Login'); ?>


<?php echo form_close(); ?>
<br>
I
		<?php 
		echo '<a href="'; 
		echo site_url("session/forgot".'" >');
		echo "forgot ";
		echo '</a>'; 
		?>
my password <br>
I want to 
		<?php 
		echo '<a href="'; 
		echo site_url("session/register".'" >');
		echo " register";
		echo '</a>'; 
		?>
</div>
</body>
</html>