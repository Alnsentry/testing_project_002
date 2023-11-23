@extends('layouts/contentNavbarLayout')

@section('title', 'Akun - Daftar Personil')

@section('page-style')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <style type="text/css">
    .pagination li{
      float: left;
      list-style-type: none;
      margin:5px;
    }
  </style>
@endsection

@section('content')
  <h4 class="fw-bold py-3 mb-2">
    <span class="text-muted fw-light">Sistem / Akun /</span> Daftar Personil
  </h4>

  @if($errors->count() != 0)
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-danger alert-dismissable my-4">
          @php
            foreach ($errors->all() as $message) {
              echo '<div>'.$message.'</div>';
            }
          @endphp
        </div>
      </div>
    </div>
  @endif
  
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
  
  <div class="card">
    <h5 class="card-header">Daftar Personil</h5>
    
    @can('akun-staff-create')
      <div class="text-end">
        <button class="btn btn-success btn-crud me-4 mb-3" data-bs-toggle="modal" data-bs-target="#smallModal" data-attr="{{ route('personnels.create') }}">
          Buat Personil
        </button>
      </div>
    @endcan
				
    <div class="table-responsive p-4">
      <table class="table table-striped stripe table-borderless" id="tblGroup" style="border:0">
        <thead>
          <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            @if(Auth::user()->can('akun-staff-edit') || Auth::user()->can('akun-staff-delete'))
              <th>Action</th>
            @endif
          </tr>
        </thead>
        <tbody>
          @php $increment = 1 @endphp
          @foreach ($staffs as $data)
            @if($data->username !== "super_admin")
              <tr>
                <td>{{ $increment++ }}</td>
                <td>{{ $data->name }}</td>
                <td>{{ $data->username }}</td>
                <td>{{ $data->email }}</td>
                <td>
                  @if(!empty($data->getRoleNames()))
                    @foreach($data->getRoleNames() as $v)
                      {{ $v }}
                    @endforeach
                  @endif
                </td>
                @if(Auth::user()->can('akun-staff-edit') || Auth::user()->can('akun-staff-delete'))
                  <td style="width:200px">
                      <div class="dropdown">
                          <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                          <div class="dropdown-menu">
                              @can('akun-staff-edit')
                                  <button class="dropdown-item btn-crud" data-bs-toggle="modal" data-bs-target="#smallModal" data-attr="{{ route('personnels.edit', $data->id) }}" onclick="modalType('Edit')">
                                      <i class="bx bx-edit-alt me-1"></i> Edit
                                  </button>
                              @endcan
                              @can('akun-staff-delete')
                                  <form action="{{ route('personnels.destroy',$data->id) }}" method="POST">								
                                      @csrf
                                      @method('DELETE')
                                      <button type="submit" class="dropdown-item"><i class="bx bx-trash me-1"></i> Hapus</button>
                                  </form>
                              @endcan
                          </div>
                      </div>
                  </td>
                @endif
              </tr>
            @endif
          @endforeach
        </tbody>
      </table>

      <br><br>
      {{ $staffs->links('pagination::bootstrap-5') }}
    </div>
  </div>
@endsection



<!-- Modal Create -->
<div class="modal fade" id="smallModal" tabindex="-1" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body" id="smallBody">
        
      </div>
    </div>
  </div>
</div>

@section('page-script')
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script type="module">
    $(function () {
      $('#tblGroup').dataTable({
        "paging" : false,
        "bInfo" : false
      });
    });

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

    $("#smallModal").on('hidden.bs.modal', function () {
      $(this).data('bs.modal', null);
    });
    $('#smallModal').on('hidden', function() {
      $(this).removeData('modal');
    });
  </script>

  <script>
    function modalType(params) {
      $('#modType').text(params)
    }
  </script>
@endsection