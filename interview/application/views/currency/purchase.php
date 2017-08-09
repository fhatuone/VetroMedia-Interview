<div class="container   container-fluid">
    <?php foreach ($result as $r): ?>
        <p class="p"> Your current balance is: <strong>$<?php echo $r->acc_balance; ?></strong></p>
        <br>
        <caption>Latest currency conversion rates from USD to other foreign countries rates aginst $1 (Dollar)</caption>        
    <?php endforeach; ?>    

    <div  class="col-lg-6 col-md-6 col-sm-9">
        <?php echo validation_errors(); ?>
        <?php echo form_open(); ?>
        <div class="form-group hidden">
            
            <input class="form-control" name="user_id" value="<?php echo $this->session->userdata('logged_in')['user_id']; ?>">
            <input class="form-control" name="user_email" value="<?php echo $this->session->userdata('logged_in')['email']; ?>">
        </div>
        <div class="form-group">
            <label class="control-label" for="spend_value">Enter amount you want to spend</label>"
            <input class="form-control" name="amount_paid" type="number" max="100000.00">
        </div>
        <?php
        ?>
        <div class="form-group" style="margin-top: 10px;" >
            <button class="btn btn-primary" name="submit" type="submit">Submit</button>                
        </div>
        <?php echo form_close(); ?>
    </div>
</div>