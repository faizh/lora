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
        $interval       = $this->M_backup->getBackupInterval();
        $backup_files   = $this->M_backup->getAllBackupFiles();

        $this->load->view('includes/header');
		$this->load->view('v_backup', compact('interval', 'backup_files'));
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

    function postBackupFile() {
        $file_base64 = $this->input->post('file');
        $file_decoded = base64_decode($file_base64);

        $path       = "backup";
        $filename   = "backup_" . time() . ".txt";
        $full_path  = "./" . $path . "/" . $filename;

        file_put_contents($full_path, $file_decoded);

        $file_data  = array(
            'filename'  => $filename,
            'path'      => $path . "/" . $filename
        );

        $status = $this->M_backup->insertFile($file_data);

        echo json_encode(array(
            'status'    => $status,
            'path'      => $path . "/" . $filename
        ));
    }

    function download($file_id){
        $file = $this->M_backup->getFile($file_id);
        
        force_download('./' . $file->path, NULL);
    }

    function delete($file_id) {
        $this->M_backup->deleteFile($file_id);

        redirect('/backup');
    }

}