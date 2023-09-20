<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/v/dt/dt-1.13.6/datatables.min.css" rel="stylesheet">
    <title>Aktivitas Belajar</title>
  </head>
  <body>
    @include('includes.navbar')
        <br>
       
        <br>
        <div class="container">
            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addActivityModal">Tambah Metode</button>
            <br>
            <br>
            <table class="table" id="learningMethodsTable">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Method Learning</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Table rows will be populated dynamically through Ajax -->
                </tbody>
            </table>
            
            
        
        </div>
      

   <!-- Modal untuk menambahkan activity -->
    <div class="modal fade" id="addActivityModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Metode</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form input activity -->
                    <form>
                        <div class="form-group">
                            <label for="methodeName">Nama Metode</label>
                            <input type="text" class="form-control" id="methodeName">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>

   <!-- Modal untuk edit activity -->
    <div class="modal fade" id="editActivityModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Metode</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form input activity -->
                    <form>
                        <div class="form-group">
                            <label for="editMethodeName">Nama Metode</label>
                            <input type="text" class="form-control" id="editMethodeName">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/v/dt/dt-1.13.6/datatables.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#learningMethodsTable').DataTable();
            // Tangkap tombol "Simpan" di modal
            $('#addActivityModal').on('click', '.btn-primary', function() {
                var metodeName = $('#methodeName').val();
    
                // Kirim permintaan Ajax ke controller
                $.ajax({
                    url: "{{ route('store-learning-method') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "metodeName": metodeName,
                    },
                    success: function(response) {
                        // Tampilkan pesan sukses atau tindakan lainnya
                        alert(response.message);
                        // Tutup modal jika berhasil
                        $('#addActivityModal').modal('hide');
                        table.ajax.reload();
                    },
                    error: function(response) {
                        // Tampilkan pesan kesalahan jika ada masalah
                        alert('Gagal menyimpan data aktivitas.');
                    }
                });
            });

            
           

            // Destroy the existing DataTable instance
            table.destroy();

            // Reinitialize DataTable with the updated configuration
            table = $('#learningMethodsTable').DataTable({
                ajax: {
                    url: "{{ route('get-learning-method-datatable') }}", 
                    type: "GET",
                },
                columns: [
                    { data: 'id' }, 
                    { data: 'learning_method' }, 
                    {
                        data: null,
                        render: function(data, type, row) {
                            // Add action buttons here (Edit and Delete buttons)
                            return '<a href="javascript:void(0);" class="btn btn-warning edit-method" data-id="' + data.id + '">Edit</a>' +
                            '&nbsp;&nbsp;' + // Add some HTML space
                            '<button class="btn btn-danger delete-method" data-id="' + data.id + '">Delete</button>';
                        }
                    }
                ]
            });

            var editModal = $('#editActivityModal'); 

            $('#learningMethodsTable').on('click', '.edit-method', function() {
                var methodId = $(this).data('id');
                
                $.ajax({
                    url: '/metode/' + methodId + '/edit', 
                    type: 'GET',
                    success: function(response) {
                        editModal.find('#editMethodeName').val(response.learning_method);
                    //   console.log(response);

                        // Show the edit modal
                        editModal.modal('show');
                    },
                    error: function(response) {
                        alert('Failed to fetch data for editing.');
                    }
                });

                    $('#editActivityModal').on('click', '.btn-primary', function() {
                    var editedMethod = $('#editMethodeName').val();
                    $.ajax({
                        url: '/metode/' + methodId, 
                        type: 'PUT', 
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "metodeName": editedMethod,
                        },
                        success: function(response) {
                            alert(response.message);
                            editModal.modal('hide');
                            table.ajax.reload();
                        },
                        error: function(response) {
                            alert('Failed to update data.');
                        }
                    });
                });

            });


            $('#learningMethodsTable').on('click', '.delete-method', function() {
                var methodId = $(this).data('id');

                if (confirm('Are you sure you want to delete this method?')) {
                    $.ajax({
                        url: '/metode/' + methodId, 
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            alert(response.message);
                            table.ajax.reload();
                        },
                        error: function(response) {
                            alert('Failed to delete the method.');
                        }
                    });
                }
            });


           
       
        });
    </script>
  </body>
</html>