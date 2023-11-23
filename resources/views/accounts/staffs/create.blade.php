<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
<div class="text-center mb-4">
    <h3>Buat Personil</h3>
    <p>Atur akun personil</p>
</div>

<form method="POST" action="{{ route('personnels.store') }}" enctype="multipart/form-data">
  @csrf
  <div class="row mb-3">
    <div class="col-md-6">
      <label for="name" class="form-label">Nama</label>
      <input type="text" class="form-control" placeholder="Masukkan nama" id="name" name="name" :value="old('name')" required autofocus autocomplete="name">
    </div>

    <div class="col-md-6">
      <label for="username" class="form-label">Username</label>
      <input type="text" class="form-control" placeholder="Masukan username" id="username" name="username" :value="old('username')" required autofocus autocomplete="username">
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-12">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control" placeholder="Masukan email" id="email" name="email" :value="old('email')" required autofocus autocomplete="email">
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-6">
      <label for="password" class="form-label">Password</label>
      <input type="password" class="form-control" placeholder="Masukan password" id="password" name="password" :value="old('password')" required autofocus autocomplete="new-password">
    </div>

    <div class="col-md-6">
      <label for="password_confirmation" class="form-label">Confirm Password</label>
      <input type="password" class="form-control" placeholder="Masukan password" id="password_confirmation" name="password_confirmation" :value="old('confirm-name')" required autofocus autocomplete="new-password">
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-12">
      <label for="roles" class="form-label">Roles</label>
      <select name="roles[]" id="roles" class="form-control">
        @foreach($roles as $data)
          <option value="{{$data->name}}">{{$data->name}}</option>
        @endforeach
      </select>
    </div>
  </div>

  <div class="col-12 text-center">
    <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
  </div>
</form>