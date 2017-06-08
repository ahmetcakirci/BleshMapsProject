<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Maps extends CI_Controller {

    public function index()
    {
        $this->load->view('maps');
    }

    public function getBeaconsForTest(){
        $authToken = '0088A1BA-0F71-4AC1-E232-29D408F2A058';
        $postData = array(
            "accessToken"=>"{$authToken}"
        );

        $this->curl->create('http://52.212.64.246/api/general/getBeaconsForTest');

        $this->curl->options(array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
            CURLOPT_POSTFIELDS => json_encode($postData)
        ));

        $output=array();
        foreach (json_decode($this->curl->execute(),TRUE)['beacons'] as $row){
            array_push($output, array('id'=>$row['id'],
                'deviceserial'=>$row['device_serial'],
                'description'=>$row['description'],
                'latitude'=>$row['latitude'],
                'longitude'=>$row['longitude']));
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($output));
    }

    public function updateDescriptionForTest(){
        $authToken = '0088A1BA-0F71-4AC1-E232-29D408F2A058';
        $postData = array(
            "accessToken"=>"{$authToken}",
            "deviceId"=>$this->uri->segment(3),
            "description"=>json_decode(file_get_contents('php://input'))->desc
        );

         $this->curl->create('http://52.212.64.246/api/general/updateDescriptionForTest');

         $this->curl->options(array(
             CURLOPT_POST => TRUE,
             CURLOPT_RETURNTRANSFER => TRUE,
             CURLOPT_HTTPHEADER => array(
                 'Content-Type: application/json'
             ),
             CURLOPT_POSTFIELDS => json_encode($postData)
         ));

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->curl->execute()));
    }
}
