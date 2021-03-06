@extends('layouts.app')

@section('nav-top')
    @include('layouts.nav-top')
@endsection

@section('content')

<div class="container">
<div class="col-12">
  <div  class="row table-tab ">
  <a class="btn" href="{{ route('receive') }}" style="font-size: 22px; font-weight:bold; color:forestgreen">
		<img class="img-head"  src="{{ asset("image/create.png")}}" alt="" srcset="" width="40px" height="40px" >&nbsp; 
					ลงทะเบียนรับหนังสือ
    </a>
    <div class="col">
      <div class="item">
        <div class="input-icon my-1 text-center"  >
          <input id="searchInput" style="width:250px" type="text" placeholder="ค้นหา" name="search[title]" @isset($old) value="{{ $old['title']}}" @endisset>
          <i class="fa fa-search"></i>
      </div>  
    </div>
  </div>
  <div class="my-2" style="padding-left:100px">
  <a id="pdf" class="btn btn-danger " href="{{ route('ExportPDF') }}" style="color: white" ><i class="fa fa-file"></i>&nbsp; ส่งออกไฟล์ PDF </a>
  </div>

  <div class="row">
    <div id="moreSearch" class="explain-search">
      <form id="searchForm" action="{{ route('received')}}" method="get">
      <input id="textSearch" type="hidden" name="search[title]" @isset($old) value="{{ $old['title']}}" @endisset>
      @isset($old['t'])  
        <input type="hidden" name="t" value="{{$old['t']}}">
      @else
        <input type="hidden" name="t" value="all">

      @endif
        <div class="body">
          <div class="row align-items-center mb-3 mt-3">
              <div class="col-2" >
                  <label for="">เลขที่เอกสาร</label>
              </div>
              <div class="col-4">
                  @if( isset($old['code']))
                  <input class="form-control" type="text" name="search[code]" >
                    @else
                  <input class="form-control" type="text" name="search[code]" >
                   @endif
              </div>

            <div class="col-2">
              <label for="">ชื่อเอกสาร</label>
            </div>
            <div class="col-4">
                @if( isset($old['title']))
                  <input class="form-control" type="text" name="search[title]" >
                @else
                  <input class="form-control" type="text" name="search[title]" >
                @endif
            </div></div>
            <div class="row align-items-center mb-3 mt-3">
            <div class="col-2">
              <label for="">วันที่เอกสาร</label>
            </div>
            <div class="col-4">
              {{-- <input class="form-control" type="text"> --}}
              <div class="input-group ">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">
                      <i class="fa fa-calendar"></i>
                    </span>
                  </div>
                  @if( isset($old['date_start']))
                    <input value="{{ $old['date_start'] }}"  type="text" name="search[date_start]" class="form-control date-select" placeholder="" autocomplete="off" aria-label="Example text with button addon" aria-describedby="button-addon1">
                  @else
                    <input  type="text" name="search[date_start]" class="form-control date-select" placeholder="" autocomplete="off" aria-label="Example text with button addon" aria-describedby="button-addon1">
                  @endif
                </div>
            </div>

            <div class="col-2">
              <label class="label" for="">วันที่รับเอกสาร</label>
            </div>
            <div class="col-4">
              {{-- <input type="text" class="form-control"> --}}
              <div class="input-group ">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">
                      <i class="fa fa-calendar"></i>
                    </span>
                  </div>
                  @if( isset($old['date_end']))
                    <input value="{{ $old['date_end'] }}"  type="text" name="search[date_end]" class="form-control date-select" placeholder="" autocomplete="off" aria-label="Example text with button addon" aria-describedby="button-addon1">

                  @else
                    <input  type="text" name="search[date_end]" class="form-control date-select" placeholder="" autocomplete="off" aria-label="Example text with button addon" aria-describedby="button-addon1">

                  @endif
                  </div>
            </div>
            
          </div>
          {{--  --}}
          <div class="row mb-3">
            <div class="col-2">
              <label class="label" for="">ตู้เอกสาร</label>
            </div>
            <div class="col-4">
              {{-- <input type="text" class="form-control"> --}}
              <select name="search[cabinet_id]" id="cabinetSelect" class="form-control">
                <option value="">เลือกตู้เอกสาร</option>
                @foreach ($cabinets as $item)
                  @if( isset($old['cabinet_id']) && $item->id == $old['cabinet_id'] )
                    <option value=" {{ $item->id }}" selected> {{ $item->name }}</option>

                  @else
                    <option value=" {{ $item->id }}"> {{ $item->name }}</option>

                  @endif
                @endforeach
              </select>
            </div>
            <div class="col-2">
              <label class="label" for="">แฟ้มเอกสาร</label>
            </div>
            <div class="col-4">
              <select name="search[folder_id]" id="folderSelect" class="form-control">
                <option value="">เลือกเแฟ้มเอกสาร</option>

                @foreach ($folders as $item)
                @if( isset($old['folder_id']) && $item->id == $old['folder_id'] )
                  <option value=" {{ $item->id }}" selected> {{ $item->name }}</option>

                @else
                  <option value=" {{ $item->id }}"> {{ $item->name }}</option>

                @endif
              @endforeach
              </select>
            </div>
          </form>
        </div>
          {{--  --}}
        <div class="row mb-3">
            <div class="col-2">
              <label for="">ประเภทเอกสาร</label>
            </div>
            <div class="col">
              @foreach ($document_types as $item)
                <div class="form-check form-check-inline">
                  @if( isset($old['document_types']) && in_array($item->id, $old['document_types']) )
                    <input class="form-check-input" name="search[document_types][]" type="checkbox" value="{{$item->id}}" id="docType{{$item->id}}" checked>
                  
                  @else
                    <input class="form-check-input" name="search[document_types][]" type="checkbox" value="{{$item->id}}" id="docType{{$item->id}}">
                  
                  @endif
                  <label class="form-check-label" for="docType{{$item->id}}">
                    {{-- Default checkbox --}}
                    {{ $item->name }}
                  </label>
                </div>
              @endforeach
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-2">
                <label for="">สถานะเอกสาร</label>
            </div>
            <div class="col">
              @foreach (App\Models\DocumentStatus::all() as $item)
                <div class="form-check form-check-inline" style="padding: 12px 0" >
                  @if( isset($old['statuses']) && in_array($item->id, $old['statuses']) )
                    <input class="form-check-input" type="checkbox" value="{{$item->id}}" name="search[statuses][]" id="docStatus{{$item->id}}" checked>
                  
                  @else
                    <input class="form-check-input" type="checkbox" value="{{$item->id}}" name="search[statuses][]" id="docStatus{{$item->id}}">
                  
                  @endif
                  <label class="form-check-label" for="docStatus{{$item->id}}">
                    {{-- Default checkbox --}}
                    <span class="status-circle status-{{$item->color}}"></span>
                    {{ $item->name }}
                  </label>
                </div>
              @endforeach
            </div>
          </div>
          <div class="row">
            <div class="col text-right">
            <!-- <button class="btn btn-danger " style="color: white" name="exportPDF" type="submit"><i class="fa fa-file"></i>&nbsp; ส่งออกไฟล์ PDF </button> -->
            <button class="btn btn-primary" >ค้นหา</button>
              {{-- <button id="closeMoreSearch" class="btn btn-secondary" type="button">ปิด</button> --}}
              
            <button id="closeMoreSearch" class="btn btn-secondary" type="button">ปิด</button>
              {{-- <button id="closeMoreSearch" class="btn btn-secondary" type="button">ปิด</button> --}}
            </div>
          </div>
        </div>
        </div>
      </div>
  <div class="row">
   
      <div class="card-body p-0 ">
        <table class="table">
          <thead>
              <tr class=" text-center">
                  <th class="color-secondary" width="50"></th>
                  <th class="color-secondary" width="100">เลขทะเบียนรับ</th>
                  <th class="color-secondary" width="140">วันที่รับ</th>
                  <th class="color-secondary" width="150">จาก</th>
                  <!-- <th class="color-secondary" width="140">วันที่เอกสาร</th> -->
                  <th class="color-secondary" width="350">เรื่อง</th>
                  <th class="color-secondary" width="150">ตู้เอกสาร</th>
                  <!-- <th class="color-secondary" width="100">เลขที่เอกสาร</th> -->
              </tr>
          </thead>
          <tbody>
            @foreach ($documents as $document)
                <tr data-id="{{ $document->id }}" data-user-id="{{ $document->cabinet_id }}">
                  @if ($document->status == 1 && $user->id != $document->user_id) 
                    @continue
                  @endif
                  <!-- <td align="center" class="color-secondary"> -->
                    @if ( in_array($document->id, $access_document->toArray()))

                  @endif

                  <td><a class="btn btn-success" style="color: white">ลงรับแล้ว </td>
                  <td class=" text-center"> {{ $document->receive_code }} </td>  
                  <td class=" text-center"> {{ ($document->thai_date2) }} </td>
                  <td class=" text-center"> {{ $document->creator->full_name}} </td>
                  <td> <a href="{{ route("document.show", $document->id) }}"> {{ $document->title }}</a></td>
                  <td class=" text-center"> {{ $document->sendToCabinet->name or '' }} </td>
                  <!-- <td class=" text-center"> {{ $user->full_name }} </td> -->
                  <!-- <td class=" text-center"> {{ $document->thai_date }} </td> -->
                  <!-- <td class=" text-center"> {{ $document->code }} </td> -->

                </tr>
            @endforeach 
          </tbody>
        </table>
      </div>
  
  <div class="row mt-3" style="color: #515151">
      {{-- <div class="col">
        สถานะ: 
        <span class="status-circle status-grey"></span> ยังไม่มีการตรวจสอบ
        <span class="status-circle status-yellow"></span> กำลังดำเนินการ
        <span class="status-circle status-red"></span> ไม่อนุมัติ
        <span class="status-circle status-green"></span> อนุมัติ
      </div> --}}
      <div class="col">
        ผลการค้นหาทั้งหมด {{$documents->total()}} รายการ
      </div>
      @if( !is_array($documents ))
      <div class="col">
        {{ $documents->links() }}
      </div>
      @endif
	</div>
	
</div>
	{{-- <div id="examModal" class="modal" role="dialog" style="display:block"> --}}
<div id="examModal" class="modal" role="dialog" >
		<div class="modal-dialog" role="document">
      <form id="approveForm" action="" method="post" >
				<div class="modal-content border-top-primary">
          <div class="modal-header">
              <h5 class="modal-title">ส่งเอกสาร</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
            @csrf
            @method("PUT")
            <div class="form-group">
              <label for="">ถึง: </label>
              {{-- <input type="text" class="form-control"> --}}
              <div class="input-search-group" id="nameSearch">
                <div class="input-group">
                  <input  class="form-control" type="text" placeholder="ค้นหารายชื่อ">
                  <div class="input-group-append">
                    <span class="input-group-text">
                      <i class="fa fa-search"></i>
                    </span>
                  </div>
                </div>
                <div class="results">

                </div>
              </div>
              <div id="tagged"></div>
            </div>
            <div class="row">
              <div class="col-6">
                  <div class="form-group">
                      <label for="">หัวจดหมาย: </label>
                      <input type="text" class="form-control" name="heading">
                    </div>
              </div>
              <div class="col-6">
                  <div class="form-group">
                      <label for="">ประเภทเอกสาร: </label>
                      <input type="text" class="form-control" id="modalDoctypeInput" disabled>
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
            <div class="form-group">
                <label for="">ความเห็นเพิ่มเติม: </label>
                <textarea class="form-control" name="remark" id="" cols="30" rows="5" placeholder="ใช้บันทึก เตือนความจำ หรืออธิบายเนื้อหาของเอกสารโดยย่อ เป็นต้น"></textarea>
            </div>
            <input type="hidden" name="document_id">
          </div>
          <div class="modal-footer float-left">
            <button type="submit" class="btn btn-primary">ส่งเอกสาร</button>
            <button type="button" class="btn btn-secondary text-left" data-dismiss="modal">ปิด</button>
          </div>
				</div>
		</form>
	</div>
</div>

<div id="alertModal" class="modal" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content border-top-primary">
      <div class="modal-header">
        เอกสารจาก: 
      </div>
      <div class="modal-body">
        <div class="form-row">
          <div class="form-group col-6">
            <label for="">ประเภทเอกสาร</label>
            <input type="text" class="form-control">
          </div>
          <div class="form-group col-6">
              <label for="">การตอบกลับ</label>
              <input type="text" class="form-control">
            </div>
        </div>
        <div class="form-group">
          <label for="">ความคิดเห็น</label>
          <textarea name="remark" id="" cols="30" rows="10"></textarea>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
@endsection

@section('script')
<script src="{{asset('js/document/index.js')}}"></script>
<script>
	$(`a.btn-delete`).click(function(e){
    e.preventDefault();
    if(!confirm('แน่ใจแล้วหรือ?')){
      return false;
    } else {
      $(this).find('form').submit();
    }
	});

  $(`a.tab-box`).click(function(e){
    e.preventDefault()
    var form = $(`#searchForm`);
    data = $(this).data('tab');
    form.append(`<input name="t" value="${data}" type="hidden">`);
    form.submit();
  })

	$("#nameSearch").search({
		url: "{{ route("ajax.search_user")}}"
	});

	$('a.assign').click(function(e){
		e.preventDefault();
		url = $(this).data("url");
		type = $(this).data("doc-type");
		id = $(this).data("doc-id");
		$("#approveForm").attr("action", url);
		$("#approveForm").find(`input[name="document_id"]`).attr("value", id);		
		$("#modalDoctypeInput").attr("value", type);

  })
  
  $('#searchInput').on('focus focusout', function(e){
    if (e.type == 'focus'){
      $('#moreSearch').addClass('active');
      $('#pdf').hide();
    } 
  });

  $('#searchInput').change(function(){
    $("#textSearch").val($(this).val());
  });

  // $("#pdf").click(function(e){
  //   e.preventDefault()
  //   $('#searchInput').hide();
  //   $('#moreSearch').addClass('active');
  // });

  $("#closeMoreSearch").click(function(){
    console.log("asd");
    $('#pdf').show();
    $('#searchInput').show();
    $('#moreSearch').removeClass('active');

  });
  function getFolderurl(id) {
		host = "{{ url("") }}";
		uri = host+"/ajax/cabinets/"+id+"/folders" ;
		return uri ;
	}

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

</script>
@include('alert.alert')

@endsection

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/2.7.94/css/materialdesignicons.css">
    <link rel="stylesheet" href="{{ asset("css/document/index.css") }}">

@endpush

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/locales/bootstrap-datepicker.th.min.js"></script>
@endpush
