 <div class="modal fade" id="modalDetailAk1" tabindex="-1">
     <div class="modal-dialog modal-lg">
         <div class="modal-content">

             <div class="modal-header bg-info text-white">
                 <h5 class="modal-title">Detail AK1</h5>
                 <button class="close text-white" data-dismiss="modal">&times;</button>
             </div>

             <div class="modal-body">

                 <div class="text-center mb-3">
                     <img id="md_foto"
                         src="https://via.placeholder.com/120x120?text=No+Image"
                         class="rounded-circle mb-2"
                         width="120"
                         height="120"
                         style="object-fit: cover; border: 2px solid #ddd; cursor:pointer;">
                     <h4 id="md_nama">-</h4>
                     <small id="md_nik" class="text-muted">-</small>
                 </div>

                 <hr>

                 <div class="row mb-2">
                     <div class="col-5 text-muted">No Pendaftaran</div>
                     <div class="col-7 font-weight-bold" id="md_no">-</div>
                 </div>

                 <div class="row mb-2">
                     <div class="col-5 text-muted">Status</div>
                     <div class="col-7" id="md_status">-</div>
                 </div>

                 <div class="row mb-2">
                     <div class="col-5 text-muted">Tanggal</div>
                     <div class="col-7" id="md_tanggal">-</div>
                 </div>

                 <div class="row mb-2">
                     <div class="col-5 text-muted">Profil Pencari Kerja</div>
                     <div class="col-7" id="md_profil">-</div>
                 </div>

                 <div class="row mb-2">
                     <div class="col-5 text-muted">Dokumen</div>
                     <div class="col-7" id="md_dokumen">
                         <small class="text-muted">Loading...</small>
                     </div>
                 </div>

             </div>

         </div>
     </div>
 </div>

 <div class="modal fade" id="modalStatusAk1" tabindex="-1">
     <div class="modal-dialog modal-lg">
         <div class="modal-content">

             <div class="modal-header bg-primary text-white">
                 <h5 class="modal-title">Verifikasi Status AK1</h5>
                 <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
             </div>

             <form id="formUpdateStatus" method="POST">
                 @csrf
                 @method('PUT')
                 <div class="modal-body">

                     <div class="form-group">
                         <label>Status</label>
                         <select name="status" id="verif_status" class="form-control" required>
                             <option value="draft">Draft</option>
                             <option value="pending">Pending</option>
                             <option value="disetujui">Disetujui</option>
                             <option value="ditolak">Ditolak</option>
                         </select>
                     </div>

                     <div class="form-group">
                         <label>Catatan Petugas</label>
                         <textarea name="catatan_petugas" class="form-control" rows="3"></textarea>
                     </div>

                     <div class="form-group">
                         <label>Nama Petugas</label>
                         <input type="text" class="form-control" value="{{ auth()->user()->nama }}" readonly>
                     </div>

                     <div class="form-group">
                         <label>NIP Petugas</label>
                         <input type="text" class="form-control" value="{{ auth()->user()->nip ?? auth()->user()->id_pengguna }}" readonly>
                     </div>

                     <div class="row mb-2">
                         <div class="col-5 text-muted">Berlaku Mulai</div>
                         <div class="col-7" id="md_berlaku_mulai">-</div>
                     </div>

                     <div class="row mb-2">
                         <div class="col-5 text-muted">Berlaku Sampai</div>
                         <div class="col-7" id="md_berlaku_sampai">-</div>
                     </div>

                 </div>

                 <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-dismiss="modal">
                         <i class="fas fa-times mr-1"></i> Batal
                     </button>

                     <button type="button" class="btn btn-primary btn-submit">
                         <i class="fas fa-save mr-1"></i> Simpan Status
                     </button>
                 </div>
             </form>

         </div>
     </div>
 </div>