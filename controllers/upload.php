<?php

class Upload extends CI_Controller {
	
	/**
	 * Constructor
	 *
	 */	

	function __construct()
	{
		// Call the Controller constructor
		parent::__construct();
	}
	
	// --------------------------------------------------------------------
	function index()
	{ 	}
	
	// --------------------------------------------------------------------
	function upload()
	{
		// Load Libraries
		$this->load->library('DropImage');
		$this->load->library('Media'); // See https://github.com/mahngiel/CI-Media-Library
		
		// Send request to library
		$response = $this->dropimage->catch_image();
	
		if( (bool)$response['status'] )
		{
			$image = $response['image'];
			
			// Prep for resize
			$file = array(
					'full_path'	=>	UPLOAD . $image,
					'file_name'	=>	$image,
				);
			
			/* ========================
			| The next portion of the script is to vary depending on the
			| action, which is assigned in the form attributes.  Based 
			| on the value of the attr, you should have different conditions
			| ======================= */
			if ( $this->input->get('action') == 'headlines') 
			{
    				// upload image: @file data array, @upload dir
    				$upload = $this->media->upload_image( $file, 'headlines' );
    				
    				if( !$upload )
				{
					// Image did not upload
					$error = array(
						'status' 	=>	0,
						'msg'		=>	'File failed to upload',
						);
				}
				else
				{					
					// prepare for insert
					$data = array(
						'image_entry'	=> $this->input->get('entry'),
						'image_name'	=> $image,
						);
						
					// insert into database
					$this->media->insert('images', $data);
				}			
			}
		}
		echo json_encode($response);
	}
}

/* End of file .php */
/* Location: ./application/controllers/upload.php */