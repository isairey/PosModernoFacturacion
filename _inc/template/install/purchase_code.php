<br>
<div class="container">
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
            <div class="panel panel-default header">
                <div class="panel-heading text-center">
                    <h2>Verify Purchase Code</h2>
                    <p>Running step 2 of 6</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">  
            <div class="panel panel-default menubar">
                <div style="background-color: #ffffff;" class="panel-heading">
                    <ul class="nav nav-pills">
                        <li>
                            <a href="index.php"><span class="fa fa-check"></span> Checklist</a>
                        </li>
                        <li class="active">
                            <a href="purchase_code.php">Verification</a>
                        </li>
                        <li>
                            <a href="#" onClick="return false;">Database</a>
                        </li>
                        <li>
                            <a href="#" onClick="return false;">Timezone</a>
                        </li>
                        <li>
                            <a href="#" onClick="return false;">Site Config</a>
                        </li>
                        <li>
                            <a href="#" onClick="return false;">Done!</a>
                        </li>
                    </ul>
                </div>
                <div class="panel-body ins-bg-col">

                    <?php if($errors['internet_connection']) : ?>
                        <div class="alert alert-danger">
                            <p><?php echo $errors['internet_connection']; ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if($errors['config_error']) : ?>
                        <div class="alert alert-danger">
                            <p><?php echo $errors['config_error']; ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <form id="purchaseCodeForm" class="form-horizontal" role="form" action="purchae_code.php" method="post">
                        <?php 
                        if($errors['purchase_username']) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                        ?>
                            <label for="purchase_username" class="col-sm-3 control-label">
                                <p>Envato Username <span class="text-aqua">*</span></p>
                            </label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="purchase_username" name="purchase_username" value="<?php echo isset($request->post['purchase_username']) ? $request->post['purchase_username'] : null; ?>" autocomplete="off">

                                <p class="control-label">
                                    <?php echo $errors['purchase_username']; ?>
                                </p>
                            </div>
                        </div>

                        <?php 
                        if($errors['purchase_code']) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                        ?>
                            <label for="purchase_code" class="col-sm-3 control-label">
                                <p>Purchase Code <span class="text-aqua">*</span></p>
                            </label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="purchase_code" name="purchase_code" value="<?php echo isset($request->post['purchase_code']) ? $request->post['purchase_code'] : null; ?>" autocomplete="off">

                                <p class="control-label">
                                    <?php echo $errors['purchase_code']; ?>
                                </p>
                            </div>
                        </div>

                        <br>

                        <div class="form-group">
                            <div class="col-sm-6 text-right">
                                <a href="index.php" class="btn btn-default">&larr; Previous Step</a>
                            </div>
                            <div class="col-sm-6 text-left">
                                <button class="btn btn-success ajaxcall" data-form="purchaseCodeForm" data-loading-text="Checking...">Next Step &rarr;</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="text-center copyright">&copy; <a href="#">Shopify Ex</a>, All right reserved.</div>
        </div>
    </div>
</div>