@extends('layouts/contentNavbarLayout')

@section('title', 'Buku - Pengajuan Peminjaman')

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
        <span class="text-muted fw-light">Aplikasi / Buku /</span> Daftar Pengajuan Peminjaman
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
        <h5 class="card-header">Daftar Pengajuan Peminjaman</h5>
        
        @can('pengajuan-pinjaman')
            <div class="text-end">
                <button class="btn btn-success btn-crud me-4 mb-3" data-bs-toggle="modal" data-bs-target="#smallModal" data-attr="{{ route('submissions.create') }}" onclick="modalType('Create')">
                    Tambah Pengajuan
                </button>
            </div>
        @endcan

        <div class="table-responsive p-4">
            <table class="table table-striped stripe table-borderless" id="tblSubmission" style="border:0">
                <thead class="border-bottom">
                    <tr>
                        <th>Buku</th>
                        <th>Tanggal Kembali</th>
                        <th>Jumlah</th>
                        <th>Approved</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($submissions as $data)
                        <tr>
                            <td>{{$data->book->name}}</td>
                            <td>{{$data->return_date}}</td>
                            <td>{{$data->number_stock}}</td>
                            <td class="@if($data->approved) text-primary @elseif(is_null($data->approved)) @else text-danger @endif fw-bold">
                                @if($data->approved)
                                    DITERIMA
                                @elseif(is_null($data->approved))
                                    -
                                @else
                                    DITOLAK
                                @endif
                            </td>
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