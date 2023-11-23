@extends('layouts/contentNavbarLayout')

@section('title', 'Buku - Peminjaman')

@section('page-style')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <style type="text/css">
    .pagination li{
      float: left;
      list-style-type: none;
      margin:5px;
    }

    .select2-container--open {
        z-index: 9999999
    }
  </style>
@endsection

@section('content')
    <h4 class="fw-bold py-3 mb-2">
        <span class="text-muted fw-light">Aplikasi / Buku /</span> Daftar Peminjaman
    </h4>

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
        <h5 class="card-header">Daftar Peminjaman</h5>

        <div class="table-responsive p-4">
            <table class="table table-striped stripe table-borderless" id="tblSubmission" style="border:0">
                <thead class="border-bottom">
                    <tr>
                        <th>User</th>
                        <th>Buku</th>
                        <th>Jumlah</th>
                        <th>Tanggal Meminjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Dikembalikan</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($submissions as $data)
                        <tr>
                            <td>{{$data->user->name}}</td>
                            <td>{{$data->book->name}}</td>
                            <td>{{$data->number_stock}}</td>
                            <td>{{$data->created_at}}</td>
                            <td>{{$data->return_date}}</td>
                            <td class="@if($data->return) text-primary @else text-danger @endif fw-bold">
                                @if($data->return)
                                    DIKEMBALIKAN
                                @else
                                    BELUM DIKEMBALIKAN
                                @endif
                            </td>
                            @if(Auth::user()->can('pengajuan-edit') || Auth::user()->can('pengajuan-delete'))
                                <td style="width:200px">
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                    <div class="dropdown-menu">
                                    @can('pengajuan-edit')
                                        <form method="POST" action="{{ route('submissions.update', $data->id) }}" enctype="multipart/form-data">
                                            @csrf
                                            @method("PUT")
                                            <input type="hidden" name="return" value="kembali">
                                            <button type="submit" class="dropdown-item"><i class="bx bx-check me-1"></i> Dikembalikan</button>
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

    <!-- Modal Create -->
    <div class="modal fade" id="smallModal" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body" id="smallBody">
                    
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="module">
        $(function () {
            $('#tblSubmission').dataTable({
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
        async function downloadImage(path, fileName) {
            const response = await fetch(path);

            const blobImage = await response.blob();

            const href = URL.createObjectURL(blobImage);

            const anchorElement = document.createElement('a');
            anchorElement.href = href;
            anchorElement.download = fileName;

            document.body.appendChild(anchorElement);
            anchorElement.click();

            document.body.removeChild(anchorElement);
            window.URL.revokeObjectURL(href);
        }
    </script>
@endsection