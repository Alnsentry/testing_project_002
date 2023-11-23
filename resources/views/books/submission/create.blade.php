<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
<div class="text-center mb-4">
    <h3>Buat Pengajuan Pinjaman</h3>
    <p>Buat pengajuan pinjaman</p>
</div>

<form method="POST" action="{{ route('submissions.store') }}" enctype="multipart/form-data">
  @csrf
    <div class="row mb-3">
        <div class="col-md-4 mb-3">
            <label for="book_id" class="form-label">Buku</label>
            <select class="select-book" name="book_id" id="book_id" style="width:100%;" required autofocus>
                @foreach($books as $data)
                    <option value="{{$data->id}}">{{$data->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label for="number_stock" class="form-label">Jumlah</label>
            <input type="number" class="form-control" placeholder="Masukan jumlah buku yang akan dipinjam" id="number_stock" name="number_stock" :value="old('number_stock')" required autofocus autocomplete="number_stock">
        </div>
        <div class="col-md-4 mb-3">
            <label for="return_date" class="form-label">Tanggal Pengembalian</label>
            <input type="date" class="form-control" placeholder="Masukan tanggal pengembalian buku" id="return_date" name="return_date" :value="old('return_date')" required autofocus autocomplete="return_date">
        </div>
    </div>

    <div class="col-12 text-center">
        <button type="submit" class="btn btn-primary me-sm-3 me-1">Kirim</button>
        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Batal</button>
    </div>
</form>

<script>
    var select2 = $('.select-book').select2({
        dropdownParent: $("#smallModal")
    });

    select2.data('select2').$selection.css('height', '39px');
    select2.data('select2').$selection.css('padding-top', '5px');
    select2.data('select2').$selection.css('border-color', '#d9dee3');
</script>