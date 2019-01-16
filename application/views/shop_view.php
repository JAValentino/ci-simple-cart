<!DOCTYPE html>
<html>
<head>
	<title>Shopping Cart</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="<?php echo base_url("assets/css/bootstrap.min.css"); ?>" rel="stylesheet" />
	<link href="<?php echo base_url("assets/font-awesome/css/font-awesome.min.css"); ?>" rel="stylesheet" />
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-lg-6">
				<div class="table-responsive">
					<h3>Available Items.</h3>
					<?php
						foreach ($product as $row) {
							echo '
								<div class="col-lg-6 col-md-3" style="padding:16px; background:#f1f1f1; border:1px solid #ccc; margin-bottom:16px; height:400px;">
									<img src="'.base_url().'upload/'.$row->product_image.'" class="img-thumbnail center-block">
									<h4 style="text-align:center;">'.$row->product_name.'</h4>
									<h4 class="text-warning" style="text-align:center;">'.$row->product_price.'</h4>
									<input type="text" name="quantity" class="quantity center-block" id="'.$row->product_id.'"><br>
									<button type="button" name="add" class="btn btn-success center-block add" data-productname="'.$row->product_name.'" data-productprice="'.$row->product_price.'" data-productid="'.$row->product_id.'" > Add to Cart</button>
								</div>
							';
						}
					?>
				</div>
			</div>
			<div class="col-lg-6">
				<div id="details">
				</div>
			</div>
		</div>
	</div>

	<script src="<?php echo base_url("assets/js/jquery.min.js"); ?>"></script>
	<script src="<?php echo base_url("assets/js/bootstrap.min.js"); ?>"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$(document).on('click', '.add', function(){
				var product_id = $(this).data("productid");
				var product_name = $(this).data("productname");
				var product_price = $(this).data("productprice");
				var quantity = $("#" + product_id).val();
				if(quantity != "" && quantity > 0){
					$.ajax({
						url:"<?php echo base_url(); ?>shop/add",
						method:"POST",
						data:{product_id:product_id, product_name:product_name, product_price:product_price, quantity:quantity},
						success:function(data){
							alert("Product Added into Cart");
							$('#details').html(data);
							$('#' + product_id).val('');
						}
					})
				} else{
					alert("Please Enter Quantity");
				}
			});
			$('#details').load("<?php echo base_url(); ?>shop/load");

			$(document).on('click', '.delete', function(){
				var rowid = $(this).attr("id");
				if(confirm("Are you sure want to delete this?")){
					$.ajax({
						url:"<?php echo base_url(); ?>shop/delete",
						method:"POST",
						data:{rowid:rowid},
						success:function(data){
							alert("Product deleted.");
							 	$('#details').html(data);
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