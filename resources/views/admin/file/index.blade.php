@extends('layouts.app-master')

@section('admin-content')
<div class="bg-light p-5 rounded">
        @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block"> 
                <strong>{{ $message }}</strong>
        </div> 
        @endif

        @if ($error = Session::get('error'))
        <div class="alert alert-error alert-block"> 
                <strong>{{ $error }}</strong>
        </div> 
        @endif

        @if (count($errors) > 0)
            <div class="alert alert-danger"> 
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="country-filter">
                <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Fayl axtar...">
                </div>
        </div>
        <button type="button" class="btn btn-primary add-new-row" data-toggle="modal" data-target="#exampleModal">Yeni fayl</button>
        <table class="table">
                <thead class="table-dark">
                        <tr> 
                                <th class="table-primary">№</th>
                                <th class="table-primary">Ad</th>
                                <th class="table-primary">link</th>
                                <th class="table-primary"></th>
                        </tr>
                </thead>
                <tbody>
                        @foreach($list as $index => $item)
                        <tr> 
                                <th class="table-light">{{ $index+1 }}</th>
                                <td class="table-light"> {{ $item->name }} </td>
                                <td class="table-light">
                                        <a href="../assets/uploads/files/{{ $item->file }}" target="_blank">{{URL::to('/')}}/../assets/uploads/files/{{ $item->file }}</a>
                                </td>
                                <td class="table-light table-edit-field">
                                        <button type="button" class="btn btn-danger" onClick="removeRow({{ $item->id }}, '/admin/file-remove/')">sil</button> 
                                </td>
                        </tr>
                        @endforeach
                </tbody>
        </table>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                        <div class="modal-content">
                                <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Fayl əlavəsi</h5>
                                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="post" action="/admin/file-add" enctype="multipart/form-data">
                                        <div class="modal-body">
                                                <div class="mb-3">
                                                        <label for="countryName" class="form-label">Fayl adı</label>
                                                        <input type="text" class="form-control" name="name" id="countryName" required placeholder="Ad daxil edin">
                                                </div>
                                                <div class="mb-3">
                                                        <label for="formFile" class="form-label">fayl daxil edin</label>
                                                        <input class="form-control" type="file" id="formFile" name="file">
                                                </div>
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                                <input type="hidden" name="check_place" value="1" />
                                        </div>
                                        <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Göndər</button>
                                        </div>
                                </form>
                        </div>
                </div>
        </div>
</div>

<script>

        $(function(){

                $(".country-filter input").keypress(function(event){
                        let val = $(this).val();
                        
                        var keycode = (event.keyCode ? event.keyCode : event.which);

                        if(val.length>0 && keycode == '13') {
                                $.ajax({
                                        url: "/admin/file-search/"+val,
                                        method: "get",
                                        success: (res)=>{
                                                console.log(res.data);

                                                let baseUrl = window.location.host;
                                                let str = "";

                                                (res.data).forEach((item, index)=>{
                                                        str += `
                                                                <tr> 
                                                                        <th class="table-light">${index+1}</th>
                                                                        <td class="table-light"> ${item.name} </td>
                                                                        <td class="table-light">
                                                                                <a href="../assets/uploads/files/${ item.file }" target="_blank">${baseUrl}/../assets/uploads/files/${ item.file }</a>
                                                                        </td>
                                                                        <td class="table-light table-edit-field">
                                                                                <button type="button" class="btn btn-danger" onClick="removeRow( ${ item.id }, '/admin/file-remove/')">sil</button> 
                                                                        </td>
                                                                </tr>
                                                        `;
                                                })

                                                $(".table tbody").html(str);
                                        }
                                })
                        } 
                })


        })

</script>
@endsection