package com.pcs.apptoko.adapter

import android.os.Bundle
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.ImageButton
import android.widget.TextView
import android.widget.Toast
import androidx.navigation.findNavController
import androidx.recyclerview.widget.RecyclerView
import com.pcs.apptoko.LoginActivity
import com.pcs.apptoko.R
import com.pcs.apptoko.api.BaseRetrofit
import com.pcs.apptoko.response.supplier.Supplier
import com.pcs.apptoko.response.supplier.SupplierResponsePost
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response

class SupplierAdapter(val listSupplier: List<Supplier>): RecyclerView.Adapter<SupplierAdapter.ViewHolder>() {

    private val api by lazy { BaseRetrofit().endpoint }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
        val view = LayoutInflater.from(parent.context).inflate(R.layout.item_supplier,parent,false)
        return ViewHolder(view)
    }

    override fun onBindViewHolder(holder: ViewHolder, position: Int) {
        val supplier = listSupplier[position]
        holder.txtNamaSupplier.text = supplier.nama
        holder.txtProduk.text=supplier.produk
        holder.txtHarga.text = supplier.harga
        holder.txtJumlah.text=supplier.jumlah

        val token = LoginActivity.sessionManager.getString("TOKEN")

        holder.btnDelete.setOnClickListener{
            Toast.makeText(holder.itemView.context,supplier.nama.toString(), Toast.LENGTH_LONG).show()

            api.deleteSupplier(token.toString(),supplier.id.toInt()).enqueue(object :
                Callback<SupplierResponsePost> {
                override fun onResponse(
                    call: Call<SupplierResponsePost>,
                    response: Response<SupplierResponsePost>
                ) {
                    Log.d("Data",response.toString())
                    Toast.makeText(holder.itemView.context,"Data di hapus", Toast.LENGTH_LONG).show()

                    holder.itemView.findNavController().navigate(R.id.supplierFragment)
                }

                override fun onFailure(call: Call<SupplierResponsePost>, t: Throwable) {
                    Log.e("Data",t.toString())
                }
            })
        }

        holder.btnEdit.setOnClickListener{
            //Toast.makeText(holder.itemView.context,produk.nama,Toast.LENGTH_LONG).show()
            val bundle = Bundle()
            bundle.putParcelable("supplier",supplier)
            bundle.putString("status","edit")

            holder.itemView.findNavController().navigate(R.id.supplierFormFragment,bundle)
        }
    }

    override fun getItemCount(): Int {
        return listSupplier.size
    }

    class ViewHolder(itemViem : View) : RecyclerView.ViewHolder(itemViem){
        val txtNamaSupplier = itemViem.findViewById(R.id.txtNamaSupplier) as TextView
        val txtProduk = itemViem.findViewById(R.id.txtProduk) as TextView
        val txtHarga = itemViem.findViewById(R.id.txtHarga) as TextView
        val txtJumlah = itemViem.findViewById(R.id.txtJumlah) as TextView
        val btnDelete = itemViem.findViewById(R.id.btnDelete) as ImageButton
        val btnEdit = itemViem.findViewById(R.id.btnEdit) as ImageButton
    }
}