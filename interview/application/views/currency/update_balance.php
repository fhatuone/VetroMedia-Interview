<div class="container   container-fluid">
    <div  class="col-lg-6 col-md-6 col-sm-9">
        <?php foreach ($result as $r): ?>
            <p class="p"> Your current balance is: <strong>$<?php echo $r->acc_balance; ?></strong></p>            
            <br>    
            <caption>Latest currency conversion rates from USD to other foreign countries rates aginst $1 (Dollar)</caption>
        <?php endforeach; ?>

        <?php echo validation_errors(); ?>
        <?php echo form_open(); ?>
        <?php foreach ($result as $r): ?>
            <input class="form-control hidden"  name="old_balance" value="<?php echo $r->acc_balance; ?>" >
            <br>        
        <?php endforeach; ?>
        <div class="form-group" style="visibility: hidden">
            <input class="form-control" name="user_id" value="<?php echo $this->session->userdata('logged_in')['user_id']; ?>">
        </div>
        <div class="form-group">
            <label class="control-label" for="new_value">Enter your amount</label>
            <input class="form-control" name="new_value" type="number"  max="100000.00"size="50">
        </div>
        <div class="form-group">
            <label class="control-label" for="Trans_type">Select whether you want to deposit or withdraw</label>
            <select class="form-control" name="Trans_type">
                <option selected="selected"></option>
                <option value="Deposit">Deposit</option>
                <option value="Withdraw">Withdraw</option>

            </select>
        </div>
        <div class="form-group" style="margin-top: 10px;" >
            <button class="btn btn-primary" name="submit" type="submit">Submit</button>                
        </div>
        <?php echo form_close(); ?>
    </div>
</div>