@extends('layouts.app')



@section('content')
<div class="container">
	<div class="row">
		<div class="col">
			<h2>
				{{$document->title}}
				<span class="badge badge-secondary" style="font-size: 0.5em">{{$document->type->name}}</span>
			</h2>

		</div>
	</div>
	<div class="row mt-3">
		<div class="col">
			<span class="font-weight-bold mr-2">
				เอกสารจาก: 
			</span>
			{{ $document->sendFormCabinet->name }}
			@if($document->replyType )
				|
				<span class="font-weight-bold ml-2">
					รูปแบบการตอบกลับ: 
				</span>
				{{ $document->replyType->name }}
			@endif

		</div>

	</div>

	<div class="row mt-3">
		<div class="col-2">
			<span class="span font-weight-bold">
				ผู้สร้างเอกสาร:
			</span>
		</div>
		<div class="col">
			{{ $document->creator->full_name}}
		</div>
	</div>

	<div class="row mt-3">
		<div class="col-2">
			<span class="span font-weight-bold">
				สถานะของเอกสาร:
			</span>
		</div>
		<div class="col">
			{!! $document->render_status !!}
			{{ $document->status_text }}
		</div>
	</div>
	
	<div class="row mt-3">
		<div class="col-2">
			<span class="font-weight-bold">
				ผู้รับ: 
			</span>
		</div>
		<div class="col">
			@foreach ($document->accessibleUsers as $user)
				@if( $user->id == $document->user_id)

				@elseif ($user->pivot->is_read)
					<span class="badge badge-success mt-1" style="font-size: 0.9em; padding: .5em .6em;"> {{ $user->full_name }}</span>

				@else
					<span class="badge badge-secondary mt-1" style="font-size: 0.9em; padding: .5em .6em;"> {{ $user->full_name }}</span>
				@endif
			@endforeach
		</div>
	</div>
	<div class="row mt-3">
		<div class="col-2">
			<span class="font-weight-bold">
				เอกสารอ้างอิง: 
			</span>
		</div>
		<div class="col">
			@foreach ($document->references as $ref)
				{{-- <input> --}}
				<div style="display: block" >
					<a  href="{{ route('document.show', $ref->id)}}"> {{ $ref->title }}</a>
				</div>
			@endforeach
		</div>
	</div>

	<div class="row  mt-3">
		<div class="col-2">
			<span class="font-weight-bold">
				ไฟล์แนบ :
			</span>
		</div>
		<div class="col">
			@foreach ($document->attachments as $file)
			<div style="display: block" >
				<a href="{{ route('attachment.download', ["document", $file->id])}}"> {{ $file->name }}</a>
			</div>
			@endforeach
		</div>
	</div>
	{{-- {{ dd($user->id)}} --}}
	
	<div class="row mt-3">
		<div class="col-12">
			<span class="font-weight-bold">
				ความคิดเห็น
			</span>
		</div>
		
		@foreach ($document->comments()->orderBy('created_at', "desc")->get() as $comment)
		<div class="col-12 mb-3">
			<div class="card  bg-light">
				<div class="card-body">
					<p class="card-text">
						{{$comment->comment}}
					</p>
					@if ( $comment->attachments->count() > 0 )
						<p class="card-text font-weight-bold">
							ไฟล์แนบ 
						</p>
						@foreach ($comment->attachments as $file)
						<div style="display: block" >
							<a  href="{{ route('attachment.download', ["comment", $file->id])}}"> {{ $file->name }}</a>
						</div>
						@endforeach
					@endif
					
				</div>
				<div class="card-footer">
					<span>{{$comment->author->full_name}}</span>
					 | 
					 <span>{{ $comment->created_thai_format}}</span>
				</div>
			</div>
		</div>
		@endforeach
	</div>

	{{-- @if ( !is_null($pivot) && $pivot->document_user_status == 1 && $document->status  == 2)
	<div class="row mt-3">
		<div class="col">
			<h2>การตอบกลับ</h2>
		</div>
	</div>
	<div style="display:block" class="row">
		<span>
		</span>
		<div class="card">
			<form action="{{ route('document.comment', $document->id) }}" method="post" enctype="multipart/form-data"> 
				@csrf
				<div class="card-body">
					<div class="from-group mb-2">
						<label for="">ผู้รับ:</label>
						<select class="form-control" name="receivers" id="">
							<option value="">ไม่มีผู้รับ</option>

							@foreach ( $users_in_school as $user_school)
								@if( auth()->user()->id != $user_school->id && $user_school->role_id != 1)
									<option value="{{ $user_school->id}}">{{$user_school->full_name}}</option>
								@endif
							@endforeach
						</select>
					</div>
					<div class="from-group">
						<label for="">ข้อความ:</label>
						<textarea name="comment" cols="30" rows="5" class="form-control"></textarea>
					</div>
					<div class="row mt-3">
						<div class="col-12">
							<label for="">ไฟล์แนบ:</label>
							<input type="file" name="files[]">
						</div>
						<div class="col-12"></div>
					</div>
					<div class="mt-3">
						<button class="btn btn-primary" style="padding-left:1em;padding-right:1em;">ส่ง</button>
					</div>
				</div>
			</form>
		</div>
	</div>
			
	@endif --}}

</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">ยืนยันการอนุมัติ</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form id="approveForm" action="{{ route('document.respond', $document->id) }}" method="post">
					@csrf
					@method("PUT")
					<div class="modal-body">
							<div class="form-group">
								<label for="recipient-name" class="col-form-label">ความคิดเห็น</label>
								<textarea class="form-control" id="recipient-name" name="comment"></textarea>
							</div>
					</div>
					<input type="hidden" name="is_approve">
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
						<button type="submit" class="btn btn-primary">ยืนยัน</button>
					</div>
				</form>
			</div>
		</div>
	</div>

@endsection

@section('script')
<script src="{{asset('js/document/show.js')}}"></script>
<script src="{{asset('auto-complete/js/bootstrap-typeahead.min.js')}}"></script>
<script>
	$("button.approve").click(function(e){
		is_approve = $(this).val();
		$("#approveForm").find("input[name='is_approve']").val(is_approve);
	});



</script>
@endsection

@push('css')
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
	<link rel="stylesheet" href="{{ asset("css/document/create.css") }}">
	{{-- <link rel="stylesheet" type="text/css" href="{{asset("semantic/dist/semantic.css")}}"> --}}

@endpush

@push('js')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/locales/bootstrap-datepicker.th.min.js"></script>
	{{-- <script src="{{asset("semantic/dist/semantic.min.js")}}"></script> --}}
@endpush