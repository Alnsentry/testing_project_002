<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
<div class="text-center mb-4">
    <h3>Edit Book</h3>
    <p>Set book</p>
</div>

<form method="POST" action="{{ route('books.update', $book->id) }}" enctype="multipart/form-data">
  @csrf
  @method("PUT")
  <div class="row mb-3">
    <div class="col-md-12 mb-3">
      <label for="name" class="form-label">Nama</label>
      <input type="text" class="form-control" placeholder="Enter your name" id="name" name="name" :value="old('name')" required autofocus autocomplete="name" value="{{$book->name}}">
    </div>

    <div class="col-md-12 mb-3">
      <label for="author" class="form-label">Penulis</label>
      <input type="text" class="form-control" placeholder="Enter your author" id="author" name="author" :value="old('author')" required autofocus autocomplete="author" value="{{$book->author}}">
    </div>

    <div class="col-md-12 mb-3">
      <label for="publish_year" class="form-label">Tahun Rilis</label>
      <input type="number" min="1900" max="2099" step="1" value="{{$book->publish_year}}" class="form-control" placeholder="Enter your publish_year" id="publish_year" name="publish_year" :value="old('publish_year')" required autofocus autocomplete="publish_year">
    </div>

    <div class="col-md-12 mb-3">
      <label for="stock" class="form-label">Stock</label>
      <input type="number" class="form-control" placeholder="Enter your stock" id="stock" name="stock" :value="old('stock')" required autofocus autocomplete="stock" value="{{$book->stock}}">
    </div>
  </div>

  <div class="col-12 text-center">
    <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
  </div>
</form>