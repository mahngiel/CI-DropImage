<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */
// ------------------------------------------------------------------------
/**
 * Pagination Library
 *
 * Prepares and creates pagination
 *
 * @package	CodeIgniter
 * @subpackage		CodeIgniter Drop Image Library
 * @category	Library
 * @authors		Mahngiel (a/k/a) Kris Reeck
 * @license		http://opensource.org/licenses/mit-license.php MIT License (MIT)
 * @filesource https://github.com/mahngiel/CI-DropImage
 */
 


class DropImage {

	var $CI;
	var $maxsize = 1048576;
	function __construct()
	{	
		// Create an instance to CI
		$this->CI =& get_instance();
	}

	// ---------------------------------------------------------------------
	/**
	 * Catch Image
	 *
	 * Catches image sent via AJAX request
	 *
	 * @access	private
	 *
	 **/
	function catch_image()
	{		
		// Prep return data
		$response = array();

		// Ensure access is via AJAX
		if( !isset($_SERVER['HTTP_X_REQUESTED_WITH']) )
		{
			$response = array(
				'status' 	=>	0,
				'msg'		=>'improper path or request',
				);
		}
		else
		{
			// Ensure user is logged in
			if( !$this->CI->user->logged_in() )
			{
				$response = array(
					'status' 	=>	0,
					'msg'		=> 'not logged in'
					);
			}
			else
			{
				// Retrieve file
				$image = (isset($_SERVER['HTTP_X_FILENAME']) ? $_SERVER['HTTP_X_FILENAME'] : false);
				
				// Ensure file exists
				if( !$image )
				{
					$response = array(
						'status' 	=>	0,
						'msg'		=> 'file not sent',
						);
				}
				else
				{					
					// Images get some additional checks
					$image_types = array('image/bmp','image/gif','image/jpeg', 'image/pjpeg','image/jpeg', 'image/pjpeg','image/jpeg', 'image/pjpeg','image/png',  'image/x-png');
					
					// Validate filetype
					if ( !in_array($_SERVER['CONTENT_TYPE'], $image_types) )
					{
						$response = array(
							'status' 	=>	0,
							'msg'		=>	'Invalid filetype',
							);
					}
					else
					{						
						// Upload file
						$temp_upload = file_put_contents(UPLOAD . $image,	file_get_contents('php://input'));
						
						$response = array(
							'status'	=>	1,
							'image'	=>	$image,
							);
					}
				}
			}
		}
		
		return $response;
	}
	

	
}

/* End of file DropImage.php */
/* Location: ./projectx/libraries/DropImage.php */
