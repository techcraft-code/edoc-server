@extends('layouts.app')

@section('nav-top')
	@include('layouts.nav-top', ['active'=>2])
@endsection

@section('content')
<div class="container py-3">
		<div class="mb-3">
			<a href="/" class="btn btn-white text-secondary"><i class="fa fa-chevron-left"></i> ย้อนกลับ</a>
		</div>
		<label style="font-size: 22px; font-weight:bold; color:forestgreen">
				<img class="img-head"  src="{{ asset('image/create.png') }}" alt="" srcset="" width="30px" height="30px">&nbsp; 
					เพิ่มเอกสาร 
		</label>
	  {{-- <div class="alert alert-danger alert-dismissible" role="alert">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Danger!</strong> You should <a href="#" class="alert-link">read this message</a>.
			<ul>
			
				<li> list </li>
			</ul>
	
		</div> --}}
	@isset($errors)
		@include('errors.validate', $errors->all())
			
	@endisset

	<form id="createForm" class="" action="{{ route('document.store') }}" method="POST" enctype="multipart/form-data">
		@csrf
  <div class="row">
	<div class="col-md-7 mb-2">
		<h3 class="mb-3">
		รายละเอียดเอกสาร
		</h3>
		<div class="card border-top-primary ">
	
		  <div class="card-body">
			  	
			  <div class="form-row">
					
					<div class="form-group col">
						<label for="">เรื่อง <span class="red-star"></span></label>
						<input value="{{ old('title') }}" type="text" name="title" required class="form-control">
					</div>
	
				</div>
				<div class="form-row">
					<div class="form-group col">
							<label for="">เอกสารอ้างอิง</label>
							<div class="input-search-group" id="refer">
								<div class="input-group">
									<input class="form-control" type="text" placeholder="ค้นหาเอกสารอ้างอิง">
									<div class="input-group-append">
										<span class="input-group-text">
											<i class="fa fa-search"></i>
										</span>
									</div>
								</div>
								<div class="results">
	
								</div>
							</div>
							<div id="taged">
	
							</div>
	
						</div>
					<div class="form-group col">
						<label for="">ประเภทเอกสาร<span class="red-star"></span></label>
						<select class="form-control" name="type_id" required>
								<option value="">เลือกประเภทเอกสาร</option> 

								@foreach (App\Models\DocumentType::all() as $item)
										<option value="{{$item->id}}"
												@if (old('type_id') == $item->id)
													selected
												@endif
											>{{$item->name}}</option>
								@endforeach
						</select>
					</div>
				</div>
			  <div class="form-row">
					<div class="form-group col">
						<label for="">ตู้เอกสารต้นทาง<span class="red-star"></span></label>
							<select id="cabinetSelect" class="form-control" name="cabinet_id" >
								<option value="">เลือกตู้เอกสารต้นทาง</option> 
								
								@foreach ( $user->cabinetPermissions as $cabinet) 
										<option value="{{$cabinet->id}}"
												@if (old('cabinet_id') == $cabinet->id)
														selected
												@endif
											>{{$cabinet->name}}</option>
								@endforeach
						</select>
					</div>
					<div class="form-group col">
						<label for="">เลขที่เอกสาร<span class="red-star"></span></label>
						<input type="text" name="code" class="form-control" value="{{old('code')}}">
					</div>
					<div class="form-group col">
						<label for="">ลงวันที่<span class="red-star"></span></label>
						<div class="input-group ">
							<div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1">
									<i class="fa fa-calendar"></i>
								</span>
							</div>
							<input value="{{ old('date')}}" type="text" name="date" class="form-control date-select" placeholder="" autocomplete="off" aria-label="Example text with button addon" aria-describedby="button-addon1" required>
						</div>
					</div>

			  </div>
			  <div class="form-row">
					<div class="form-group col">
						<label for="">เลขแฟ้มต้นทาง<span class="red-star"></span></label>
						<select id="folderSelect" class="form-control" name="folder_id" disabled required>
							<option value="">เลือกแฟ้มเก็บเอกสาร</option> 
						</select>					
					</div>
					<div class="form-group col">
						<label for="">ตู้เอกสารปลายทาง<span class="red-star"></span></label>
						<select  class="form-control" name="send_to_cabinet_id" required>
							<option value="">เลือกตู้เอกสารปลายทาง</option> 
							@foreach ($user->getLocalCabinets()->get() as $item)
									<option value="{{$item->id}}">{{$item->name}}</option>
							@endforeach
						</select>
					</div>
				</div>
				
			  <!-- <div class="form-row">
					<div class="form-group col">
						<label for="">เลขที่รับ/ส่ง</label>
						
						<input type="text" name="receive_code" class="form-control">
					</div>
					<div class="form-group col">
						<label for="">วันที่รับ</label>
						<div class="input-group ">
							<div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1">
									<i class="fa fa-calendar"></i>
								</span>
							</div>
							<input value="{{ old('receive_date')}}" name="receive_date" type="text" class="form-control date-select" placeholder="" autocomplete="off" aria-label="Example text with button addon" aria-describedby="button-addon1">
						</div>
					</div>
					<div class="form-group col">
						{{-- <label for="">คำค้น<span class="red-star"></span></label> --}}
						<label for="">คำค้น</label>
						<input type="text" name="keywords" class="form-control">
					</div>
			  </div>      -->
	
		  </div>
	
		</div>

	</div>
	<div class="col-md-5 row">
		<div class="col-12">
			<h3 class="mb-3">ไฟล์แนบ</h3>
			<div class="row my-2 px-3" style="min-width: 320px">
				<a href="/editor" class="btn btn-white"><i class="fa fa-file"></i> สร้างใหม่</a>
				<a href="#" class="btn btn-white" id="openDocument"><i class="fa fa-folder-open"></i> เลือกเอกสารออนไลน์</a>
			</div>
			<div class="row mt-2 mb-3 card-body" id="attatch_document">
			</div>
			<input type="hidden" name="attatch_file_id" id="attatch_file_id">

			<div class="card border-top-primary">
				<div class="card-body" style="min-width: 320px">
					<div class="row mb-2">
						<div class="col-12">
						<div id="fileGroup">
							<div class="row mb-3" id="file1">
								<div class="col" >
									<input type="file" name="files[]">
									<button type="button" class="btn btn-danger btn-sm rounded-circle btn-remove-file" data-file="1">
										<i class="fa fa-times"></i>
									</button>
								</div>
							</div>
						</div>
						</div>   
			
					</div>
					<div class="row">
						<div class="col-12">
						<button id="addFile" type="button" class="btn btn-success  btn-sm">
							<i class="fa fa-plus"></i>
							<span>
								เพิ่มไฟล์
							</span>
						</button>
						</div>   
					</div>
				</div>
				</div>

		</div>
			<div class="col-12 mt-3" >
					<h3 class="mb-3">รายการเอกสารอ้างอิง</h3>
					<div class="card border-top-primary">
						<div class="card-body" style="min-width: 320px">
							<div class="row mb-2" id="referItem">
								@if(old('refers'))
									@foreach( old('refers') as $id )
										<div class="col-12 mb-1"><a href="#">{{ App\Models\Document::find($id)->title }}</a>
											<input type="hidden" name="refers[]" value="{{$id}}">
											<button type="button" class="btn btn-danger btn-sm rounded-circle float-right">
												<i class="fa fa-times"></i>
											</button>
										</div>
									@endforeach
								@endif
							</div>
						</div>
					</div>
				</div>
		</div>
	
  </div>

	@csrf
	<div class="text-center">
		<!-- <a class="btn btn-secondary mx-auto mt-3"  href="{{ url('/') }}">หน้าแรก</a> -->
		<button class="btn btn-outline-primary mx-auto mt-3" type="submit" name="save">
			<i class="fa fa-save"></i>
			บันทึก
		</button>
		<button id="sendBtn" class="btn btn-primary mx-auto mt-3" type="button" data-toggle="modal" data-target="#submitModal">
			<i class="fa fa-rocket"></i>
			ส่งเอกสาร
		</button>
	</div>

	<div id="submitModal" class="modal" role="dialog" >
			<div class="modal-dialog" role="document">
				{{-- <form id="approveForm" action="" method="post" > --}}
					<div class="modal-content border-top-primary">
						<div class="modal-header">
								<h5 class="modal-title">ส่งเอกสาร</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
								</button>
						</div>
						<div class="modal-body">
							<div class="form-group">
								<div class="row">
									<div class="col-8">
										<label for="">ถึง: </label>
										<select id="selectReceiver" class="form-control" @change="addUser(userModel)" v-model="userModel">
											<option v-for="user in users" :value="user.id">@{{user.full_name}}</option>
										</select>
									</div>
									<div class="col-4">
										<label for="selectAll">เลือกทั้งหมด</label>
										<input id="selectAll" class="form-control" type="checkbox" :checked="users.length == 0" v-model="isSelectAll">
									</div>
								</div>

							</div>
							<div class="form-group">

								<div class="row">
									<div class="col">
											<div id="tagged">
														<span div v-for="selected in selected_users" class="badge badge-info mr-1" > 
															<input type="hidden" name="send_to_users[]" :value="selected.id" >
																@{{selected.full_name}}
															<a class="rm-tag" href="#" v-on:click.prevent="removeSelectedUser(selected)" > <i class="fa fa-times"> </i></a>
														</span>
												</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-6">
										<div class="form-group">
												<label for="">ชื่อเอกสาร: </label>
												<input type="text" class="form-control" id="titleModal" disabled>
											</div>
								</div>
								<div class="col-6">
										<div class="form-group">
												<label for="">ประเภทเอกสาร: </label>
												<input type="text" class="form-control" id="documentTypeInputModal" disabled>
											</div>
								</div>
	
							</div>
							<div class="form-group">
									<label for="">การตอบกลับ: </label>
									{{-- <input type="text" class="form-control"> --}}
									<select name="reply_type_id" id="" class="form-control">
										@foreach (App\Models\DocumentReplyType::all() as $item)
											<option value="{{$item->id}}">{{$item->name}}</option>
										@endforeach
									</select>
							</div>
							<div id="approveUser" class="form-group">
								<label for="">ผู้อนุมัติเอกสาร</label>
								{{-- <input class="form-control" type="text" name="approve_user" id="" disabled> --}}
								<select name="approved_user_id" id="approve_user" class="form-control" disabled>
									<option value="null"></option>
	
									@foreach ($users as $user)
										<option value="{{$user->id}}">{{$user->full_name}}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group">
									<label for="">ความเห็นเพิ่มเติม: </label>
									<textarea class="form-control" name="comment" id="" cols="30" rows="5" placeholder="ใช้บันทึก เตือนความจำ หรืออธิบายเนื้อหาของเอกสารโดยย่อ"></textarea>
							</div>
							<input type="hidden" name="document_id">
						</div>
						<div class="modal-footer float-left">
							<button type="button" id="sendButton" class="btn btn-success" data-dismiss="modal" name="send">บันทึกและส่งทันที</button>
							<button type="button" class="btn btn-secondary text-left" data-dismiss="modal">ปิด</button>
						</div>
					</div>
			{{-- </form> --}}
		</div>
		
	</div>
	</form>
</div>
<div id="openDialog">
	<h3>
		<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 576 512"><!--! Font Awesome Pro 6.0.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M147.8 192H480V144C480 117.5 458.5 96 432 96h-160l-64-64h-160C21.49 32 0 53.49 0 80v328.4l90.54-181.1C101.4 205.6 123.4 192 147.8 192zM543.1 224H147.8C135.7 224 124.6 230.8 119.2 241.7L0 480h447.1c12.12 0 23.2-6.852 28.62-17.69l96-192C583.2 249 567.7 224 543.1 224z"/></svg>
		เอกสารของฉัน
	</h3>
	<a class="close_button"><i class="fa fa-times fa-2x"></i></a>
	<ul id="doclist">
	</ul>
</div>

@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.8/vue.min.js"></script>

<script src="{{asset('js/document/create.js')}}"></script>
{{-- <script src="{{asset('js/nameSearch.js')}}"></script> --}}
{{-- <script src="{{asset('auto-complete/js/bootstrap-typeahead.min.js')}}"></script> --}}
<script>
    document.getElementsByClassName("close_button")[0].addEventListener( "click", () => {
        document.getElementById('openDialog').style.display = 'none'
    } )
	document.getElementById("openDocument").addEventListener( "click", () => {
		document.getElementById('openDialog').style.display = 'block'
        fetch('/mydocument', {
            method: 'GET', // *GET, POST, PUT, DELETE, etc.
            mode: 'cors', // no-cors, *cors, same-origin
            cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
            credentials: 'same-origin', // include, *same-origin, omit
            redirect: 'follow', // manual, *follow, error
            referrerPolicy: 'no-referrer'
        })
        .then(res=>res.json())
        .then(data=>{
            var item
            document.getElementById('doclist').innerHTML = '<li></li>'
            
            data.forEach(file => {
                item = document.createElement('li')
                item.innerHTML = '<a onclick="addthis('+file.id+', \''+file.doc_title+'\')"><i class="fa fa-file"></i> '+file.doc_title+'</a>'
                document.getElementById('doclist').appendChild(item)
            })
        })
	} )
	function addthis(id, title) {
		var attachfile = document.getElementById('attatch_document')
		attachfile.innerHTML = '<a href="/editor?edit='+id+'" target="_blank" class="btn btn-link text-secondary"><i class="fa fa-file"></i> '+title+'</a><a onclick="removeAtt()"><i class="fa fa-times"></i></a>'
		document.getElementById('attatch_file_id').value = id
		document.getElementById('openDialog').style.display = 'none'
	}

	function removeAtt() {
		document.getElementById('attatch_file_id').value = ''
		document.getElementById('attatch_document').innerHTML = ''
	}

	vueContainer = new Vue({
		el:'#submitModal',
		data: {
			userModel: null,
			isSelectAll: false,
			users: {!! $users->toJson() !!},
			selected_users: []
		},
		watch: {
			isSelectAll(newValue) {

				if (newValue) {
					for(i=0; i<this.users.length;i++) {
						this.selected_users.push(this.users[i])
					}
					this.users.splice(0, this.users.length)
				} else {
					for(i=0; i<this.selected_users.length;i++) {
						this.users.push(this.selected_users[i])
					}
					this.selected_users.splice(0, this.selected_users.length)
				}
			}

		},
		mounted() {
			// console.log(this.users);
			
		},
		methods: {
			addUser: function(user) {
				this.userModel = null
				index = this.users.findIndex( (item) => {return item.id == user} )
				this.selected_users.push(this.users[index])
				this.users.splice(index, 1)
			},
			removeSelectedUser(user) {
				this.userModel = null
				index = this.selected_users.findIndex( (item) => {return item.id == user.id} )
				this.users.push(user)
				this.selected_users.splice(index, 1)
			},
			selectAll() {
				console.log(this.users.length);
				
				if (this.users.length) {
					for(i=0; i<this.users.length;i++) {
						this.selected_users.push(this.users)
					}
					this.users.splice(0, this.users.length)
				}

			}
		},
	})
	function getFolderurl(id) {
		host = "{{ url("") }}";
		uri = host+"/ajax/cabinets/"+id+"/folders" ;
		return uri ;
	}

	$('#sendButton').click(function(){
		form = $("#createForm");
		input = $(`<input name="submit_type" value="send" type="hidden">`);
		form.append(input);
		$('button[type="submit"]').trigger('click');
	});

	// $("#selectReceiver").change(function(e){
	// 	// console.log(e);
	// 	value = $(this).val();
	// 	text = $(this).find('option:selected').text();
	// 	if( $(`input[name="send_to_users[]"][value="${value}"]`).length == 0 ){
	// 		$link = $(`<a href="">${text}</a>`);
	// 		$deleteBtn = $(`<a class="rm-tag" href="#" data-refer="${value}" > <i class="fa fa-times"> </i></a>`) ;
	// 		$value = $(`<input type="hidden" name="send_to_users[]" value="${value}" >`);
	// 		$tag = $(`<span class="badge badge-info mr-1" > ${text}</span>`) ;
			
	// 		// $('input[name="send_to_users"]').val(text);
	// 		$tag.append($deleteBtn);
	// 		$tag.append($value);
	// 		$deleteBtn.click(function(e){
	// 			e.preventDefault();
	// 			$(this).parent().remove();
	// 		});
	// 		$("#tagged").append($tag);
	// 	} 
	// 	$(this).find('option:selected').prop('selected', false);

	// })

	$('select[name="reply_type_id"]').change(function(e){
		value = $(this).find("option:selected").val();

		if (value == 2) {
			$('select[name="approved_user_id"]').prop('disabled', false);
			$('select[name="approved_user_id"]').find('option[value="null"]').remove();

		} else{
			$('select[name="approved_user_id"]').prop('disabled', true);
			$('select[name="approved_user_id"]').prepend(`<option value="null"></option>`);
			$('select[name="approved_user_id"]').find('option:selected').prop('selected', false);

		}
	});

	$('select[name="type_id"]').change(function(){
		value = $(this).find("option:selected").text();
		console.log(value);
		$("#documentTypeInputModal").val(value)
	});
	$('input[name="title"]').change(function(){
		value = $(this).val();
		// console.log(value);
		$("#titleModal").val(value)
	});
	$("#cabinetSelect").change(function(){
		id = $(this).val();
		// console.log(typeof(id));
		$folderEle = $("#folderSelect") ;
		if(id !== ""){
			axios.get(getFolderurl(id))
			.then(function(res){
				$folderEle.prop("disabled", false);
				$($folderEle).empty();
				res.data.forEach(function(item) {
					$child = $(`<option value="${item.id}">${item.name}</option>`);
					$($folderEle).append($child);
				});
			})
			.catch(function(err){
			});
		} else {
			$folderEle.prop("disabled", true);
			$folderEle.val(null);
		}
	});

	$("#refer").search({
    el: "#refer",

		url: "{{ route("ajax.document_refer")}}",
		callback: function(value){
			// console.log(value);
			$("#titleModal").val(value);
		}
	})

	// $("#nameSearch").nameSearch({
  //   el: "#nameSearch",
	// 	url: "{{ route("ajax.search_user")}}"
	// });

</script>
@endsection

@push('css')
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
	{{-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous"> --}}

	<link rel="stylesheet" href="{{ asset("css/document/create.css") }}">
	{{-- <link rel="stylesheet" type="text/css" href="{{asset("semantic/dist/semantic.css")}}"> --}}

@endpush

@push('js')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/locales/bootstrap-datepicker.th.min.js"></script>
	{{-- <script src="{{asset("semantic/dist/semantic.min.js")}}"></script> --}}
@endpush