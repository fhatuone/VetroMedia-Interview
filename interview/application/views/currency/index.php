<div class="container">  

    <?php foreach ($result as $r): ?>
        <p class="p"> Your current balance is: <strong>$<?php echo $r->acc_balance; ?></strong></p>
        <br>        
    <?php endforeach; ?>
    <table class="table table-striped table ">
        <caption>Latest currency conversion rates from USD to other foreign countries rates aginst $1 (Dollar)</caption>
        <tr>
            <td>Convertion</td>
            <td>Currency Values</td>
            <td>Last updated at</td> 
            <?php if (isset($result[0]->acc_balance)) { ?>
                <td>On Offer</td>
            <?php } ?>
        </tr>
        <?php foreach ($result2['currencies'] as $key => $value) {
            ?>
            <tr>
                <td><?php echo 'USD to' . ' ' . $value->cntry_code . " (" . $value->cntry_name . ")"; ?></td>
                <td><?php echo ($value->cntry_code) . " " . ($value->curr_amount); ?></td>
                <td><?php echo $value->curr_time; ?></td> 

                <?php if (isset($result[0]->acc_balance)) { ?>
                    <td><a href="<?php echo base_url('/welcome/purchase/' . $value->curr_id); ?>">Purchase</a></td>
                <?php } ?>
                    
            </tr>
        <?php }
        ?>

    </table>
    <p><a href="<?php echo base_url('/currencies'); ?>">Update System Curriencies With Live Current Currencies On The Right Market Now</a></p>
    <p><a href="<?php echo base_url('/welcome/update_balance'); ?>">Deposit/Withdraw Money Into/From You Account</a></p>
    <p><a href="<?php echo base_url('/welcome/Transactions'); ?>">View Transactions history</a></p>

</div>