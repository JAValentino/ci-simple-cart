<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop extends CI_Controller {
	public function index(){
		$this->load->model("shop_model");
		$data["product"] = $this->shop_model->fetch();
		$this->load->view("shop_view", $data);
	}
	public function add(){
		$this->load->library("cart");
		$data = array(
			"id" => $this->input->post('product_id'),
			"name" => $this->input->post('product_name'),
			"qty" => $this->input->post('quantity'),
			"price" => $this->input->post('product_price')
		);
		$this->cart->insert($data);
		echo $this->view();
	}
	public function view(){
		$this->load->library("cart");
		$output = '';
		$output .= '
			<h4>Your Cart</h4>
			<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
						<th>Name</th>
						<th>Quantity</th>
						<th>Price</th>
						<th>Total</th>
						<th>Delete</th>
					</tr>
		';
		$count = 0;
		foreach ($this->cart->contents() as $item) {
			$count++;
			$output .= '
				<tr>
					<td>'.$item["name"].'</td>
					<td>'.$item["qty"].'</td>
					<td>'.$item["price"].'</td>
					<td>'.$item["subtotal"].'</td>
					<td><button type="button" name="delete" class="btn btn-danger delete" id="'.$item["rowid"].'"><i class="fa fa-trash"></i></button></td>
				</tr>
			';
		}
		$output .='
			<tr>
				<td colspan="4" align="right">Total</td>
				<td>'.$this->cart->total().'</td>
			</tr>
		</table>
		</div>
		';

		if($count == 0){
			$output = '
				<h4>Your Cart</h4>
				<div class="table-responsive">
					<table class="table table-bordered">
						<tr>
							<th>Name</th>
							<th>Quantity</th>
							<th>Price</th>
							<th>Total</th>
							<th>Delete</th>
						</tr>
					</table>
				</div>
			';
		}
		return $output;
	}
	public function load(){
		echo $this->view();
	}
	public function delete(){
		$this->load->library("cart");
		$rowid = $this->input->post('rowid');
		$data = array(
			"rowid" => $rowid,
			"qty" => 0
		);
		$this->cart->update($data);
		echo $this->view();
	}
}
?>