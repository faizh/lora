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
            'M_backup',
            'M_alat'
        ));
     }
	public function index()
	{
        $interval       = $this->M_backup->getBackupInterval();
        $backup_files   = $this->M_backup->getAllBackupFiles();

        $lora_1         = $this->M_alat->getAlat($this->M_alat->lora_1());
        $lora_2         = $this->M_alat->getAlat($this->M_alat->lora_2());

        $this->load->view('includes/header');
		$this->load->view('v_backup', compact('interval', 'backup_files', 'lora_1', 'lora_2'));
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

    function loraLocation() {
        $input = (object) $this->input->post();

        if (empty($input->alat_id)){
            $response = array(
                'status'    => false,
                'msg'       => 'parameter alat_id is null'
            );

            echo json_encode($response);
            exit;
        }

        if (empty($input->latitude)){
            $response = array(
                'status'    => false,
                'msg'       => 'parameter latitude is null'
            );

            echo json_encode($response);
            exit;
        }

        if (empty($input->longitude)){
            $response = array(
                'status'    => false,
                'msg'       => 'parameter longitude is null'
            );

            echo json_encode($response);
            exit;
        }

        $alat_id    = $input->alat_id;
        $longitude  = $input->longitude;
        $latitude   = $input->latitude;
        
        $alat = $this->M_alat->getAlat($alat_id);
        
        if (empty($alat)) {
            $response = array(
                'status'    => false,
                'msg'       => 'alat_id value is invalid'
            );

            echo json_encode($response);
            exit;
        }

        $data = array(
            'latitude'      => $latitude,
            'longitude'     => $longitude
        );

        $condition = array(
            'id'    => $alat_id
        );

        $update = $this->M_alat->update($data, $condition);

        if ($update) {
            $response = array(
                'status'    => true,
                'msg'       => 'update success'
            );
        } else {
            $response = array(
                'status'    => false,
                'msg'       => 'update failed'
            );
        }
        
        echo json_encode($response);
        
    }

}