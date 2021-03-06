@extends('layouts.app')

@section('nav-top')
    @include('layouts.nav-top',['active'=>4])
@endsection

@section('content')
  <div class="container text-center ">
    <div class="mb-3">
      <div class="col-12 ">
        <!-- <div class="row "> -->
        <a href="" class="btn btn-primary1 " data-toggle="modal" data-target="#createModal">
        <img class="" src="{{ asset("image/officer-add.png")}}" alt="" srcset="" > &nbsp;
          เพิ่มรายชื่อ
        </a>
        <a id="importFile" href="{{route('officer.create' )}}" class="btn btn-primary2 ">
        <img class="" src="{{ asset("image/officer-file.png")}}" alt="" srcset="" > &nbsp;
          นำเข้ารายชื่อจากไฟล์
        </a>
        <a id="importFile" href="{{ url('/officer/download/template')}}" class="btn btn-primary3 ">
        <img class="" src="{{ asset("image/officer-example.png")}}" alt="" srcset="" > &nbsp;
          ไฟล์ตัวอย่าง
        </a>
        <form action="{{ route("officer.import")}}" method="post" enctype="multipart/form-data">
          @csrf
          <input type="file" name="import_file" accept=".csv" id="" style="display:none"/>
        </form>
      <!-- </div> -->
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body p-0 border-top-primary">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th class="">ID</th>
                  <th class="w-50">ชื่อ นามสกุล</th>
                  <th class="w-25">ตำแหน่ง</th>
                  <th class="w-25">การจัดการ</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($officers as $officer)
                <tr class="">
                  <td class="">
                    {{ $officer->user_id}}
                  </td>
                  <td class="">
                    {{ $officer->full_name}}
                  </td>
                  <td class="">
                      {{ __('role.'.$officer->role->name)}}
                  </td>
                  <td class="">
                      @if ($officer->role_id != 1)
                      <form method="POST" action="{{ route('officer.destroy', $officer->id)}}"  class="form-delete" style="display: inline-block;">
                        @method("DELETE")
                        @csrf
                        <button class="btn btn-danger" >
                          ลบ
                        </button>
                      </form>
                      <form method="POST" action="{{ route('password_reset') }}"  class="form-password-reset" style="display: inline-block;">
                        @method("POST")
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $officer->user_id}}">
                        <button class="btn btn-warning" >
                          รีเซ็ตรหัสผ่าน
                        </button>
                      </form>
                      @endif
                  </td>
                      

                </tr>
                    
                @endforeach
              </tbody>
            </table>
          </div>
    
        </div>

      </div>
    </div>
  </div>

  <div class="modal" id="createModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h3>

            สร้างชื่อผู้ใช้ใหม่
          </h3>
        </div>
        <div class="modal-body">
          <form action="{{ route("officer.store") }}" method="post">
            @csrf
            <div class="form-group">
              <label for="">เลขบัตรประชาชน</label>
              <input class="form-control" type="text" name="user_id" required>
            </div>

            <div class="form-group">
              <label for="">ชื่อ</label>
              <input class="form-control" type="text" name="first_name" required>
            </div>
          
            <div class="form-group">
              <label for="">นามสกุล</label>
              <input class="form-control" type="text" name="last_name" required>
            </div>
            <input type="hidden" name="school_id" value="{{ Auth::user()->school_id }}">
          
            <div class="form-group">
              <label for="">อีเมล</label>
              <input class="form-control" type="text" name="email" required>
            </div>

            <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
            <button class="btn btn-primary" type="submit">บันทึก</button>
          
          </form>
        </div>
      </div>
    </div>
  </div>  
  @include('alert.alert')
@endsection 

@section("script")
<script>

$('.form-delete').on('submit', function(e){
if(confirm("คุณต้องการจะลบข้อมูลอย่างถาวรใช่ไหม ?")) {
  return true;
} else {
  e.preventDefault()
}
})

$("#importFile").click(function(e){
  e.preventDefault();
  $('input[name="import_file"]').trigger("click");
});

$('input[name="import_file"]').on("change", function(){
  $(this).parent().submit();
});
</script>
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/2.7.94/css/materialdesignicons.css">
    <link rel="stylesheet" href="{{ asset("css/document/index.css") }}">

@endpush