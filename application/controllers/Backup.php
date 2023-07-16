<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Backup extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */

     function __construct() {
        parent::__construct();

        $this->load->model(array(
            'M_backup'
        ));
     }
	public function index()
	{
        $interval = $this->M_backup->getBackupInterval();

        $this->load->view('includes/header');
		$this->load->view('v_backup', compact('interval'));
        $this->load->view('includes/footer');
	}

    function updateBackupInterval() {
        $this->load->model('M_backup');

        $interval = $this->input->post('interval');

        $data = array(
            'value' => $interval
        );

        $condition = array(
            'name'  => 'interval'
        );

        $status = $this->M_backup->update($data, $condition);

        echo json_encode(array('status'  => $status));
    }

    function getBackupInterval() {
        $interval = $this->M_backup->getBackupInterval();

        echo json_encode(array(
            'interval'  => $interval
        ));
    }
}
