<!DOCTYPE html>
<html>
<head>
	<title>Crud</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="<?php echo base_url("assets/css/bootstrap.min.css"); ?>" rel="stylesheet" />
	<link href="<?php echo base_url("assets/font-awesome/css/font-awesome.min.css"); ?>" rel="stylesheet" />
	<link href="<?php echo base_url("assets/css/dataTables.bootstrap.min.css"); ?>" rel="stylesheet" />
</head>
<body>
	<div class="content-wrapper">
 		<div class="container">
 			<div class="row">
 				<div class="col-lg-12">
 					<div class="panel panel-default" style="margin-top: 40px">
 						<div class="panel-body">
 							<button type="button" id="add" data-toggle="modal" data-target="#mymodal" class="btn btn-success"><i class="fa fa-plus"></i> Add New Employee</button>
 							<br><br>
 							<!--Table-->
 							<div class="col-lg-12">
 								<div id="alert"></div>
 								<div class="panel panel-primary">
 									<div class="panel-heading">
 										<p class="text-default">Records</p>
 									</div>
 									<div class="panel-body">
 										<div class="table-responsive">
 											<table class="table table-striped table-bordered" id="table">
 												<thead>
 													<tr>
 														<th>Image</th>
 														<th>FirstName</th>
 														<th>LastName</th>
 														<th>Edit</th>
 														<th>Delete</th>
 													</tr>
 												</thead>
 											</table>
 										</div>
 									</div>
 								</div>
 							</div>
 						</div>
 					</div>
 				</div>
 			</div>
 			<!--modal popup-->
	    	<div class="modal" id="mymodal">
	      		<div class="modal-dialog">
	      			<form method="post" id="form">
			       		<div class="modal-content">
			          		<!--modal header-->
			          		<div class="modal-header">
			            		<button type="button" class="close" data-dismiss="modal"><i class="fa fa-remove"></i></button>
			            		<h2 class="modal-title"> Add New User</h2>
			          		</div>
			          		<!--modal body-->
			          		<div class="modal-body">
				          		<label>First Name:</label><br>
				          		<div class="input-group"> 
				            		<span class="input-group-addon"><i class="fa fa-user"></i></span>
				            		<input type="text" class="form-control" placeholder="First Name" name="firstname" id="firstname">
				          		</div><br>
				          		<label>Last Name:</label><br>
				          		<div class="input-group"> 
				            		<span class="input-group-addon"><i class="fa fa-user"></i></span>
				            		<input type="text" class="form-control" placeholder="Last Name" name="lastname" id="lastname">
				          		</div><br>
				          		<label>Select Image</label>
	     				  		<input type="file" name="image" id="image" />
	     				  		<span id="user_upload_image"></span>
	     			   		</div>
				       		<div class="modal-footer">
					       		<div class="col-lg-12">
					       			<input type="hidden" name="user_id" id="user_id">
					       			<input type="hidden" name="operation" id="operation">
					        		<button type="submit" name="submit" id="submit" class="btn btn-primary"> Submit</button>
						       		<button class="btn bnt-deafult" type="button" name="close" id="close" data-dismiss="modal"> Close</button>
						    	</div>
			           		</div>
			        	</div>
			    	</form>
	      		</div>
	    	</div>
 		</div>
 	</div>

	<script src="<?php echo base_url("assets/js/jquery.min.js"); ?>"></script>
	<script src="<?php echo base_url("assets/js/bootstrap.min.js"); ?>"></script>
	<script src="<?php echo base_url("assets/js/jquery.dataTables.min.js"); ?>"></script>
	<script src="<?php echo base_url("assets/js/dataTables.bootstrap.min.js"); ?>"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			var table =  $('#table').DataTable({
				"processing":true,
				"serverSide":true,
				"order":[],
				"ajax":{
					url:"<?php echo base_url(). 'crud/fetch_data'; ?>",
					type:"POST"
				},
				"columnDefs":[
					{
						"targets":[0, 3, 4],
						"orderable":false
					}
				],
				"autoWidth":false
			});
			$('#add').on('click', function(){
				$('#form')[0].reset();
				$('.modal-title').text("Add New User");
				$('#submit').val("Add").text("Add");
				$('#operation').val("Add");
				$('#user_upload_image').html('');
			});
			$(document).on('submit', '#form', function(event){
				event.preventDefault();
				var firstname = $('#firstname').val();
				var lastname = $('#lastname').val();
				var image = $('#image').val().split('.').pop().toLowerCase();
				if(image != ''){
					if(jQuery.inArray(image, ['gif', 'png', 'jpg', 'jpeg']) == -1){
						alert("Invalid Image File");
						$('#image').val('');
						return false;
					}
				}
				if(firstname != '' && lastname != ''){
					$.ajax({
						url:"<?php echo base_url() . 'crud/operation'; ?>",
						method:"POST",
						data:new FormData(this),
						contentType:false,
						processData:false,
						success:function(data){
							$('#alert').fadeIn().html('<div class="alert alert-info">'+data+'</div>').fadeOut(5000);
							$('#form')[0].reset();
							$('#mymodal').modal('hide');
							table.ajax.reload();
						}
					})
				} else{	
					alert("Both Fields are required.");
				}
			});
			$(document).on('click', '.edit', function(){
				var user_id = $(this).attr("id");
				$.ajax({
					url:"<?php echo base_url(); ?>crud/single_fetch",
					method:"POST",
					data:{user_id:user_id},
					dataType:"json",
					success:function(data){
						$('#mymodal').modal('show');
						$('#firstname').val(data.firstname);
						$('#lastname').val(data.lastname);
						$('#user_id').val(user_id);
						$('#user_upload_image').html(data.image);
						$('.modal-title').text("Edit Data");
						$('#submit').val("Edit").text("Edit");
						$('#operation').val("Edit");
					}
				})
			});
			$(document).on('click', '.delete', function(){
				var user_id = $(this).attr("id");
				if(confirm("Are you sure want to delete this?")){
					$.ajax({
						url:"<?php echo base_url(); ?>crud/delete",
						method:"POST",
						data:{user_id:user_id},
						success:function(data){
							$('#alert').fadeIn().html('<div class="alert alert-info">'+data+'</div>').fadeOut(5000);
							table.ajax.reload();
						}
					})
				} else{
					return false;
				}
			});
		});
	</script>

</body>
</html>