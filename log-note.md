todo-logic-note:

- buat tabel laporan monitoring barang gabungan dari monitoring pengambilan dan monitoring pengadaan dengan detail isi tabel : 
 no
 tanggal (dari tabel monitoring barang/pengambilan atau monitoring pengadaan)
 uraian (colspan 3): keterangan(monitoring pegadaan), bidang (monitoring pengambilan/barang), pengambil(monitoring pengambilan), 
 persediaan (colspan 3): debit(monitoring pengadaan), kredit(monitoring pengambilan), saldo akhir(monitoring pengambilan)

sekarang tolong buatkan dulu tabel detail_monitoring_barang dengan rincian isi sebagai berikut:
- nama barang (sinkron/referensi dari tabel barang)
- tanggal (referensi dari tabel monitoring barang dan monitoring pengadaan)
- keterangan (referensi dari tabel monitoring barang atau monitoring pengadaan)
- bidang (referensi dari tabel monitoring barang)
- pengambil (referensi dari tabel monitoring barang)
- debit (referensi dari tabel monitoring pengadaan)
- kredit (referensi dari tabel monitoring barang)
- saldo (referensi dari stok di tabel barang)
dengan catatan urutkan berdasarkan semua isi kolom nullable kecuali tanggal dan nama barang dan saldo

- tambahkan beberapa bidang pada sistem
