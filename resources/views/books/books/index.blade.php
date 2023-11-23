@extends('layouts/contentNavbarLayout')

@section('title', 'Buku - Daftar Buku')

@section('page-style')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />

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
    <span class="text-muted fw-light">Aplikasi / Buku /</span> Daftar Buku
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
    <h5 class="card-header">Daftar Buku</h5>
    
    @can('buku-create')
      <div class="text-end">
        <button class="btn btn-success btn-crud me-4 mb-3" data-bs-toggle="modal" data-bs-target="#smallModal" data-attr="{{ route('books.create') }}" onclick="modalType('Create')">
          Buat Buku
        </button>
      </div>
    @endcan
				
    <div class="table-responsive p-4">
      <table class="table table-striped stripe table-borderless" id="tblBook" style="border:0">
        <thead class="border-bottom">
          <tr>
            <th>Nama</th>
            <th>Penulis</th>
            <th>Tahun Rilis</th>
            <th>Stock</th>
            @if(Auth::user()->can('buku-edit') || Auth::user()->can('buku-delete'))
              <th>Aksi</th>
            @endif
          </tr>
        </thead>
        <tbody>
          @foreach ($books as $data)
            <tr>
              <td>{{$data->name}}</td>
              <td>{{$data->author}}</td>
              <td>{{$data->publish_year}}</td>
              <td>{{$data->stock}}</td>
              @if(Auth::user()->can('buku-edit') || Auth::user()->can('buku-delete'))
                <td style="width:200px">
                  <div class="dropdown">
                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                    <div class="dropdown-menu">
                      @can('buku-edit')
                        <button class="dropdown-item btn-crud" data-bs-toggle="modal" data-bs-target="#smallModal" data-attr="{{ route('books.edit', $data->id) }}" onclick="modalType('Edit')">
                          <i class="bx bx-edit-alt me-1"></i> Edit
                        </button>
                      @endcan
                      @can('buku-delete')
                        <form action="{{ route('books.destroy',$data->id) }}" method="POST">								
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
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <div class="modal fade" id="smallModal" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
      <div class="modal-content p-3 p-md-5">
        <div class="modal-body" id="smallBody">
          
        </div>
      </div>
    </div>
  </div>
@endsection

@section('page-script')
  <script type="module">
    $(function () {
      $('#tblBook').dataTable({
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
@endsection