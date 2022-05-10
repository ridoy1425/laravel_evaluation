@extends('layouts.app')
@include('sweetalert::alert', ['cdn' => "https://cdn.jsdelivr.net/npm/sweetalert2@9"])


@section('style')
<style>
    /* The Modal (background) */
 .modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        /* Stay in place */
        z-index: 1;
        /* Sit on top */
        padding-top: 150px;
        /* Location of the box */
        left: 0;
        top: 0;
        width: 100%;
        /* Full width */
        height: 100%;
        /* Full height */
        overflow: auto;
        /* Enable scroll if needed */
        background-color: rgb(0, 0, 0);
        /* Fallback color */
        background-color: rgba(0, 0, 0, 0.4);
        /* Black w/ opacity */
    }

    /* Modal Content */
    .modal-content {
        background-color: #fefefe;
        margin: auto;
        padding: 20px;
        /* left:80px; */
        border: 1px solid #888;
        width: 50%;
        display:flex;
    }

    /* The Close Button */
    .close {
        color: #aaaaaa;
        font-size: 28px;
        float:right;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }

</style>
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Products') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="main_content">
                        <div class="d-flex flex-row-reverse" >
                            <button type="button" id="addBtn" class="btn btn-warning">Add Product</button>
                        </div>
                       <div class="product_table">
                        <table class="table" id="myTable">
                            <thead>
                              <tr>
                                <th scope="col">#</th>
                                <th scope="col">Title</th>
                                <th scope="col">Description</th>
                                <th scope="col">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              
                            </tbody>
                          </table>
                       </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- The Modal -->
<div id="myModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <div>
           
        <span class="close">&times;</span>
        </div>
        <div>
            <h4>Add Product</h1>
        <form action="" method="post" id="form">
        @csrf
        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <select class="form-control" id="category" name="category">
                <option>select</option>
                @foreach($category as $row)
                    <option value="{{ $row->id }}">{{ $row->title }}</option>  
                @endforeach                
            </select>
        </div>
        <div class="mb-3">
            <label for="subcategory" class="form-label">Subcategory</label>
            <select class="form-control" id="subcategory" name="subcategory">
            </select>
        </div>
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text"  class="form-control" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>
        
        <div class="mb-3">
            <label for="pay_date" class="form-label">Price</label>
            <input type="text"  class="form-control" id="price" name="price" required>
        </div>
        <div class="mb-3">
            <label for="pay_date" class="form-label">Thumbnail</label>
            <input type="file"  class="form-control" id="thambnail" name="thambnail" required>
        </div>
        <button type="button" id="prodcut_save" class="btn btn-warning">Save</button>
    </form>
        </div>
    </div>
</div>

@endsection
@section('customscript')
<script>
    $(document).ready(function(){
        $('#category').change(function(){
            if ($(this).val() != '') {
                var category = $(this).val();
                $.ajax({
                    url: "/subCatSearch",
                    type: 'get',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id: category,
                    },
                    success: function (data) {
                        console.log(data);
                        $("#subcategory").empty();
                        $( `<option value="">Select</option>` ).appendTo( "#subcategory" );
                        $.each(data, function (key, value) {
                            $("#subcategory").append(`<option value="` + value
                                .id + `">` +value.title+`</option>`)

                        });
                    }
                });
            }
        })

        $("#addBtn").click(function(){            
            document.querySelector('#myModal').style.display = 'block';
        });

        $('.close').on('click', function () {
        document.querySelector('#myModal').style.display = 'none';
        $("form")[0].reset();
        location.reload(true);
    });

    $("#prodcut_save").click(function(){  
        let title = $('#title').val();          
        let description = $('#description').val();          
        let subcategory = $('#subcategory').val();
        let price = $('#price').val();          
        let thambnail = $('#thambnail').val();          
        $.ajax({
            type: "POST",
            url: "{{url('addProducts')}}",
            data: {
                "_token": "{{ csrf_token() }}",
                title:title,
                description:description,
                subcategory:subcategory,
                price:price,
                thambnail:thambnail, 
            },
            success: function (response) {                
                console.log(response);
                swal("Poof! Your imaginary data has been Saved!", {
                            icon: "success",
                            });
            },
            error: function () {
                alert('Failed');
            }
            
        });
        $("#form")[0].reset();
    });
    var i = 1;
    // filter_product----------------
    fill_datatable();
    function fill_datatable(title='', description='', subcategory= '', price= '') {
            var table = $('#myTable').DataTable({
            processing: true,
            serverSide: true,
            // region:region, area:area,
            ajax:{
                url:"{{url('productSearch')}}",
                // data:{ title:title, description:description, subcategory:subcategory, price:price}
            },
            
            columns: [
                {
                "render": function(data, type, full, meta) {
                    return i++;                
                }},
                {data: 'title', name: 'branchcode'},
                {data: 'description', name: 'description'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        }

        $(document).on('click', '#productDelete', function(){
            var productId = $(this).data('id');
            swal({
                title: "Are you sure you want to delete this record?",
                text: "If you delete this, it will be gone forever.",
                icon: "warning",
                type: "warning",
                buttons: ["Cancel","Yes!"],
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: "GET",
                        url: "/product_delete",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            id:productId
                        },
                        success: function (response) {                
                            console.log(response);                            
                            swal("Poof! Your imaginary data has been delete!", {
                            icon: "success",
                            }).then((ok)=>{
                                if(ok)
                                {
                                    location.reload(true);
                                }
                               
                            });
                            
                        },
                        error: function () {
                            alert('Failed');
                        }
                        
                    });
                }
            });
        });

    });
</script>

@endsection
