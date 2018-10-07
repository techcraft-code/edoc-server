@extends('layouts.app')

@section('nav-top')
	@include('layouts.nav-top', ['active'=>1])
@endsection

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
			<span class="font-weight-bold">
				เอกสารจาก: 
			</span>
			{{ $document->sendFormCabinet->name }}
			@if($document->replyType )
				|
				<span class="font-weight-bold">
					รูปแบบการตอบกลับ: 
				</span>
				{{ $document->replyType->name }}
			@endif

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
				@if ($user->pivot->is_read)
					<span class="badge badge-success" style="font-size: 0.9em; padding: .5em .6em;"> {{ $user->full_name }}</span>
				@else
					<span class="badge badge-secondary" style="font-size: 0.9em; padding: .5em .6em;"> {{ $user->full_name }}</span>
				@endif
			@endforeach
				{{-- <span class="badge badge-success" style="font-size: 0.9em; padding: .5em .6em;">สมชาย ใจดำดี</span>
				<span class="badge badge-success" style="font-size: 0.9em; padding: .5em .6em;">สมชาย ใจดำดี</span>
				<span class="badge badge-success" style="font-size: 0.9em; padding: .5em .6em;">สมชาย ใจดำดี</span>
				<span class="badge badge-success" style="font-size: 0.9em; padding: .5em .6em;">สมชาย ใจดำดี</span>
				<span class="badge badge-success" style="font-size: 0.9em; padding: .5em .6em;">สมชาย ใจดำดี</span> --}}

		</div>
	</div>
	<div class="row mt-3">
		<div class="col-2">
			<span class="font-weight-bold">
				เอกสารอ้างอิง: 
			</span>
		</div>
		<div class="col-2">
			@foreach ($document->references as $ref)
				{{-- <input> --}}
				<a  style="display: block" href="{{ route('document.show', $ref->id)}}"> {{ $ref->title }}</a>
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
			{{-- <input> --}}
				<a  style="display: block" href="{{ route('attachment.download', $file->id)}}"> {{ $file->name }}</a>
			@endforeach
		</div>
	</div>

	@if( !is_null($pivot) )
		@if( $document->reply_type == 1 && $user->accessibleDocuments()->wherePivot('is_read', '!=', 0)->count())
		<div class="row mt-3">
			<div class="col-2">
				<span class="font-weight-bold">
					การดำเนินการ:
				</span>
			</div>
			<div class="col">
			<form action="{{ route('document.respond', $document->id) }}" method="post">
				@csrf
				@method("PUT")
				<button class="btn btn-success" >
					รับทราบ
				</button>
			</form>

			</div>
		</div>
	@elseif( $document->reply_type == 2 && $document->approved_user_id == $user->id && $document->status == 2 )
		<div class="row mt-3">
			<div class="col-2">
				<span class="font-weight-bold">
					การดำเนินการ:
				</span>
			</div>
			<div class="col">
				<form class="d-inline-block" action="{{ route('document.respond', $document->id) }}" method="post">
						@csrf
						@method("PUT")
					<button class="btn btn-success" name="is_approve" value="1">
					อนุมัติ
					</button>
				</form>
				<form class="d-inline-block" action="{{ route('document.respond', $document->id) }}" method="post">
					@csrf
					@method("PUT")
					<button class="btn btn-danger" name="is_approve" value="0">
						ไม่อนุมัติ
					</button>
				</form>
			</div>
		</div>
		@endif
	@endif
	



	<div class="row mt-3">
		<div class="col-12">
			<span class="font-weight-bold">
				ความคิดเห็น
			</span>
		</div>
		
		@foreach ($document->comments as $comment)
		<div class="col-12 mb-3">
			<div class="card  bg-light">
				<div class="card-body">
					<p class="card-text">
						{{$comment->comment}}
					</p>
					<p class="card-text font-weight-bold">
						ไฟล์แนบ 
					</p>
					@foreach ($document->attachments as $file)
					{{-- <input> --}}
					<div style="display: block" >
						<a  href="{{ route('attachment.download', $file->id)}}"> {{ $file->name }}</a>
					</div>
					@endforeach
				</div>
				<div class="card-footer">
					{{$comment->author->full_name}} | {{ $comment->created_thai_format}}
				</div>
			</div>
		</div>
		@endforeach
	</div>

	@if ( !is_null($pivot) && $pivot->document_user_status == 1 && $document->status  == 2)
	<div class="row mt-3">
		<div class="col">
			<h2>การตอบกลับ</h2>
		</div>
	</div>
	<div style="display:block" class="row">
		<span>
		</span>
		<div class="card">
			<form action="{{ route('document.comment', $document->id) }}" method="post"> 
				@csrf
				<div class="card-body">
					<div class="from-group mb-2">
						<label for="">ผู้รับ:</label>
						{{-- <input type="text" class="form-control" name="user"> --}}
						<select class="form-control" name="receivers" id="">
							@foreach ( $users_in_school as $user_school)
								@if( auth()->user()->id != $user_school->id )
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
			
	@endif
</div>

@endsection

@section('script')
<script src="{{asset('js/document/show.js')}}"></script>
<script src="{{asset('auto-complete/js/bootstrap-typeahead.min.js')}}"></script>
<script>



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