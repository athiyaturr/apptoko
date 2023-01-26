<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_transaksi extends CI_Model {

    function __construct(){
        parent::__construct();
        $this->load->database();
    }

	public function getTransaksi()
	{
        // $data=$this->db->get('transaksi');
        $this->db->select('t.id,admin.nama as nama,t.total,,t.tanggal');
        $this->db->from('transaksi t');
        $this->db->join('admin','admin.id=t.admin_id');
        $query=$this->db->get();
        return $query->result_array();
    }
    
    public function insertTransaksi($data)
	{
        //$insert  = $this->db->insert('transaksi', $data);
        // $this->db>insert('transaksi', $data);
        // $insert_id=$this->db->insert_id();
        // $result=$this->db->get_where('transaksi',array('id'=>$insert_id));

        // return $result->row_array();

        $this->db->insert('transaksi', $data);
        $insert_id = $this->db->insert_id();
        $result = $this->db->get_where('transaksi', array('id' => $insert_id));
        return $result->row_array();
    }

    public function updateTransaksi($data,$id){
        $this->db->where('id',$id);
        $this->db->update('transaksi',$data);
        $result=$this->db->get_where('transaksi',array('id'=>$id));
        return $result->row_array();
    }

    public function deleteTransaksi($id){
        $result=$this->db->get_where('transaksi',array('id'=>$id));
        $this->db->where('id',$id);
        $this->db->delete('transaksi');
        return $result->row_array();
    }

    public function getTransaksiMonthly(){
        $this->load->database();
        $month = date("m");
        $data = $this->db->query("SELECT * FROM transaksi WHERE MONTH(tanggal) = '".$month."' ");
        return $data->result_array();
    }

    public function getTransaksiMonth()
	{
        // $data=$this->db->get('transaksi');
        $this->db->select('t.id,t.admin_id,admin.nama as nama_admin,t.tanggal,t.total');
        $this->db->from('transaksi t');
        $this->db->join('admin','admin.id=t.admin_id');
        $this->db->where('month(tanggal)',date('m'));
        $query=$this->db->get();
        return $query->result_array();
    }

    public function getTransaksiMonthtotal()
	{
        // $data=$this->db->get('transaksi');
        $this->db->select('sum(t.total) as total');
        $this->db->from('transaksi t');
        $this->db->join('admin','admin.id=t.admin_id');
        $this->db->where('month(tanggal)',date('m'));
        $query=$this->db->get();
        return $query->row_object();
    }


    public function cekTransaksiExist($id){
        $data=array(
            "id"=>$id
        );

        $this->db->where($data);
        $result=$this->db->get('transaksi');

        if(empty($result->row_array())){
            return false;
        }
        
        return true;
    }
}
