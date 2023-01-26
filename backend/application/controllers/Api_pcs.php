<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta'); 

require APPPATH.'libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/Firebase/JWT/JWT.php';
use \Firebase\JWT\JWT;

class Api_pcs extends REST_Controller{

    private $secret_key="haechan";

    function __construct(){
        parent::__construct();
        $this->load->model('M_admin');
        $this->load->model('M_produk');
        $this->load->model('M_supplier');
        $this->load->model('M_transaksi');
        $this->load->model('M_item_transaksi');
    }

    //Cek Token
    public function cekToken(){
        try {
            $token=$this->input->get_request_header('Authorization');

            if(!empty($token)){
                $token=explode(' ',$token)[1];
            }

            $token_decode=JWT::decode($token,$this->secret_key,array('HS256'));
        } catch (Exception $e) { 
            $data_json=array(
                "success"=>false,
                "message"=>"Token tidak valid",
                "error_code"=>1204,
                "data"=>null
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        }
    }

    //Login Start
    public function login_post(){
        $data=array(
            "email"=>$this->input->post("email"),
            "password"=>md5($this->input->post("password"))
        );

        $result=$this->M_admin->cekLoginAdmin($data);

        if(empty($result)){
            $data_json=array(
                "success"=>false,
                "message"=>"Email dan Password tidak valid",
                "error_code"=>1308,
                "data"=>null
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        }else{
            $date=new Datetime();

            $payload["id"]=$result["id"];
            $payload["email"]=$result["email"];
            $payload["iat"]=$date->getTimestamp();
            $payload["exp"]=$date->getTimestamp()+3600;

            $data_json=array(
                "success"=>true,
                "message"=>"Otentikasi Berhasil",
                "data"=>array(
                    "admin"=>$result,
                    "token"=>JWT::encode($payload,$this->secret_key)
                )
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
        }
    }
    //Login End

    //API Admin Start
	public function admin_get()
	{
        $this->cekToken();

        $result=$this->M_admin->getAdmin();

        if(empty($result)){
            $data_json=array(
                "success"=>false,
                "message"=>"Data not found",
                "data"=>null
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        }

        $data_json=array(
            "success"=>true,
            "message"=>"Data found",
            "data"=>array(
                "admin"=>$result
            )
        );

        $this->response($data_json,REST_Controller::HTTP_OK);
    }
    
    public function admin_post()
    {
        $this->cekToken();
        //validasi
        $validation_message=[];

        if($this->post("email")==""){
            array_push($validation_message,"Email tidak boleh kosong");
        }

        if($this->post("email")!="" && !filter_var($this->post("email"),FILTER_VALIDATE_EMAIL)){
            array_push($validation_message,"Format Email tidak valid");
        }
        
        if($this->post("password")==""){
            array_push($validation_message,"Password tidak boleh kosong");
        }

        if($this->post("nama")==""){
            array_push($validation_message,"Nama tidak boleh kosong");
        }

        if(count($validation_message)>0){
            $data_json=array(
                "success"=>false,
                "message"=>"Data tidak valid",
                "data"=>$validation_message
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        }

        //jika lolos validasi
        $data=array(
            'email'=>$this->post('email'),
            'password'=>md5($this->post('password')),
            'nama'=>$this->post('nama')
        );

        $result=$this->M_admin->insertAdmin($data);
        
        $data_json=array(
            "success"=>true,
            "message"=>"Insert Berhasil",
            "data"=>array(
                "admin"=>$data
            )
        );

        $this->response($data_json,REST_Controller::HTTP_OK);
    }

    public function admin_put(){
        $this->cekToken();
        //validasi
        $validation_message=[];

        if($this->put("id")==""){
            array_push($validation_message,"ID tidak boleh kosong");
        }

        if($this->put("email")==""){
            array_push($validation_message,"Email tidak boleh kosong");
        }

        if($this->put("email")!="" && !filter_var($this->put("email"),FILTER_VALIDATE_EMAIL)){
            array_push($validation_message,"Format Email tidak valid");
        }
        
        if($this->put("password")==""){
            array_push($validation_message,"Password tidak boleh kosong");
        }

        if($this->put("nama")==""){
            array_push($validation_message,"Nama tidak boleh kosong");
        }

        if(count($validation_message)>0){
            $data_json=array(
                "success"=>false,
                "message"=>"Data tidak valid",
                "data"=>$validation_message
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        }

        //jika lolos validasi
        $data=array(
            "email"=>$this->put("email"),
            "password"=>md5($this->put("password")),
            "nama"=>$this->put("nama")
        );

        $id=$this->put("id");
        $result=$this->M_admin->updateAdmin($data,$id);
        
        $data_json=array(
            "success"=>true,
            "message"=>"Update Berhasil",
            "data"=>array(
                "admin"=>$result
            )
        );

        $this->response($data_json,REST_Controller::HTTP_OK);
    }

    public function admin_delete(){
        $this->cekToken();

        $id=$this->delete("id");
        $result=$this->M_admin->deleteAdmin($id);

        if(empty($result)){
            $data_json=array(
                "success"=>false,
                "message"=>"Id tidak valid",
                "data"=>null
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        }

        $data_json=array(
            "success"=>true,
            "message"=>"Delete Berhasil",
            "data"=>array(
                "admin"=>$result
            )
        );

        $this->response($data_json,REST_Controller::HTTP_OK);

    }
    //API Admin End

    //API Produk Start 
    public function produk_get()
	{
        $this->cekToken();
        $data=$this->M_produk->getProduk();
        $result=array(
            "success"=>true,
            "message"=>"Data found",
            "data"=>array(
                "produk"=>$data
            )
        );

        echo json_encode ($result);

        // $result=$this->M_produk->getProduk();

        // if(empty($result)){
        //     $data_json=array(
        //         "success"=>false,
        //         "message"=>"Data not found",
        //         "data"=>null
        //     );

        //     $this->response($data_json,REST_Controller::HTTP_OK);
        //     $this->output->_display();
        //     exit();
        // }

        // $data_json=array(
        //     "success"=>true,
        //     "message"=>"Data found",
        //     "data"=>array(
        //         "produk"=>$result
        //     )
        // );

        // $this->response($data_json,REST_Controller::HTTP_OK);
    }
    
    public function produk_post()
    {
        $this->cekToken();
        //validasi
        $validation_message=[];

        if($this->post("admin_id")==""){
            array_push($validation_message,"ID Admin tidak boleh kosong");
        }

        if($this->post("admin_id")!="" && !$this->M_admin->cekAdminExist($this->input->post("admin_id"))){
            array_push($validation_message,"ID Admin tidak ditemukan");
        }

        if($this->post("nama")==""){
            array_push($validation_message,"Nama tidak boleh kosong");
        }

        if($this->post("harga")==""){
            array_push($validation_message,"Harga tidak boleh kosong");
        }

        if($this->input->post("harga")!="" && !is_numeric($this->input->post("harga"))){
            array_push($validation_message,"Harga harus diisi angka");
        }
        
        if($this->post("stok")==""){
            array_push($validation_message,"Stok tidak boleh kosong");
        }

        if($this->input->post("stok")!="" && !is_numeric($this->input->post("stok"))){
            array_push($validation_message,"Stok harus diisi angka");
        }

        if(count($validation_message)>0){
            $data_json=array(
                "success"=>false,
                "message"=>"Data tidak valid",
                "data"=>$validation_message
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        }

        //jika lolos validasi
        $data=array(
            'admin_id'=>$this->post('admin_id'),
            'nama'=>$this->post('nama'),
            'harga'=>$this->post('harga'),
            'stok'=>$this->post('stok')
        );

        $result=$this->M_produk->insertProduk($data);
        
        $data_json=array(
            "success"=>true,
            "message"=>"Insert Berhasil",
            "data"=>array(
                "produk"=>$data
            )
        );

        $this->response($data_json,REST_Controller::HTTP_OK);
    }

    public function produk_put(){
        $this->cekToken();
        //validasi
        $validation_message=[];

        if($this->put("id")==""){
            array_push($validation_message,"ID tidak boleh kosong");
        }

        if($this->put("admin_id")==""){
            array_push($validation_message,"ID Admin tidak boleh kosong");
        }

        if($this->put("admin_id")!="" && !$this->M_admin->cekAdminExist($this->put("admin_id"))){
            array_push($validation_message,"ID Admin tidak ditemukan");
        }

        if($this->put("nama")==""){
            array_push($validation_message,"Nama tidak boleh kosong");
        }

        if($this->put("harga")==""){
            array_push($validation_message,"Harga tidak boleh kosong");
        }

        if($this->put("harga")!="" && !is_numeric($this->put("harga"))){
            array_push($validation_message,"Harga harus diisi angka");
        }
        
        if($this->put("stok")==""){
            array_push($validation_message,"Stok tidak boleh kosong");
        }

        if($this->put("stok")!="" && !is_numeric($this->put("stok"))){
            array_push($validation_message,"Stok harus diisi angka");
        }

        if(count($validation_message)>0){
            $data_json=array(
                "success"=>false,
                "message"=>"Data tidak valid",
                "data"=>$validation_message
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        }

        //jika lolos validasi
        $data=array(
            'admin_id'=>$this->put('admin_id'),
            'nama'=>$this->put('nama'),
            'harga'=>$this->put('harga'),
            'stok'=>$this->put('stok')
        );

        $id=$this->put("id");
        $result=$this->M_produk->updateProduk($data,$id);
        
        $data_json=array(
            "success"=>true,
            "message"=>"Update Berhasil",
            "data"=>array(
                "produk"=>$result
            )
        );

        $this->response($data_json,REST_Controller::HTTP_OK);
    }

    public function produk_delete(){
        $this->cekToken();

        $id=$this->delete("id");
        $result=$this->M_produk->deleteProduk($id);

        if(empty($result)){
            $data_json=array(
                "success"=>false,
                "message"=>"Id tidak valid",
                "data"=>null
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        }

        $data_json=array(
            "success"=>true,
            "message"=>"Delete Berhasil",
            "data"=>array(
                "produk"=>$result
            )
        );

        $this->response($data_json,REST_Controller::HTTP_OK);

    }
    //API Produk End

    //API Transaksi Start 
    public function transaksi_get()
	{

        $this->cekToken();
        $data=$this->M_transaksi->getTransaksi();
        $result=array(
            "success"=>true,
            "message"=>"Data found",
            "data"=>array(
                "transaksi"=>$data
            )
        );

        echo json_encode ($result);

        // $this->cekToken();

        // $result=$this->M_transaksi->getTransaksi();

        // if(empty($result)){
        //     $data_json=array(
        //         "success"=>false,
        //         "message"=>"Data not found",
        //         "data"=>null
        //     );

        //     $this->response($data_json,REST_Controller::HTTP_OK);
        //     $this->output->_display();
        //     exit();
        // }

        // $data_json=array(
        //     "success"=>true,
        //     "message"=>"Data found",
        //     "data"=>array(
        //         "transaksi"=>$result
        //     )
        // );

        // $this->response($data_json,REST_Controller::HTTP_OK);
    }
    
    public function transaksi_post()
    {
        $this->cekToken();
        //validasi
        $validation_message=[];

        if($this->post("admin_id")==""){
            array_push($validation_message,"ID Admin tidak boleh kosong");
        }

        if($this->post("admin_id")!="" && !$this->M_admin->cekAdminExist($this->input->post("admin_id"))){
            array_push($validation_message,"ID Admin tidak ditemukan");
        }

        if($this->post("total")==""){
            array_push($validation_message,"Total tidak boleh kosong");
        }

        if($this->input->post("total")!="" && !is_numeric($this->input->post("total"))){
            array_push($validation_message,"Total harus diisi angka");
        }

        if(count($validation_message)>0){
            $data_json=array(
                "success"=>false,
                "message"=>"Data tidak valid",
                "data"=>$validation_message
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        }

        //jika lolos validasi
        $data=array(
            'admin_id'=>$this->post('admin_id'),
            'tanggal'=>date("Y-m-d H:i:s"),
            'total'=>$this->post('total')
        );

        $result=$this->M_transaksi->insertTransaksi($data);
        
        $data_json=array(
            "success"=>true,
            "message"=>"Insert Berhasil",
            "data"=>array(
                "transaksi"=>$result
            )
        );

        $this->response($data_json,REST_Controller::HTTP_OK);
    }

    public function transaksi_put(){
        $this->cekToken();
        //validasi
        $validation_message=[];

        if($this->put("id")==""){
            array_push($validation_message,"ID tidak boleh kosong");
        }

        if($this->put("admin_id")==""){
            array_push($validation_message,"ID Admin tidak boleh kosong");
        }

        if($this->put("admin_id")!="" && !$this->M_admin->cekAdminExist($this->put("admin_id"))){
            array_push($validation_message,"ID Admin tidak ditemukan");
        }

        if($this->put("total")==""){
            array_push($validation_message,"Total tidak boleh kosong");
        }

        if($this->put("total")!="" && !is_numeric($this->put("total"))){
            array_push($validation_message,"Total harus diisi angka");
        }

        if(count($validation_message)>0){
            $data_json=array(
                "success"=>false,
                "message"=>"Data tidak valid",
                "data"=>$validation_message
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        }

        //jika lolos validasi
        $data=array(
            'admin_id'=>$this->put('admin_id'),
            'tanggal'=>date("Y-m-d H:i:s"),
            'total'=>$this->put('total')
        );

        $id=$this->put("id");
        $result=$this->M_transaksi->updateTransaksi($data,$id);
        
        $data_json=array(
            "success"=>true,
            "message"=>"Update Berhasil",
            "data"=>array(
                "transaksi"=>$result
            )
        );

        $this->response($data_json,REST_Controller::HTTP_OK);
    }

    public function transaksi_delete(){
        $this->cekToken();

        $id=$this->delete("id");
        $result=$this->M_transaksi->deleteTransaksi($id);

        if(empty($result)){
            $data_json=array(
                "success"=>false,
                "message"=>"Id tidak valid",
                "data"=>null
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        }

        $data_json=array(
            "success"=>true,
            "message"=>"Delete Berhasil",
            "data"=>array(
                "transaksi"=>$result
            )
        );

        $this->response($data_json,REST_Controller::HTTP_OK);

    }

    public function transaksi_bulan_ini_get()
	{
        $this->cekToken();

        $data=$this->M_transaksi->getTransaksiMonth();
        $dataTotal=$this->M_transaksi->getTransaksiMonthtotal();

        $result=array(
            "success"=>true,
            "message"=>"Data found",
            "data"=>array(
                "total"=>$dataTotal->total,
                "transaksi"=>$data
            )
        );

        echo json_encode($result);

        // if(empty($result)){
        //     $data_json=array(
        //         "success"=>false,
        //         "message"=>"Data not found",
        //         "data"=>null
        //     );

        //     $this->response($data_json,REST_Controller::HTTP_OK);
        //     $this->output->_display();
        //     exit();
        // }

        // $data_json=array(
        //     "success"=>true,
        //     "message"=>"Data found",
        //     "data"=>array(
        //         "transaksi"=>$result
        //     )
        // );

        // $this->response($data_json,REST_Controller::HTTP_OK);
    }
    //API Transaksi End

    //API Item Transaksi Start
    public function item_transaksi_get()
	{
        $this->cekToken();
        $data=$this->M_item_transaksi->getItem();
        $result=array(
            "success"=>true,
            "message"=>"Data found",
            "data"=>array(
                "item_transaksi"=>$data
            )
        );

        echo json_encode ($result);

        // $this->cekToken();

        // $result=$this->M_item_transaksi->getItem();

        // if(empty($result)){
        //     $data_json=array(
        //         "success"=>false,
        //         "message"=>"Data not found",
        //         "data"=>null
        //     );

        //     $this->response($data_json,REST_Controller::HTTP_OK);
        //     $this->output->_display();
        //     exit();
        // }

        // $data_json=array(
        //     "success"=>true,
        //     "message"=>"Data found",
        //     "data"=>array(
        //         "item_transaksi"=>$result
        //     )
        // );

        // $this->response($data_json,REST_Controller::HTTP_OK);
    }
    
    public function item_transaksi_post()
    {
        $this->cekToken();
        //validasi
        $validation_message=[];

        if($this->post("transaksi_id")==""){
            array_push($validation_message,"ID Transaksi tidak boleh kosong");
        }

        if($this->post("transaksi_id")!="" && !$this->M_transaksi->cekTransaksiExist($this->input->post("transaksi_id"))){
            array_push($validation_message,"ID Transaksi tidak ditemukan");
        }

        if($this->post("produk_id")==""){
            array_push($validation_message,"ID Produk tidak boleh kosong");
        }

        if($this->post("produk_id")!="" && !$this->M_produk->cekProdukExist($this->input->post("produk_id"))){
            array_push($validation_message,"ID Produk tidak ditemukan");
        }

        if($this->post("qty")==""){
            array_push($validation_message,"Qty tidak boleh kosong");
        }

        if($this->input->post("qty")!="" && !is_numeric($this->input->post("qty"))){
            array_push($validation_message,"Qty harus diisi angka");
        }

        if($this->post("harga_saat_transaksi")==""){
            array_push($validation_message,"Harga tidak boleh kosong");
        }

        if($this->input->post("harga_saat_transaksi")!="" && !is_numeric($this->input->post("harga_saat_transaksi"))){
            array_push($validation_message,"Harga harus diisi angka");
        }

        if(count($validation_message)>0){
            $data_json=array(
                "success"=>false,
                "message"=>"Data tidak valid",
                "data"=>$validation_message
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        }

        //jika lolos validasi
        $data=array(
            'transaksi_id'=>$this->post('transaksi_id'),
            'produk_id'=>$this->post('produk_id'),
            'qty'=>$this->post('qty'),
            'harga_saat_transaksi'=>$this->post('harga_saat_transaksi'),
            'sub_total'=>$this->post('qty')*$this->post('harga_saat_transaksi')
        );

        $insert=$this->M_item_transaksi->insertItem($data);
        
        // $data_json=array(
        //     "success"=>true,
        //     "message"=>"Insert Berhasil",
        //     "data"=>array(
        //         "item_transaksi"=>$data
        //     )
        // );

        // $this->response($data_json,REST_Controller::HTTP_OK);

        if ($insert) {
            $this->response($data, 200);
        } else {
            $this->response($data, 502);
        }
    }

    public function item_transaksi_put(){
        $this->cekToken();
        //validasi
        $validation_message=[];

        if($this->put("id")==""){
            array_push($validation_message,"ID tidak boleh kosong");
        }

        if($this->put("transaksi_id")==""){
            array_push($validation_message,"ID Transaksi tidak boleh kosong");
        }

        if($this->put("transaksi_id")!="" && !$this->M_transaksi->cekTransaksiExist($this->put("transaksi_id"))){
            array_push($validation_message,"ID Transaksi tidak ditemukan");
        }

        if($this->put("produk_id")==""){
            array_push($validation_message,"ID Produk tidak boleh kosong");
        }

        if($this->put("produk_id")!="" && !$this->M_produk->cekProdukExist($this->put("produk_id"))){
            array_push($validation_message,"ID Produk tidak ditemukan");
        }

        if($this->put("qty")==""){
            array_push($validation_message,"Qty tidak boleh kosong");
        }

        if($this->put("qty")!="" && !is_numeric($this->put("qty"))){
            array_push($validation_message,"Qty harus diisi angka");
        }

        if($this->put("harga_saat_transaksi")==""){
            array_push($validation_message,"Harga tidak boleh kosong");
        }

        if($this->put("harga_saat_transaksi")!="" && !is_numeric($this->put("harga_saat_transaksi"))){
            array_push($validation_message,"Harga harus diisi angka");
        }

        if(count($validation_message)>0){
            $data_json=array(
                "success"=>false,
                "message"=>"Data tidak valid",
                "data"=>$validation_message
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        }

        //jika lolos validasi
        $data=array(
            'transaksi_id'=>$this->put('transaksi_id'),
            'produk_id'=>$this->put('produk_id'),
            'qty'=>$this->put('qty'),
            'harga_saat_transaksi'=>$this->put('harga_saat_transaksi'),
            'sub_total'=>$this->put('qty')*$this->put('harga_saat_transaksi')
        );

        $id=$this->put("id");
        $result=$this->M_item_transaksi->updateItem($data,$id);
        
        $data_json=array(
            "success"=>true,
            "message"=>"Update Berhasil",
            "data"=>array(
                "item_transaksi"=>$result
            )
        );

        $this->response($data_json,REST_Controller::HTTP_OK);
    }

    public function item_transaksi_delete(){
        $this->cekToken();

        $id=$this->delete("id");
        $result=$this->M_item_transaksi->deleteItem($id);

        if(empty($result)){
            $data_json=array(
                "success"=>false,
                "message"=>"Id tidak valid",
                "data"=>null
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        }

        $data_json=array(
            "success"=>true,
            "message"=>"Delete Berhasil",
            "data"=>array(
                "item_transaksi"=>$result
            )
        );

        $this->response($data_json,REST_Controller::HTTP_OK);

    }

    public function item_transaksi_by_transaksi_id_get()
	{
        $this->cekToken();

        // $id=$this->input->get("transaksi_id");
        $result=$this->M_item_transaksi->getItemTransaksi($this->input->get('transaksi_id'));

        if(empty($result)){
            $data_json=array(
                "success"=>false,
                "message"=>"Data not found",
                "data"=>null
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        }

        $data_json=array(
            "success"=>true,
            "message"=>"Data found",
            "data"=>array(
                "item_transaksi"=>$result
            )
        );

        $this->response($data_json,REST_Controller::HTTP_OK);
    }

    public function item_transaksi_by_transaksi_id_delete(){
        $this->cekToken();

        $transaksi_id=$this->delete("transaksi_id");
        $result=$this->M_item_transaksi->deleteTransaksiById($transaksi_id);

        if(empty($result)){
            $data_json=array(
                "success"=>false,
                "message"=>"Id tidak valid",
                "data"=>null
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        }

        $data_json=array(
            "success"=>true,
            "message"=>"Delete Berhasil",
            "data"=>array(
                "item_transaksi"=>$result
            )
        );

        $this->response($data_json,REST_Controller::HTTP_OK);

    }
    //API Item Transaksi 

    //API supplier START
    //fungsi get supplier
    public function supplier_get()
	{
        //AUTHORIZATION Bearer Token
        $this->cekToken();

        $result=$this->M_supplier->getSupplier();

        //menampilkan semua data supplier
        $data_json=array(
            "success" => true,
            "message"=>"Data found",
            "data"=> array(
                "supplier" => $result
            )
        );

        $this->response($data_json,REST_Controller::HTTP_OK);
    }
    
    //fungsi post supplier
    public function supplier_post()
    {    
        //AUTHORIZATION Bearer Token   
        $this->cekToken();
        //validasi
        $validation_message=[];

        if($this->input->post("admin_id")==""){
            array_push($validation_message,"Admin ID tidak boleh kosong");
        }

        //validasi input admin id harus sesuai dengan data yang ada pada admin
        if($this->input->post("admin_id")!="" && !$this->M_admin->cekAdminExist($this->input->post("admin_id"))){
            array_push($validation_message,"Admin ID tidak ditemukan");
        }
        
        if($this->input->post("nama")==""){
            array_push($validation_message,"Nama tidak boleh kosong");
        }

        if($this->input->post("produk_id")==""){
            array_push($validation_message,"Produk ID tidak boleh kosong");
        }

        //validasi input produk id harus sesuai dengan data yang ada pada produk
        if($this->input->post("produk_id")!="" && !$this->M_produk->cekProdukExist($this->input->post("produk_id"))){
            array_push($validation_message,"Produk ID tidak ditemukan");
        }

        if($this->input->post("harga")==""){
            array_push($validation_message,"Harga tidak boleh kosong");
        }

        //validasi input untuk harga harus berupa numeric
        if($this->input->post("harga")!="" && !is_numeric($this->input->post("harga"))){
            array_push($validation_message,"Harga harus diisi angka");
        }

        if($this->input->post("jumlah")==""){
            array_push($validation_message,"jumlah tidak boleh kosong");
        }

        //validasi input untuk jumlah harus berupa numeric
        if($this->input->post("jumlah")!="" && !is_numeric($this->input->post("jumlah"))){
            array_push($validation_message,"jumlah harus diisi angka");
        }

        //jika validasi gagal
        if(count($validation_message)>0){
            $data_json=array(
                "success"=>false,
                "message"=>"Data tidak valid",
                "data"=>$validation_message
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        }

        //jika validasi berhasil
        $data=array(
            "admin_id"=>$this->post("admin_id"),
            "nama"=>$this->post("nama"),
            "produk_id"=>$this->post("produk_id"),
            "harga"=>$this->post("harga"),
            "jumlah"=>$this->post("jumlah")
        );

        $result=$this->M_supplier->insertSupplier($data);
        
        //menampilkan data supplier yang berhasil di insert
        $data_json=array(
            "success"=>true, 
            "message"=>"Insert Berhasil",
            "data"=>array( 
                "supplier"=>$data
            )
        );

        $this->response($data_json,REST_Controller::HTTP_OK);
    }

    //fungsi put supplier
    public function supplier_put()
    {
        //AUTHORIZATION Bearer Token
        $this->cekToken();
        //validasi
        $validation_message=[];

        if($this->put("id")==""){
            array_push($validation_message,"ID tidak boleh kosong");
        }

        if($this->put("admin_id")==""){
            array_push($validation_message,"Admin ID tidak boleh kosong");
        }

        //validasi input admin id harus sesuai dengan data yang ada pada admin
        if($this->put("admin_id")!="" && !$this->M_admin->cekAdminExist($this->put("admin_id"))){
            array_push($validation_message,"Admin ID tidak ditemukan");
        }
        
        if($this->put("nama")==""){
            array_push($validation_message,"Nama tidak boleh kosong");
        }

        if($this->put("produk_id")==""){
            array_push($validation_message,"Produk ID tidak boleh kosong");
        }

        //validasi input produk id harus sesuai dengan data yang ada pada produk
        if($this->put("produk_id")!="" && !$this->M_produk->cekProdukExist($this->put("produk_id"))){
            array_push($validation_message,"Produk ID tidak ditemukan");
        }

        if($this->put("harga")==""){
            array_push($validation_message,"Harga tidak boleh kosong");
        }

        //validasi input untuk harga harus berupa numeric
        if($this->put("harga")!="" && !is_numeric($this->put("harga"))){
            array_push($validation_message,"Harga harus diisi angka");
        }

        if($this->put("jumlah")==""){
            array_push($validation_message,"jumlah tidak boleh kosong");
        }

        //validasi input pada jumlah harus berupa numeric
        if($this->put("jumlah")!="" && !is_numeric($this->put("jumlah"))){
            array_push($validation_message,"jumlah harus diisi angka");
        }

        //jika validasi gagal
        if(count($validation_message)>0){
            $data_json=array(
                "success"=>false,
                "message"=>"Data tidak valid",
                "data"=>$validation_message
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        }

        //jika validasi berhasil
        $data=array(
            "admin_id"=>$this->put("admin_id"),
            "nama"=>$this->put("nama"),
            "produk_id"=>$this->put("produk_id"),
            "harga"=>$this->put("harga"),
            "jumlah"=>$this->put("jumlah")
        );

        $id=$this->put("id");
        $result=$this->M_supplier->updateSupplier($data,$id);
        
        //menampilkan data supplier yang berhasil di update
        $data_json=array(
            "success"=>true,
            "message"=>"Update Berhasil",
            "data"=>array(
                "supplier"=>$result
            )
        );

        $this->response($data_json,REST_Controller::HTTP_OK);
    }

    //fungsi delete supplier
    public function supplier_delete()
    {
        //AUTHORIZATION Bearer Token
        $this->cekToken();

        //delete berdasarkan id
        $id=$this->delete("id");
        $result=$this->M_supplier->deleteSupplier($id);

        //jika delete gagal karena id tidak valid
        if(empty($result)){
            $data_json=array(
                "success"=>false,
                "message"=>"Id tidak valid",
                "data"=>null
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        }

        //menampilkan data supplier jika delete berhasil
        $data_json=array(
            "success"=>true,
            "message"=>"Delete Berhasil",
            "data"=>array(
                "supplier"=>$result
            )
        );

        $this->response($data_json,REST_Controller::HTTP_OK);

    }
    //API supplier END
}
