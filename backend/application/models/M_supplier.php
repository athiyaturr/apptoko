<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_supplier extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

	public function getSupplier()
	{
        $this->db->select('supplier.id,supplier.admin_id,admin.nama as admin,supplier.nama,supplier.produk_id,produk.nama as produk,supplier.harga,supplier.jumlah');
        $this->db->from('supplier');
        $this->db->join('produk', 'produk.id = supplier.produk_id');
        $this->db->join('admin', 'admin.id = supplier.admin_id');
        $this->load->database();
        $data=$this->db->get();
        return $data->result_array();
    }
    
    public function insertSupplier($data)
	{
        $this->load->database();
        $insert  = $this->db->insert('supplier', $data);
    }

    public function updateSupplier($data,$id){
        $this->db->where('id',$id);
        $this->db->update('supplier',$data);
        $result=$this->db->get_where('supplier',array('id'=>$id));
        return $result->row_array();
    }

    public function deleteSupplier($id)
    {
        $result=$this->db->get_where('supplier',array('id'=>$id));
        $this->db->where('id',$id);
        $this->db->delete('supplier');
        return $result->row_array();
    }

    public function cekProdukExist($id)
    {
        $data=array(
            "id"=>$id
        );

        $this->db->where($data);
        $result=$this->db->get('produk');

        if(empty($result->row_array())){
            return false;
        }

        return true;
    }
}