<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud extends CI_Controller {

	public function index(){
		$this->load->view("crud_view");
	}

	public function fetch_data(){
		$this->load->model("crud_model");
		$fetch_data = $this->crud_model->get_datatables();
		$data = array();
		foreach ($fetch_data as $row) {
			$array = array();
			$array[] = '<img src="'.base_url().'upload/'.$row->image.'" class="img-thumbnail" height="35" width="50">';
			$array[] = $row->firstname;
			$array[] = $row->lastname;
			$array[] = '<button type="button" name="edit" id="'.$row->id.'" class="btn btn-primary edit"><i class="fa fa-edit"></i></button>';
			$array[] = '<button type="button" name="delete" id="'.$row->id.'" class="btn btn-danger delete"><i class="fa fa-trash"></i></button>';
			$data[] = $array;
		}

		$output = array(
			"draw" => intval($_POST["draw"]),
			"recordsTotal" => $this->crud_model->get_all_data(),
			"recordsFiltered" => $this->crud_model->filtered_data(),
			"data" => $data
		);
		echo json_encode($output);
	}
	
	public function operation(){
		if($_POST["operation"] == "Add"){
			$insert_data = array(
				'firstname' => $this->input->post('firstname'),
				'lastname' => $this->input->post('lastname'),
				'image' => $this->upload_image()
			);
			$this->load->model('crud_model');
			$this->crud_model->insert_data($insert_data);
			echo 'New Data Successfully Inserted.';
		}

		if($_POST["operation"] == "Edit"){
			$image = '';
			if($_FILES["image"]["name"] != ''){
				$image = $this->upload_image();
			} else{
				$image = $this->input->post("hidden_image");
			}
			$update_data = array(
				'firstname' => $this->input->post('firstname'),
				'lastname' => $this->input->post('lastname'),
				'image' => $image
			);
			$this->load->model('crud_model');
			$this->crud_model->update_data($this->input->post('user_id'), $update_data);
			echo "Data Successfully Updated.";
		}
	}

	public function upload_image(){
		if(isset($_FILES["image"])){
			$extension = explode('.', $_FILES["image"]["name"]);
			$new = rand() . '.' . $extension[1];
			$destination = './upload/' . $new;
			move_uploaded_file($_FILES["image"]["tmp_name"], $destination);
			return $new; 
		}
	}
	public function single_fetch(){
		$output = array();
		$this->load->model('crud_model');
		$data = $this->crud_model->single_fetch($this->input->post('user_id'));
		foreach ($data as $row) {
			$output["firstname"] = $row->firstname;
			$output["lastname"] = $row->lastname;
			if($row->image != ''){
				$output["image"] = '<img src="'.base_url().'upload/'.$row->image.'" class="img-thumbnail" width="50" height="35" /><input type="hidden" name="hidden_image" value="'.$row->image.'" />';
			} else{
				$output["image"] = '<input type="hidden" name="hidden_image" value="">';
			}
		}
		echo json_encode($output);
	}
	public function delete(){
		$this->load->model("crud_model");
		$this->crud_model->delete($this->input->post('user_id'));
		echo "Data Successfully Deleted.";
	}
}
