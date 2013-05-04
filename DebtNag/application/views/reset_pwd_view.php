<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html><head>
<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type"><title>DebtNagger</title>
<link rel="stylesheet" href="<?php echo base_url();?>css/styles.css">

</head>
<body class="background">
<h2>Reset password</h2>

<?php echo form_open('session/reset'); ?>
 
 <b>   
        <?php echo form_label('Insert your new password', 'pwd'); ?>
        </b>
        <br>
        <?php echo form_hidden('stamp',$stamp);?>
        <?php echo form_hidden('hash',$hash);?>
        <?php echo form_hidden('id',$userid);?>
        <?php echo form_password('pwd','');
         ?>
		
        
    


<?php echo form_submit('submit', 'Submit'); ?>


<?php echo form_close(); ?>

</body>
</html>