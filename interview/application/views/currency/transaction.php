<div class="container"> 

    <table class="table table-striped table ">

        <caption>History of your main Account </caption>

        <tr>
            <td><strong>Transaction Type</strong></td>
            <td><strong>Deposit</strong></td>
            <td><strong>Withdraw</strong></td> 
            <td><strong>Remaining Balance</strong></td>

        </tr>

        <?php foreach ($result as $r): ?>
            <tr>
                <td> <?php echo $r->acc_trans; ?></td>  
                <td> <?php echo $r->amnt_deposit; ?></td> 
                <td> <?php echo $r->amnt_withdraw; ?></td>  
                <td> <?php echo $r->acc_balance; ?></td>     
            </tr>

        <?php endforeach; ?>

    </table>

</div>