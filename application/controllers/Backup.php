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
            'M_alat',
            'M_backup_messages'
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

    function postMessage() {
        $input = (object) $this->input->post();

        if ( !isset($input->message) ) {
            $response = array(
                'status'    => false,
                'msg'       => 'parameter message is required'
            );
            echo json_encode($response);
            exit;
        }

        if ( !isset($input->alat_id) ) {
            $response = array(
                'status'    => false,
                'msg'       => 'parameter alat_id is required'
            );
            echo json_encode($response);
            exit;
        }

        $message    = base64_decode($input->message);
        $alat_id    = $input->alat_id;
        $insert     = $this->M_backup_messages->insert(array(
            "alat_id"   => $alat_id,
            "message"   => $message
        ));

        if ( !$insert ) {
            $response = array(
                'status'    => false,
                'msg'       => 'failed post message'
            );
        } 

        $response = array(
            'status'    => true,
            'msg'       => 'success post message'
        );

        echo json_encode($response);
        exit;
    }

    function get_available_backup_times() {
        $date       = date('Y-m-d');
        $interval_setup   = 5; /** in minutes */

        switch ($interval_setup) {
            case 5:
                $interval = new DateInterval('PT5M'); // set the interval to 1 minute
                break;
            
            case 300:
                $interval = new DateInterval('PT1H'); // set the interval to 1 minute
                break;

            case 600:
                $interval = new DateInterval('PT10H'); // set the interval to 1 minute
                break;

            case 1440:
                $interval = new DateInterval('PT24H'); // set the interval to 1 minute
                break;
            
            default:
                $interval = new DateInterval('PT1H'); // set the interval to 1 minute
                break;
        }

        $begin = (new DateTime())->setTime(0,0,0);  // create start point
        $end = (new DateTime())->setTime(23,59,59); // create end point
        $daterange = new DatePeriod($begin, $interval ,$end);  // create the DatePeriod

        $time = date('H:i:s');
        $avail_times = array();
        foreach($daterange as $key => $d){ // loop through that period
            if ($key > 0) {
                $prev_time = $time;
            }

            $time = $d->format("H:i:s");

            if ($key == 0) continue;
            
            $messages   = $this->M_backup_messages->getBackupMessages($date, $prev_time, $time);
            if (count($messages) > 0) {
                $avail_times[] = array(
                    'start' => $prev_time,
                    'end'   => $time
                );
            }
        }

        /** additional condiition */
        $prev_time = $time;
        $time = '23:59:59';
        $messages   = $this->M_backup_messages->getBackupMessages($date, $prev_time, $time);
        if (count($messages) > 0) {
            $avail_times[] = array(
                'start' => $prev_time,
                'end'   => $time
            );
        }

        return $avail_times;
    }

}