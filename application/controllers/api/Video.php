<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Video  extends REST_Controller {

    function __construct(){
        parent::__construct();
        $this->load->database();
    }

    public function index_get(){

        //Variable dan Parameter untuk memanggil data bedasarkan parameter
        $video_id   = $this->get('video_id');
        $title      = $this->get('title');


        if($video_id != null || $video_id != ''){
            //menampilkan data berdasarkan id
            $videos = $this->db->get_where('lazday_video', array('video_id' => $video_id))->result();
            $list   = $this->db->get_where('lazday_list', array('video_id' => $video_id))->result();
            $this->response(array('videos' => $videos, 'list' => $list), 200);

        }else if($title != null || $title != ''){
            //menampilkan data berdasrkan title
            $this->db->like('title', $title);
            $videos = $this->db->get('lazday_video')->result();

            $this->db->like('title', $title);
            $list = $this->db->get('lazday_list')->result();
            $this->response(array('videos_judul' => $videos, 'lists_judul' => $list), 200);
            
        }else{
            $videos = $this->db->get('lazday_video')->result();
            $this->response(array('videos' => $videos), 200);
        }


    }

    public function category_get(){
        $category   = $this->get('category');

        if ($category != null || $category != '') {
            $this->db->like('category', $category);
            $videos = $this->db->get('lazday_video')->result();
            $this->response(array('videos' => $videos), 200);
        }else{
            $category = $this->db->get('lazday_cat')->result();
            $this->response(array('category' => $category), 200);
        }

    }

    // public function category_post(){
    //     $category   = $this->post('category');
    //     $this->db->like('category', $category);
    //     $videos = $this->db->get('lazday_video')->result_array();
    //     $this->response(array('videos' => $videos), 200); 
    // }
}
        