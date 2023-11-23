@extends('layouts/contentNavbarLayout')

@section('title', 'Akun - Daftar Hak Akses')

@section('page-style')
  <!-- Core CSS -->
  <style type="text/css">
    .pagination li{
      float: left;
      list-style-type: none;
      margin:5px;
    }
  </style>
@endsection

@section('content')
@if(Session::get('error'))
  <div class="row">
        <div class="col-md-12">
          <div class="alert alert-danger alert-dismissable my-4">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              {{Session::get('error')}}
          </div>
        </div>
    </div>
@endif

@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif

<h4 class="fw-bold py-3 mb-2">
  <span class="text-muted fw-light">Sistem / Akun /</span> Daftar Hak Akses
</h4>

<p>Hak Akses memberikan akses ke menu dan fitur yang telah ditentukan sebelumnya sehingga bergantung pada <br> hak akses yang ditetapkan, administrator dapat memiliki akses ke apa yang dibutuhkan pengguna.</p>
<!-- Role cards -->
<div class="row g-4 mb-5">
  @foreach ($roles as $data)
    <div class="col-xl-4 col-lg-6 col-md-6">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between mb-2">
            <h6 class="fw-normal">Total {{$user->role($data->name)->count()}} users</h6>
            <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
              @foreach($user->role($data->name)->limit(5)->get() as $user)
                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="{{$user->name}}" data-bs-original-title="{{$user->name}}">
                  <img class="rounded-circle" src="{{asset('img/staffs/'.$user->photo_profile)}}" alt="Avatar">
                </li>
              @endforeach
            </ul>
          </div>
          <div class="d-flex justify-content-between align-items-end">
            <div class="role-heading">
              <h4 class="mb-1">{{ $data->name }}</h4>
              @can('akun-role-edit')
                <a href="javascript:;" data-bs-target="#addRoleModal" data-bs-toggle="modal" data-attr="{{ route('access-rights.edit', $data->id) }}" class="role-edit-modal btn-crud"><small>Edit Hak Akses</small></a>
              @endcan
            </div>
            @can('akun-role-delete')
              <form action="{{ route('access-rights.destroy',$data->id) }}" method="POST">								
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="p-0 m-0 bg-transparent text-danger border-0"><i class='bx bx-trash'></i></button>
              </form>
            @endcan
          </div>
        </div>
      </div>
    </div>
  @endforeach

  @can('akun-role-create')
    <div class="col-xl-4 col-lg-6 col-md-6">
      <div class="card h-100">
        <div class="row h-100">
          <div class="col-sm-5">
            <div class="d-flex align-items-end h-100 justify-content-center mt-sm-0 mt-3">
              <img src="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo/assets/img/illustrations/sitting-girl-with-laptop-light.png" class="img-fluid" alt="Image" width="120" data-app-light-img="illustrations/sitting-girl-with-laptop-light.png" data-app-dark-img="illustrations/sitting-girl-with-laptop-dark.png">
            </div>
          </div>
          <div class="col-sm-7">
            <div class="card-body text-sm-end text-center ps-sm-0">
              <button data-bs-target="#addRoleModal" data-bs-toggle="modal" data-attr="{{ route('access-rights.create') }}" class="btn btn-primary mb-3 text-nowrap add-new-role btn-crud">Tambah Hak Akses</button>
              <p class="mb-0">Tambah hak akses, jika belum tersedia</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endcan
</div>
<!--/ Role cards -->
@endsection

<div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body" id="smallBody">
        
      </div>
    </div>
  </div>
</div>

@section('page-script')
  <script type="module">
    $(".btn-crud").click(function(e){
      e.preventDefault();
      let href = $(this).attr('data-attr');
      $.ajax({
        async: false,
        url: href,
        beforeSend: function() {
          $('#smallBody').html('');
        },
        success: function(result) {
          $('#smallBody').html(result).show(); 
          $('#myModal').modal('show');
        },
        error: function(jqXHR, testStatus, error) {
          console.log(error);
          console.log("Page " + href + " cannot open. Error:" + error);
        }
      })      
    });

    $("#addRoleModal").on('hidden.bs.modal', function () {
      $(this).data('bs.modal', null);
    });
    $('#addRoleModal').on('hidden', function() {
      $(this).removeData('modal');
    });
  </script>

  <script>
    function modalType(params) {
      $('#modType').text(params)
    }
  </script>
@endsection