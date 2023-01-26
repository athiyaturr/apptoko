package com.pcs.apptoko.response.supplier

import android.os.Parcelable
import kotlinx.parcelize.Parcelize

@Parcelize
data class Supplier(
    val admin_id: String,
    val harga: String,
    val id: String,
    val nama: String,
    val produk_id: String,
    val admin: String,
    val produk: String,
    val jumlah: String
): Parcelable