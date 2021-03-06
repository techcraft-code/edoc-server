@extends('layouts.app')
@section('nav-top')
  @include('layouts.admin.nav-top')
  {{-- {{ Breadcrumbs::render('school.index') }} --}}
  
@endsection
@section('content')
  <div class="container">
    <form action="{{ route('back-office.school.store')}}" method="post">
      @csrf
        <div class="row">
          <div class="col-md-7 offset-2">
              <h3 class="text-center">สร้างตู้เอกสารใหม่</h3>
            <div class="card border-top-primary">
              
              <div class="card-body">
                <div class="form-group row">
                  <div class="col-6">
                    <label for="">ชื่อตู้เอกสาร</label><span class="red-star"></span>
                    <input type="text" class="form-control" name="name" required>
                    <small class="text-danger">ระบุชื่อตู้เอกสาร</small>

                  </div>
                  <div class="col-6">
                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="">คำอธิบาย ตู้เอกสาร</label>
                  <textarea  id="" cols="30" rows="5" class="form-control" name="description"></textarea>
                </div>
                <div class="form-group row">
                    <div class="col-6">
                        <label for="">ชื่อแฟ้ม</label><span class="red-star"></span>
                        <input type="text" class="form-control" name="folder[name]" required>
                        <small class="text-danger">ระบุชื่อแฟ้ม</small>
                    </div>
                    <div class="col-6">
    
                    </div>
    
                </div>
                <div class="form-group">
                    <label for="">คำอธิบาย แฟ้มเอกสาร</label>
                  <textarea id="" cols="30" rows="5" class="form-control" name="folder[description]"></textarea>
    
                  </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-7 offset-2 ">

            <div class="button-group text-center">
              <a class="btn edoc-btn-primary inverse">ย้อนกลับ</a>
              <button class="btn edoc-btn-primary">สร้าง</button>
  
            </div>
          </div>
        </div>
    </form>

  </div>
@include('alert.alert')

@endsection