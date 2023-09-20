<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    
    <title>Aktivitas Belajar</title>
  </head>
  <body>
    @include('includes.navbar')
        <br>
       
        <br>
        <div class="container">
            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addActivityModal">Tambah Activity</button>
            <br>
            <br>
            <table class="table" style="font-size: 12px">
                <thead class="thead-dark">
                  <tr>
                    <th scope="col">Method</th>
                    <th scope="col">Januari</th>
                    <th scope="col">Februari</th>
                    <th scope="col">Maret</th>
                    <th scope="col">April</th>
                    <th scope="col">Mei</th>
                    <th scope="col">Juni</th>
                    <th scope="col">Juli</th>
                    <th scope="col">Agustus</th>
                    <th scope="col">September</th>
                    <th scope="col">Oktober</th>
                    <th scope="col">November</th>
                    <th scope="col">Desember</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
        
        </div>
      

   <!-- Modal untuk menambahkan activity -->
    <div class="modal fade" id="addActivityModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Activity</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form input activity -->
                    <form>
                        <div class="form-group">
                            <label for="categoryMethod">Kategori Method:</label>
                            <select class="form-control" id="categoryMethodSelect">
                                <option value="">Select a category method</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="activityName">Nama Activity:</label>
                            <input type="text" class="form-control" id="activityName">
                        </div>
                        <div class="form-group">
                            <label for="startDate">Tanggal Mulai:</label>
                            <input type="date" class="form-control" id="startDate">
                        </div>
                        <div class="form-group">
                            <label for="endDate">Tanggal Berakhir:</label>
                            <input type="date" class="form-control" id="endDate">
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

    <script>
        $(document).ready(function() {

            $('#addActivityModal').on('show.bs.modal', function() {
                var categoryMethodSelect = $('#categoryMethodSelect');

                // Clear existing options
                categoryMethodSelect.empty();

                // Fetch category methods via AJAX
                $.ajax({
                    url: "{{ route('get-category-methods') }}",
                    type: "GET",
                    success: function(response) {
                        categoryMethodSelect.append($('<option>', {
                            value: "", 
                            text: "Select a category method"
                        }));
                        
                        $.each(response, function(index, categoryMethod) {
                            categoryMethodSelect.append($('<option>', {
                                value: categoryMethod.id, 
                                text: categoryMethod.learning_method
                            }));
                        });
                    },
                    error: function(response) {
                        alert('Failed to fetch category methods.');
                    }
                });
            });

            // Tangkap tombol "Simpan" di modal
            $('#addActivityModal').on('click', '.btn-primary', function() {
                var categoryMethod = $('#categoryMethod').val();
                var activityName = $('#activityName').val();
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();
    
                // Kirim permintaan Ajax ke controller
                $.ajax({
                    url: "{{ route('store-activity') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "categoryMethod": categoryMethod,
                        "activityName": activityName,
                        "startDate": startDate,
                        "endDate": endDate
                    },
                    success: function(response) {
                        // Tampilkan pesan sukses atau tindakan lainnya
                        alert(response.message);
                        // Tutup modal jika berhasil
                        $('#addActivityModal').modal('hide');
                    },
                    error: function(response) {
                        // Tampilkan pesan kesalahan jika ada masalah
                        alert('Gagal menyimpan data aktivitas.');
                    }
                });

                
            });
        });
    </script>
  </body>
</html>