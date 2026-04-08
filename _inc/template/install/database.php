<br>
<div class="container">
    <div class="row">
	    <div class="col-sm-8 col-sm-offset-2">
	        <div class="panel panel-default header">
		        <div class="panel-heading text-center bg-database">
                    <h2>Database Configuration</h2>
                    <p>Running step 3 of 6</p>
                </div>
	        </div>
	    </div>
    </div>
	<div class="row">
		<div class="col-sm-8 col-sm-offset-2">    
		    <div class="panel panel-default menubar">
		        <div class="panel-heading bg-white">
					<ul class="nav nav-pills">
					  	<li>
					  		<a href="index.php">
					  			<span class="fa fa-check"></span> Checklist
					  		</a>
					  	</li>
					  	<li>
                            <a href="purchase_code.php">
                            	<span class="fa fa-check"></span> Verification
                            </a>
                        </li>
					  	<li class="active">
					  		<a href="database.php">Database
					  		</a>
					  	</li>
					  	<li>
					  		<a href="#" onClick="return false">Timezone
					  		</a>
					  	</li>
					  	<li>
					  		<a href="#" onClick="return false">Site Config</a>
					  	</li>
					  	<li>
					  		<a href="#" onClick="return false">Done!</a>
					  	</li>
					</ul>
			    </div>
			    <div class="panel-body ins-bg-col">

			    	<?php if(isset($errors['database_import'])) : ?>
				    	<div class="alert alert-danger">
				    		<p><?php echo $errors['database_import']; ?></p>
				    	</div>
				    <?php endif; ?>
			    	
			    	<form id="databaseForm" class="form-horizontal" action="database.php" method="post">
						<?php 
						if(isset($errors['host'])) 
						    echo "<div class='form-group has-error' >";
						else     
						    echo "<div class='form-group' >";
						?>
							<label for="host" class="col-sm-3 control-label">
							    <p>Hostname <span class="text-aqua">*</span></p>
							</label>
							<div class="col-sm-8">
							    <input type="text" class="form-control" id="host" name="host" value="<?php echo isset($request->post['host']) ? $request->post['host'] : 'localhost'; ?>" required>

							    <p class="control-label">
							    	<?php echo isset($errors['host']) ? $errors['host'] : ''; ?>
							    </p>
							</div>
						</div>

						<?php 
						if(isset($errors['database']))
						    echo "<div class='form-group has-error' >";
						else
						    echo "<div class='form-group' >";
						?>
							<label for="database" class="col-sm-3 control-label">
							    <p>Database <span class="text-aqua">*</span></p>
							</label>
							<div class="col-sm-8">
							    <input type="text" class="form-control" id="database" name="database" value="<?php echo isset($request->post['database']) ? $request->post['database'] : null; ?>" required>

							    <p class="control-label">
							    	<?php echo isset($errors['database']) ? $errors['database'] : ''; ?>
							    </p>
							</div>
						</div>

						<?php 
						if(isset($errors['user'])) 
						    echo "<div class='form-group has-error' >";
						else     
						    echo "<div class='form-group' >";
						?>
							<label for="user" class="col-sm-3 control-label">
							    <p>Username <span class="text-aqua">*</span></p>
							</label>
							<div class="col-sm-8">
							    <input type="text" class="form-control" id="user" name="user" value="<?php echo isset($request->post['user']) ? $request->post['user'] : 'root'; ?>" required>

							    <p class="control-label">
							    	<?php echo isset($errors['user']) ? $errors['user'] : ''; ?>
							    </p>
							</div>
						</div>

						<?php 
						if(isset($errors['password'])) 
						    echo "<div class='form-group has-error' >";
						else     
						    echo "<div class='form-group' >";
						?>
							<label for="password" class="col-sm-3 control-label">
							    <p>Password</p>
							</label>
							<div class="col-sm-8">
							    <input type="password" class="form-control" id="password" name="password" value="<?php echo isset($request->post['password']) ? $request->post['password'] : null; ?>" required>

							    <p class="control-label">
							    	<?php echo isset($errors['password']) ? $errors['password'] : ''; ?>
							    </p>
							</div>
						</div>

						<?php 
						if(isset($errors['port'])) 
						    echo "<div class='form-group has-error' >";
						else     
						    echo "<div class='form-group' >";
						?>
							<label for="port" class="col-sm-3 control-label">
							    <p>Port (3306) <span class="text-aqua">*</span></p>
							</label>
							<div class="col-sm-8">
							    <input type="port" class="form-control" id="port" name="port" value="<?php echo isset($request->post['port']) ? $request->post['port'] : 3306; ?>" required>

							    <p class="control-label">
							    	<?php echo isset($errors['port']) ? $errors['port'] : ''; ?>
							    </p>
							</div>
						</div>

						<div class="alert alert-info highlight-text">
							<p>*** This action may take several minutes. Please keep patience while processing this action and never close the browser. Otherwise system will not work properly. Enjoy a cup of coffee while you are waiting... :)</p>
						</div>

				        <div class="form-group">
							<div class="col-sm-6 text-right">
				                <a href="purchase_code.php" class="btn btn-default">&larr; Previous Step</a>
				            </div>
				            <div class="col-sm-6 text-left">
				                <button class="btn btn-success ajaxcall" data-form="databaseForm" data-loading-text="Processing...">Next Step &rarr;</button>
				            </div>
				        </div>
					</form>
			    </div>
			</div>
		    <div class="text-center copyright">&copy; <a href="#">Shopify Ex</a>, All right reserved.</div>
		</div>
	</div>
</div>

<script type="text/javascript">
function databaseFormSuccessCallback(res)
{
	console.log(res);
	$("#loader-status").show();
	$("#loader-status .progress").show();
    $("#loader-status .text").text("Processing...");

	$("#loader-status .progress-bar").attr("aria-valuenow", 0);
    $("#loader-status .progress-bar").css("width", "0%");
    
    next(res["next"]);
}

function next(url) {
    $.ajax({
      url: url,
      dataType: "json",
      success: function(json) {
        
        if (json["error"]) {
          	toastr.error(json["error"]);
          	$("#loader-status").css('display','none');
          	$("body").removeClass("overlay-loader");
          	$("#loader-status").remove();
			$(".btn").removeAttr("disabled");
			$(".form-control").removeAttr("disabled", "disabled");
			$('.btn').button("reset");
        }
        
        if (json["success"]) {
        	toastr.success(json["success"]);
          	window.location = 'timezone.php';
        }
        
        if (json["total"]) {
        	$("#loader-status .text").text( json["total"]+"%");
          	$("#loader-status .progress-bar").attr("aria-valuenow", json["total"]);
          	$("#loader-status .progress-bar").css("width", json["total"] + "%");
        }
        
        if (json["next"]) {
          next(json["next"]);
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  }
</script>