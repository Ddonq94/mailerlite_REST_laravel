@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">

        
            <div class="card">
                <div class="card-header">Select your preferred user <a class="btn btn-sm btn-primary float-right" href="/Users/create">Add user</a></div>

                <div class="card-body">
                    @if($users->count() < 1)
                        <div class="card">
                            <div class="card-body text-danger">
                                Maybe add some Users first? 
                            </div>
                        </div>
                    @endif
                

                @if($users->count() > 0)
                <table class="table table-bordered table-sm mt-4 ml-4" id="tbl" style="width:90% !important; margin:15px" >
                    <thead>
                        <tr>
                            
                            <th>Name</th>
                            <th>Email</th>
                            <th >Action</th>
                        </tr>
                    </thead>
                    <tbody id="tblData">
                    @foreach($users as $user)
                        <tr>
                        <td>{{ ucfirst($user->name) }}</td>
                        <td>{{$user->email}}</td>
                        <td> <a class="btn btn-sm btn-primary" href="/Users/{{$user->id}}/subscribers">Use this user</a> </td>
                        </tr>
                    @endforeach

                    </tbody>  
                </table>
                @endif
                </div>
            </div>
        </div>
    </div>
</div>




<script>
    $(document).ready(function() {
        $('#tbl').DataTable();
        
    });

   

</script>



@endsection
