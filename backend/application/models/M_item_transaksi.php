<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_item_transaksi extends CI_Model {

    function __construct(){
        parent::__construct();
        $this->load->database();
    }

	public function getItem()
	{
        $this->db->select('t.id,t.transaksi_id,t.produk_id,produk.nama as nama,t.qty,t.harga_saat_transaksi,t.sub_total');
        $this->db->from('item_transaksi t');
        $this->db->join('produk','produk.id=t.produk_id');
        $query=$this->db->get();
        return $query->result_array();

        // $data=$this->db->get('item_transaksi');
        // return $data->result_array();
    }
    
    public function insertItem($data)
	{
        //input all data
        $this->db->insert('item_transaksi', $data);
        $insert_id = $this->db->insert_id();
        $result = $this->db->get_where('item_transaksi', array('id' => $insert_id));

        //update stok
        $result_produk=$this->db->get_where('produk',array('id'=>$data["produk_id"]));
        $result_produk=$result_produk->row_array();
        $stok_lama=$result_produk["stok"];
        $stok_baru=$stok_lama-$data["qty"];

        $data_produk_update=array(
            "stok"=>$stok_baru
        );

        $this->db->where('id',$data["produk_id"]);
        $this->db->update('produk',$data_produk_update);

        return $result->row_array();

        // $insert  = $this->db->insert('item_transaksi', $data);
        // $this->db>insert('item_transaksi', $data);
    }

    public function updateItem($data,$id){
        $this->db->where('id',$id);
        $this->db->update('item_transaksi',$data);
        $result=$this->db->get_where('item_transaksi',array('id'=>$id));
        return $result->row_array();
    }

    public function deleteItem($id){
        $result=$this->db->get_where('item_transaksi',array('id'=>$id));
        $this->db->where('id',$id);
        $this->db->delete('item_transaksi');
        return $result->row_array();
    }

    public function getItemTransaksi($transaksi_id)
	{
        $this->db->select('t.id,t.transaksi_id,t.produk_id,produk.nama as nama_produk,t.qty,t.harga_saat_transaksi,t.sub_total');
        $this->db->from('item_transaksi t');
        $this->db->join('produk','produk.id=t.produk_id');
        $this->db->where('t.transaksi_id',$transaksi_id);
        $query=$this->db->get();
        return $query->result_array();
    }

    public function deleteTransaksiById($transaksi_id){
        $result=$this->db->get_where('item_transaksi',array('transaksi_id'=>$transaksi_id));
        $this->db->where('transaksi_id',$transaksi_id);
        $this->db->delete('item_transaksi');
        return $result->result_array();
    }
}
