<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
<div class="text-center mb-4">
    <h3 class="role-title">Edit Hak Akses</h3>
    <p>Atur izin hak Akses</p>
</div>
<!-- Add role form -->
<form class="row g-3 fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate" method="POST" action="{{ route('access-rights.update', $role->id) }}">
    @csrf
    @method('PUT')
    <div class="col-12 mb-4 fv-plugins-icon-container">
        <label class="form-label" for="name">Nama Hak Akses</label>
        <input type="text" id="name" name="name" value="{{$role->name}}" :value="old('name')" required autofocus autocomplete="name" class="form-control" placeholder="Enter a role name" tabindex="-1">
    <div class="fv-plugins-message-container invalid-feedback"></div></div>
    <div class="col-12">
        <h4>Izin Hak Akses</h4>
        <!-- Permission table -->
        <div class="table-responsive">
            <table class="table table-flush-spacing">
                <tbody>
                    <tr>
                        <td class="text-nowrap fw-semibold">Administrator Access <i class="bx bx-info-circle bx-xs" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Allows a full access to the system" data-bs-original-title="Allows a full access to the system"></i></td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                                <label class="form-check-label" for="selectAll">
                                    Select All
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-nowrap fw-semibold">Dashboard</td>
                        <td>
                            <div class="d-flex">
                                @foreach(Spatie\Permission\Models\Permission::where('name','like','%dashboard-%')->get() as $value)
                                    <div class="form-check me-3 me-lg-5">
                                        <input name="permission[]" class="form-check-input" type="checkbox" value="{{$value->id}}" id="dashboard{{ ucfirst(explode('-', $value->name)[1]) }}" @if(in_array($value->id, DB::table("role_has_permissions")->where("role_has_permissions.role_id",$role->id)->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')->all())) checked @endif>
                                        <label class="form-check-label" for="dashboard{{ ucfirst(explode('-', $value->name)[1]) }}">
                                            {{ ucfirst(explode("-", $value->name)[1]) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    <tr>
                    <td class="text-nowrap fw-semibold">Laporan Rutin</td>
                        <td>
                            <div class="d-flex">
                                @foreach(Spatie\Permission\Models\Permission::where('name','like','%laporan-rutin-%')->get() as $value)
                                    <div class="form-check me-3 me-lg-5">
                                        <input name="permission[]" class="form-check-input" type="checkbox" value="{{$value->id}}" id="laporanRutin{{ ucfirst(explode('-', $value->name)[2]) }}" @if(in_array($value->id, DB::table("role_has_permissions")->where("role_has_permissions.role_id",$role->id)->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')->all())) checked @endif>
                                        <label class="form-check-label" for="laporanRutin{{ ucfirst(explode('-', $value->name)[2]) }}">
                                            {{ ucfirst(explode("-", $value->name)[2]) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-nowrap fw-semibold">Laporan Grouncheck Hotspot</td>
                        <td>
                            <div class="d-flex">
                                @foreach(Spatie\Permission\Models\Permission::where('name','like','%laporan-grouncheck-hotspot-%')->get() as $value)
                                    <div class="form-check me-3 me-lg-5">
                                        <input name="permission[]" class="form-check-input" type="checkbox" value="{{$value->id}}" id="laporanGrouncheckHotspot{{ ucfirst(explode('-', $value->name)[3]) }}" @if(in_array($value->id, DB::table("role_has_permissions")->where("role_has_permissions.role_id",$role->id)->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')->all())) checked @endif>
                                        <label class="form-check-label" for="laporanGrouncheckHotspot{{ ucfirst(explode('-', $value->name)[3]) }}">
                                            {{ ucfirst(explode("-", $value->name)[3]) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-nowrap fw-semibold">Laporan Info Kebakaran</td>
                        <td>
                            <div class="d-flex">
                                @foreach(Spatie\Permission\Models\Permission::where('name','like','%laporan-info-kebakaran-%')->get() as $value)
                                    <div class="form-check me-3 me-lg-5">
                                        <input name="permission[]" class="form-check-input" type="checkbox" value="{{$value->id}}" id="laporanInfoKebakaran{{ ucfirst(explode('-', $value->name)[3]) }}" @if(in_array($value->id, DB::table("role_has_permissions")->where("role_has_permissions.role_id",$role->id)->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')->all())) checked @endif>
                                        <label class="form-check-label" for="laporanInfoKebakaran{{ ucfirst(explode('-', $value->name)[3]) }}">
                                            {{ ucfirst(explode("-", $value->name)[3]) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-nowrap fw-semibold">Laporan Pemadaman</td>
                        <td>
                            <div class="d-flex">
                                @foreach(Spatie\Permission\Models\Permission::where('name','like','%laporan-pemadaman-%')->get() as $value)
                                    <div class="form-check me-3 me-lg-5">
                                        <input name="permission[]" class="form-check-input" type="checkbox" value="{{$value->id}}" id="laporanPemadaman{{ ucfirst(explode('-', $value->name)[2]) }}" @if(in_array($value->id, DB::table("role_has_permissions")->where("role_has_permissions.role_id",$role->id)->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')->all())) checked @endif>
                                        <label class="form-check-label" for="laporanPemadaman{{ ucfirst(explode('-', $value->name)[2]) }}">
                                            {{ ucfirst(explode("-", $value->name)[2]) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-nowrap fw-semibold">Laporan Grup</td>
                        <td>
                            <div class="d-flex">
                                @foreach(Spatie\Permission\Models\Permission::where('name','like','%history-pesan-%')->get() as $value)
                                    <div class="form-check me-3 me-lg-5">
                                        <input name="permission[]" class="form-check-input" type="checkbox" value="{{$value->id}}" id="historiPesan{{ ucfirst(explode('-', $value->name)[2]) }}" @if(in_array($value->id, DB::table("role_has_permissions")->where("role_has_permissions.role_id",$role->id)->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')->all())) checked @endif>
                                        <label class="form-check-label" for="historiPesan{{ ucfirst(explode('-', $value->name)[2]) }}">
                                            {{ ucfirst(explode("-", $value->name)[2]) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-nowrap fw-semibold">Kontak</td>
                        <td>
                            <div class="d-flex">
                                @foreach(Spatie\Permission\Models\Permission::where('name','like','%whatsapp-kontak-%')->get() as $value)
                                    <div class="form-check me-3 me-lg-5">
                                        <input name="permission[]" class="form-check-input" type="checkbox" value="{{$value->id}}" id="kontak{{ ucfirst(explode('-', $value->name)[2]) }}" @if(in_array($value->id, DB::table("role_has_permissions")->where("role_has_permissions.role_id",$role->id)->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')->all())) checked @endif>
                                        <label class="form-check-label" for="kontak{{ ucfirst(explode('-', $value->name)[2]) }}">
                                            {{ ucfirst(explode("-", $value->name)[2]) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-nowrap fw-semibold">Kirim Pesan</td>
                        <td>
                            <div class="d-flex">
                                @foreach(Spatie\Permission\Models\Permission::where('name','like','%whatsapp-kirim-pesan-%')->get() as $value)
                                    <div class="form-check me-3 me-lg-5">
                                        <input name="permission[]" class="form-check-input" type="checkbox" value="{{$value->id}}" id="kirimPesan{{ ucfirst(explode('-', $value->name)[3]) }}" @if(in_array($value->id, DB::table("role_has_permissions")->where("role_has_permissions.role_id",$role->id)->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')->all())) checked @endif>
                                        <label class="form-check-label" for="kirimPesan{{ ucfirst(explode('-', $value->name)[3]) }}">
                                            {{ ucfirst(explode("-", $value->name)[3]) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-nowrap fw-semibold">Provinsi</td>
                        <td>
                            <div class="d-flex">
                                @foreach(Spatie\Permission\Models\Permission::where('name','like','%data-master-provinsi-%')->get() as $value)
                                    <div class="form-check me-3 me-lg-5">
                                        <input name="permission[]" class="form-check-input" type="checkbox" value="{{$value->id}}" id="provinsi{{ ucfirst(explode('-', $value->name)[3]) }}" @if(in_array($value->id, DB::table("role_has_permissions")->where("role_has_permissions.role_id",$role->id)->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')->all())) checked @endif>
                                        <label class="form-check-label" for="provinsi{{ ucfirst(explode('-', $value->name)[3]) }}">
                                            {{ ucfirst(explode("-", $value->name)[3]) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-nowrap fw-semibold">Daop</td>
                        <td>
                            <div class="d-flex">
                                @foreach(Spatie\Permission\Models\Permission::where('name','like','%data-master-daop-%')->get() as $value)
                                    <div class="form-check me-3 me-lg-5">
                                        <input name="permission[]" class="form-check-input" type="checkbox" value="{{$value->id}}" id="daop{{ ucfirst(explode('-', $value->name)[3]) }}" @if(in_array($value->id, DB::table("role_has_permissions")->where("role_has_permissions.role_id",$role->id)->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')->all())) checked @endif>
                                        <label class="form-check-label" for="daop{{ ucfirst(explode('-', $value->name)[3]) }}">
                                            {{ ucfirst(explode("-", $value->name)[3]) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-nowrap fw-semibold">Pondok Kerja</td>
                        <td>
                            <div class="d-flex">
                                @foreach(Spatie\Permission\Models\Permission::where('name','like','%data-master-pondok-kerja-%')->get() as $value)
                                    <div class="form-check me-3 me-lg-5">
                                        <input name="permission[]" class="form-check-input" type="checkbox" value="{{$value->id}}" id="pondokKerja{{ ucfirst(explode('-', $value->name)[4]) }}" @if(in_array($value->id, DB::table("role_has_permissions")->where("role_has_permissions.role_id",$role->id)->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')->all())) checked @endif>
                                        <label class="form-check-label" for="pondokKerja{{ ucfirst(explode('-', $value->name)[4]) }}">
                                            {{ ucfirst(explode("-", $value->name)[4]) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-nowrap fw-semibold">Manajemen Role</td>
                        <td>
                        <div class="d-flex">
                            @foreach(Spatie\Permission\Models\Permission::where('name','like','%role-%')->get() as $value)
                            <div class="form-check me-3 me-lg-5">
                                <input name="permission[]" class="form-check-input" type="checkbox" value="{{$value->id}}" id="roleManagement{{ ucfirst(explode('-', $value->name)[1]) }}" @if(in_array($value->id, DB::table("role_has_permissions")->where("role_has_permissions.role_id",$role->id)->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')->all())) checked @endif>
                                <label class="form-check-label" for="roleManagement{{ ucfirst(explode('-', $value->name)[1]) }}">
                                {{ ucfirst(explode("-", $value->name)[1]) }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-nowrap fw-semibold">Manajemen Staff</td>
                        <td>
                        <div class="d-flex">
                            @foreach(Spatie\Permission\Models\Permission::where('name','like','%staff-%')->get() as $value)
                            <div class="form-check me-3 me-lg-5">
                                <input name="permission[]" class="form-check-input" type="checkbox" value="{{$value->id}}" id="staffManagement{{ ucfirst(explode('-', $value->name)[1]) }}" @if(in_array($value->id, DB::table("role_has_permissions")->where("role_has_permissions.role_id",$role->id)->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')->all())) checked @endif>
                                <label class="form-check-label" for="staffManagement{{ ucfirst(explode('-', $value->name)[1]) }}">
                                {{ ucfirst(explode("-", $value->name)[1]) }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-nowrap fw-semibold">QR Scan</td>
                        <td>
                            <div class="d-flex">
                                @foreach(Spatie\Permission\Models\Permission::where('name','like','%qrcode%')->get() as $value)
                                    <div class="form-check me-3 me-lg-5">
                                        <input name="permission[]" class="form-check-input" type="checkbox" value="{{$value->id}}" id="logoSetting{{ ucfirst(explode('-', $value->name)[1]) }}" @if(in_array($value->id, DB::table("role_has_permissions")->where("role_has_permissions.role_id",$role->id)->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')->all())) checked @endif>
                                        <label class="form-check-label" for="logoSetting{{ ucfirst(explode('-', $value->name)[1]) }}">
                                            Scan QR Code
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-12 text-center">
        <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
    </div>
</form>

<script>
    $(document).ready(function() {
        // Event handler for "Select All" checkbox
        $("#selectAll").on("change", function() {
            // Get the current state of the "Select All" checkbox
            var isChecked = $(this).prop("checked");
            
            // Set all the permission checkboxes to the same state as "Select All" checkbox
            $("input[name='permission[]']").prop("checked", isChecked);
        });
    });
</script>