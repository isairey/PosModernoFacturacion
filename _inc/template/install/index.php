<br>
<div class="container">
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
            <div class="panel panel-default header">
                <div class="panel-heading text-center bg-database">
                    <h2>Pre-Installation Checklist</h2>
                    <p>Running step 1 of 6</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">   
            <div class="panel panel-default menubar">
                <div class="panel-heading bg-white">
            		<ul class="nav nav-pills">
            		  	<li class="active">
                            <a href="index.php">Checklist</a>
                        </li>
                        <li>
                            <a href="#" onClick="return false">Verification</a>
                        </li>
            		  	<li>
                            <a href="#" onClick="return false">Database</a>
                        </li>
                        <li>
                            <a href="#" onClick="return false">Timezone</a>
                        </li>
            		  	<li>
                            <a href="#" onClick="return false">Site Config</a>
                        </li>
            		  	<li>
                            <a href="#" onClick="return false">Done!</a>
                        </li>
            		</ul>
                </div>
                <div class="panel-body ins-bg-col" style="margin-top:10px;margin-bottom:10px;">
                	<?php  

                		foreach ($success as $succ) {
                		 	echo "<div class=\"alert alert-success\"><span class=\"fa fa-check-circle\"></span> ". $succ ."</div>";	
                		}

                		foreach ($errors as $er) {
                		 	echo "<div class=\"alert alert-danger\"><span class=\"fa fa-exclamation-circle\"></span> ". $er ."</div>";
                		}
                	?>

                    <?php if(empty($errors)) : ?>
                	   
                        <div class="col-sm-4 col-sm-offset-4 text-center" style="margin-top:10px;">
                            <a href="purchase_code.php" class="btn btn-block btn-success">Next Step &rarr;</a>
                        </div>
                    
                    <?php else : ?>
                        
                        <div class="alert alert-warning">
                            Please, Resolve all the warning showings in check list to proceed to next step.
                        </div>
                    
                    <?php endif; ?>
                </div>
            </div>
            <div class="text-center copyright">&copy; <a href="#">Shopify EX</a>, All right reserved.</div>
        </div>
    </div>
</div>