<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Video  extends REST_Controller {

    function __construct(){
        parent::__construct();
        $this->load->database();
    }

    function auth_post(){
        //daftar user dengan android_id 

        //cek data user
        $id = $this->post('android_id');
        $this->db->where('android_id', $id);
        $users = $this->db->get('lazday_user')->result();

        //bila user sudah ada maka akan dipanggil
        if ($users){
            $data = array( 'last_login' => date("Y-m-d H:i:s") );
            $this->db->where('android_id', $id);
            $update = $this->db->update('lazday_user', $data);
            if($update){
                $this->response(array('response' => 'success', 'users' => $users ), 201);
            } else {
                $this->response(array('response' => 'fail', 502));
            }
        } else {
            //bila android_id belum terdaftar maka akan ditambah baru
            $data = array(
                'android_id'    => $this->post('android_id'),
                'created'       => date("Y-m-d H:i:s"),
                'last_login'    => date("Y-m-d H:i:s")
            );
            $insert = $this->db->insert('lazday_user', $data);
            if($insert){
                $this->db->where('android_id', $id);
                $users = $this->db->get('lazday_user')->result();
                $this->response(array('response' => 'success', 'users' => $users ), 201);
            } else {
                $this->response(array('response' => 'fail', 502));
            }
        }
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

    //sama dengan public function category_get()
    // public function category_post(){
    //     $category   = $this->post('category');
    //     $this->db->like('category', $category);
    //     $videos = $this->db->get('lazday_video')->result_array();
    //     $this->response(array('videos' => $videos), 200); 
    // }

    function like_post() {
        $android_id = $this->post('android_id');
        $list_id    = $this->post('list_id');
        $this->db->where(array('android_id' => $android_id, 'list_id' => $list_id));
        $likes = $this->db->get('lazday_like')->result();
        if(!$likes){
            $data = array(
                'android_id'    => $this->post('android_id'),
                'list_id'       => $this->post('list_id'),
                'created'       => date("Y-m-d H:i:s")
            );
            $insert = $this->db->insert('lazday_like', $data);
            if ($insert) {
                $this->response( array('response' => 'success' , 200));
            } else {
                $this->response( array('response' => 'fail', 502));
            }
        }
    }

    function unlike_post() {
        $android_id = $this->post('android_id');
        $list_id = $this->post('list_id');
        $this->db->where(array('android_id' => $android_id, 'list_id' => $list_id ));
        $delete = $this->db->delete('lazday_like');
        if ($delete) {
            $this->response(array('response' => 'success'), 201);
        } else {
            $this->response(array('response' => 'fail', 502));
        }
    }
}
        