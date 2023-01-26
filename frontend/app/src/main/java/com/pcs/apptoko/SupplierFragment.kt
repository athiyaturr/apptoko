package com.pcs.apptoko

import android.os.Bundle
import android.util.Log
import androidx.fragment.app.Fragment
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import android.widget.TextView
import androidx.navigation.fragment.findNavController
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.pcs.apptoko.api.BaseRetrofit
import com.pcs.apptoko.adapter.SupplierAdapter
import com.pcs.apptoko.response.supplier.SupplierResponse
import com.pcs.apptoko.response.supplier.Data
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response

class SupplierFragment : Fragment() {

    private val api by lazy { BaseRetrofit().endpoint }

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        val view = inflater.inflate(R.layout.fragment_supplier, container, false)

        getSupplier(view)

        val btnTambah = view.findViewById<Button>(R.id.btnTambah)
        btnTambah.setOnClickListener{
            //Toast.makeText(activity?.applicationContext,"Click", Toast.LENGTH_LONG).show()

            val bundle = Bundle()
            bundle.putString("status","tambah")

            findNavController().navigate(R.id.supplierFormFragment,bundle)
        }

        return view
    }

    fun getSupplier(view:View){
        val token = LoginActivity.sessionManager.getString("TOKEN")

        api.getSupplier(token.toString()).enqueue(object : Callback<SupplierResponse> {
            override fun onResponse(
                call: Call<SupplierResponse>,
                response: Response<SupplierResponse>
            ) {
                Log.d("SupplierData",response.body().toString())

                val txtTotalSupplier = view.findViewById(R.id.txtTotalSupplier) as TextView
                val rv = view.findViewById(R.id.rv_supplier) as RecyclerView

                //txtTotalSupplier.text = response.body()!!.data.supplier.size.toString() + " item"
                txtTotalSupplier.text=response.body()!!.data.supplier.size.toString() + " item"

                rv.setHasFixedSize(true)
                rv.layoutManager = LinearLayoutManager(activity)
                val rvAdapter = SupplierAdapter(response.body()!!.data.supplier)
                rv.adapter = rvAdapter
            }

            override fun onFailure(call: Call<SupplierResponse>, t: Throwable) {
                Log.e("SupplierError",t.toString())
            }

        })
    }
}