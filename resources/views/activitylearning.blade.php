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
        <div class="container-fluid">
            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addActivityModal">Tambah Activity</button>
            <br>
            <br>
            <table class="table" id="table_activities">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Method</th>
                        @foreach (['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $month)
                            <th scope="col">{{ $month }}</th>
                        @endforeach
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be populated here using Ajax -->
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
                            <label for="categoryMethodSelect">Kategori Method:</label>
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
    <script src="https://cdn.datatables.net/v/dt/dt-1.13.6/datatables.min.js"></script>
    <script>
        $(document).ready(function() {

            var table = $('#table_activities').DataTable({
                    ajax: {
                        url: "{{ route('get-learning-activities') }}",
                        type: "GET",
                    },
                    columns: [
                        { data: 'learning_method', title: 'Metode' }, // Set the title for the learning_method column
                        @foreach (['January', 'February', 'Maret', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $month)
                            {
                                data: null,
                                title: '{{$month}}', 
                                render: function(data, type, row) {
                                    // console.log(data.activity_months[0].learning_activity_id)
                                    var activities = row.activity_months.filter(function(monthData) {
                                        return monthData.month === '{{$month}}';
                                    });

                                    if (activities.length > 0) {
                                        
                                        var activitiesList = '<ul>';
                                        activities.forEach(function(activity) {
                                            activitiesList += '<li>' + activity.activities + '</li>';
                                        });
                                        activitiesList += '</ul>';
                                        
                                        return activitiesList
                                    } else {
                                        return ''; // No data for this month
                                    }
                                }
                            },
                        @endforeach
                        {
                            data: null,
                            title: 'Action', 
                            render: function(data, type, row) {
                               
                                return '<button class="btn btn-sm btn-danger delete-method" data-learning_activity_id="' + data.activity_months[0].learning_activity_id + '" data-method="' + row.learning_method + '">Delete</button>';
                            }
                        }
                    ]
                });

                $('#table_activities').on('click', '.delete-method', function() {
                    var learningActivityId = $(this).data('learning_activity_id');
                    var learningMethod = $(this).data('method');
                    console.log(learningActivityId)
                    if (confirm('Are you sure you want to delete all activities for the learning method ' + learningMethod + '?')) {
                
                        $.ajax({
                            url: "delete-activities-by-learning-method/" + learningActivityId,
                            type: "DELETE",
                            data: {
                                learningActivityId: learningActivityId,
                                _token: "{{ csrf_token() }}",
                            },
                            success: function(response) {
                                alert(response.message);
                                table.ajax.reload();
                            },
                            error: function(response) {
                                alert('Failed to delete activities.');
                            }
                        });
                    }
                });


            $('#addActivityModal').on('show.bs.modal', function() {
                var categoryMethodSelect = $('#categoryMethodSelect');

                // Clear existing options
                categoryMethodSelect.empty();

                // Fetch category methods via AJAX
                $.ajax({
                    url: "{{ route('get-category-methods') }}",
                    type: "GET",
                    success: function(response) {
                        // console.log(response)
                        // jika response kosong
                        if (response.length === 0) {
                            $('#exampleModalLabel').append('<p class="text-danger"><i>Please add a method first</i></p>');
                        } 
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
                var categoryMethod = $('#categoryMethodSelect').val();
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
                        // Refresh tabel
                        table.ajax.reload();
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