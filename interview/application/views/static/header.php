<head>
        <meta charset="utf-8">        
        <title>Welcome</title>
        
       
        <link href="<?php echo base_url(); ?>/view/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        
        
    </head>
    <body>
        <nav class="navbar navbar-default" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			
			<a class="navbar-brand" href="<?php echo base_url(); ?>">Currency converter</a>
		</div>
           
		<div class="collapse navbar-collapse" id="navbar1">
			<ul class="nav navbar-nav navbar-right">
                            
                             <?php if(!empty($this->session->userdata('logged_in'))) : ?>
                       
                                    <li><a href="<?php echo base_url('user/change_password'); ?>"><span class="glyphicon glyphicon-log-in"></span> Change Password</a></li>                       
                                <li><a href="<?php echo base_url('user/logout'); ?>"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>                                                                
                                   <li> <a href="#" class="dropdown-toggle glyphicon glyphicon-user" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                               Hi <?php echo $this->session->userdata('logged_in')['fname'];?> <span class="caret"></span></a></li>
                        
                       
                        <?php else : ?>
                        <li ><a class="glyphicon glyphicon-user" href="<?php echo site_url('user'); ?>">Sign in</a></li>
                        <?php endif;?>  
				
			</ul>
                  
		</div>
	</div>
</nav>
        <br/>