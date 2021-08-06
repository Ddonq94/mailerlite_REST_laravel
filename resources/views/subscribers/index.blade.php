@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">

        
            <div class="card">
                <div class="card-header">Subscribers <button class="btn btn-sm btn-primary float-right" onclick="addNew()">Add Subscribers</button></div>

                <div class="card-body table-responsive">

                <div class="alert alert-success alert-dismissible" id="alert">
                    <p>Subscriber operation successful!</p>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <div class="alert alert-danger alert-dismissible" id="erralert">
                    <p>Something broke, try again or contact admin</p>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                    <table class="table  table-bordered table-sm mt-4 ml-4" id="tbl" style="width:90% !important; margin:15px" >
                        <thead></thead>
                        <tbody></tbody>  
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>




<!-- ================add modal===================== -->
<div class="modal " id="addSubModal">
    <div class="modal-dialog ">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title" id="headingCust"> Add New Subscriber</h4>
          <button type="button" class="close" data-dismiss="modal" onclick="$('#addSubModal').modal('hide')">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body" id="bodySub">

			<div class="row">
				
				<div class="col-12">
					<div class="form-group">
							<label for="sname">Subscriber Name:</label>
							<input type="type" class="form-control" id="sname">
						</div>

				</div>
				<div class="col-12">
					<div class="form-group">
							<label for="semail">Subscriber Email:</label>
							<input type="email" class="form-control" id="semail">
						</div>

				</div>
				<div class="col-12">
					<div class="form-group">
							<label for="scountry">Subscriber Country:</label>
							<input type="type" class="form-control" id="scountry">
						</div>

				</div>
				
			</div>

        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-warning" data-dismiss="modal" onclick="submitSub()">Submit</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="$('#addSubModal').modal('hide')">Close</button>
        </div>
        
      </div>
    </div>
  </div>

<!-- ================modal===================== -->

<!-- ================edit modal===================== -->
<div class="modal " id="editSubModal">
    <div class="modal-dialog ">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title" id="headingCust"> Edit Subscriber</h4>
          <button type="button" class="close" data-dismiss="modal" onclick="$('#editSubModal').modal('hide')">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body" id="edbodySub">

			<div class="row">
				
				<div class="col-12">
					<div class="form-group">
							<label for="edname">Subscriber Name:</label>
							<input type="type" class="form-control" id="edname">
						</div>

				</div>
				
				<div class="col-12">
					<div class="form-group">
							<label for="edcountry">Subscriber Country:</label>
							<input type="type" class="form-control" id="edcountry">
						</div>

				</div>
				
			</div>

        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-warning" data-dismiss="modal" onclick="submitEditSub()">Update</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="$('#editSubModal').modal('hide')">Close</button>
        </div>
        
      </div>
    </div>
  </div>

<!-- ================modal===================== -->




<script>

    $(document).ready(function() {
        $("#alert").hide();
        $("#erralert").hide();
        
    });

        $(function() {
            var drawer_count = 1;

            $('#tbl').DataTable({
                "oLanguage": {
                    "sProcessing": '<span>Please wait ...</span>'
                },
                "pagingType": "simple_numbers",
                "paging": true,
                "pageLength": 5,
                "lengthMenu": [
                    [1, 2, 5, 10, 25, 50],
                    [1, 2, 5, 10, 25, 50]
                ],
                "processing": true,
                "serverSide": true,
                "ordering": false,
                "ajax": {
                    "type": "GET",
                    "url": "{{ url('Users/subscribers') }}",
                    "data": {},
                    "dataFilter": function(data) {
                        drawer_count++;
                        var json = jQuery.parseJSON(data);
                        json.draw = json.draw;
                        json.recordsTotal = json.total;
                        json.recordsFiltered = json.total;
                        json.data = json.data;

                        $('#list_table_processing').css('display', 'none');
                        return JSON.stringify(json); // return JSON string
                    }
                },
                "columns": [
                    {"title": "Name", "data": "name", "name": "name", "visible": true, "searchable": false},
                    {"title": "Email", "data": "email", "name": "email", "visible": true, "searchable": true},
                    {"title": "Country", "data": "country", "name": "country", "visible": true, "searchable": false},
                    {"title": "Subscribe Date", "data": "date", "name": "date", "visible": true, "searchable": false},
                    {"title": "Subscribe time", "data": "time", "name": "time", "visible": true, "searchable": false},
                    {"title": "Action", "data": "action", "name": "action", "visible": true, "searchable": false},
                ],
            });
        });

        function reload_table() {
            $('#tbl').DataTable().ajax.reload();
        }


        var editId;

        function edit(id, name, country){
            

            $('#editSubModal').modal('show');

            $('#edname').val(name === "Not Available" ? "" : name);
            $('#edcountry').val(country === "Not Available" ? "" : country);

            editId = id;


        }

        
        
        function del(id){

            $.ajax({
                type:'POST',
                url:"{{ route('Subscribers.delete') }}",
                data:{ 
                        _token:'{{ csrf_token() }}',
                        id: id,
                    },
                success:(data)=>{
                    console.log(data);
                        $("#alert").show();

                    
                    reload_table();
                },
                error:(jqxhr,textSt, err)=>{
                    console.log(jqxhr,textSt, err);
                        $("#erralert").show();
                        $('#addSubModal').modal('hide');

                    
                    reload_table();
                }

                });

        }
        
        function addNew(){

		    $('#addSubModal').modal('show');

        }


        function submitEditSub(){

            if($("#edname").val() === ""){
                alert("Please Fill the name field")
                return;
            }
            
            if($("#edcountry").val() === ""){
                alert("Please Fill the country field")
                return;
            }

            $.ajax({
                type:'POST',
                url:"{{ route('Subscribers.update') }}",
                data:{ 
                        _token:'{{ csrf_token() }}',
                        id: editId,
                        name:$("#edname").val(),
                        country:$("#edcountry").val(),
                    },
                success:(data)=>{
                    console.log(data);
                    if(data.name === $("#edname").val()){
                        $("#alert").show();
                        $('#editSubModal').modal('hide');

                    }
                    else{
                        $("#erralert").show();
                        $('#addSubModal').modal('hide');
                    }
                    reload_table();
                },
                error:(jqxhr,textSt, err)=>{
                    console.log(jqxhr,textSt, err);
                        $("#erralert").show();
                        $('#addSubModal').modal('hide');

                    
                    reload_table();
                }

                });


            }

            function submitSub(){

            if($("#sname").val() === ""){
                alert("Please Fill the name field")
                return;
            }
            if($("#semail").val() === "" || !IsEmail($("#semail").val()) ){
                alert("Please Fill the email field with an email")
                return;
            }
            if($("#scountry").val() === ""){
                alert("Please Fill the country field")
                return;
            }

            $.ajax({
                type:'POST',
                url:"{{ route('Subscribers.store') }}",
                data:{ 
                        _token:'{{ csrf_token() }}',
                        name:$("#sname").val(),
                        email:$("#semail").val(),
                        country:$("#scountry").val(),
                    },
                success:(data)=>{
                    console.log(data);
                    if(data.name === $("#sname").val()){
                        $("#alert").show();
                        $('#addSubModal').modal('hide');

                    }
                    else{
                        $("#erralert").show();
                        $('#addSubModal').modal('hide');
                    }
                    reload_table();
                },
                error:(jqxhr,textSt, err)=>{
                    console.log(jqxhr,textSt, err);
                        $("#erralert").show();
                        $('#addSubModal').modal('hide');

                    
                    reload_table();
                }

                });


            }


                function IsEmail(email) {
                    var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                    if(!regex.test(email)) {
                    return false;
                    }else{
                    return true;
                    }
                }

    </script>



@endsection
