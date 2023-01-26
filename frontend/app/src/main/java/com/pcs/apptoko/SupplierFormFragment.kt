package com.pcs.apptoko

import android.os.Bundle
import android.util.Log
import androidx.fragment.app.Fragment
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import android.widget.TextView
import android.widget.Toast
import androidx.navigation.fragment.findNavController
import com.google.android.material.textfield.TextInputEditText
import com.pcs.apptoko.api.BaseRetrofit
import com.pcs.apptoko.response.produk.Produk
import com.pcs.apptoko.response.produk.ProdukResponsePost
import com.pcs.apptoko.response.supplier.Supplier
import com.pcs.apptoko.response.supplier.SupplierResponsePost
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response

class SupplierFormFragment : Fragment() {

    private val api by lazy { BaseRetrofit().endpoint }

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        val view = inflater.inflate(R.layout.fragment_supplier_form, container, false)

        val btnProsesSupplier = view.findViewById<Button>(R.id.btnProsesSupplier)

        val txtFormNama = view.findViewById<TextView>(R.id.txtFormNama)
        val txtFormProduk = view.findViewById<TextView>(R.id.txtFormProduk)
        val txtFormHarga = view.findViewById<TextView>(R.id.txtFormHarga)
        val txtFormJumlah = view.findViewById<TextView>(R.id.txtFormJumlah)

        val status = arguments?.getString("status")
        val supplier = arguments?.getParcelable<Supplier>("supplier")

        Log.d("supplierForm",supplier.toString())

        if(status=="edit"){
            txtFormNama.setText(supplier?.nama.toString())
            txtFormProduk.setText(supplier?.produk_id.toString())
            txtFormHarga.setText(supplier?.harga.toString())
            txtFormJumlah.setText(supplier?.jumlah.toString())
        }

        btnProsesSupplier.setOnClickListener{
            val txtFormNama = view.findViewById<TextInputEditText>(R.id.txtFormNama)
            val txtFormProduk = view.findViewById<TextInputEditText>(R.id.txtFormProduk)
            val txtFormHarga = view.findViewById<TextInputEditText>(R.id.txtFormHarga)
            val txtFormJumlah = view.findViewById<TextInputEditText>(R.id.txtFormJumlah)

            val token = LoginActivity.sessionManager.getString("TOKEN")
            val adminId = LoginActivity.sessionManager.getString("ADMIN_ID")

            if(status=="edit"){
                api.putSupplier(token.toString(),supplier?.id.toString().toInt(),adminId.toString().toInt(),txtFormNama.text.toString(),txtFormProduk.text.toString().toInt(),txtFormHarga.text.toString().toInt(),txtFormJumlah.text.toString().toInt()).enqueue(object :
                    Callback<SupplierResponsePost> {
                    override fun onResponse(
                        call: Call<SupplierResponsePost>,
                        response: Response<SupplierResponsePost>
                    ) {
                        Log.d("ResponData",response.body()!!.data.toString())
                        Toast.makeText(activity?.applicationContext,"Data "+ response.body()!!.data.supplier.nama.toString() +" di edit",
                            Toast.LENGTH_LONG).show()

                        findNavController().navigate(R.id.supplierFragment)
                    }

                    override fun onFailure(call: Call<SupplierResponsePost>, t: Throwable) {
                        Log.e("Error",t.toString())
                    }

                })
            } else{
                api.postSupplier(token.toString(),adminId.toString().toInt(),txtFormNama.text.toString(),txtFormProduk.text.toString().toInt(),txtFormHarga.text.toString().toInt(),txtFormJumlah.text.toString().toInt()).enqueue(object :
                    Callback<SupplierResponsePost> {
                    override fun onResponse(
                        call: Call<SupplierResponsePost>,
                        response: Response<SupplierResponsePost>
                    ) {
                        Log.d("Data",response.toString())
                        Toast.makeText(activity?.applicationContext,"Data di input", Toast.LENGTH_LONG).show()

                        findNavController().navigate(R.id.supplierFragment)
                    }

                    override fun onFailure(call: Call<SupplierResponsePost>, t: Throwable) {
                        Log.e("Error",t.toString())
                    }

                })
            }


        }

        return view
    }


}