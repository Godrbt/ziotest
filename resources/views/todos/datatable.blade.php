<!doctype html>
<html lang="en">
    <head>
        <title>Title</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />

        <!-- Bootstrap CSS v5.2.1 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
        />

        <link rel="stylesheet" href="https://cdn.datatables.net/2.3.6/css/dataTables.dataTables.css" />

    </head>

    <body>
        <header>
            <!-- place navbar here -->
        </header>
        <main>

        <div class="container py-5">
            <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="card-title mb-0">Datatable</h5>
    <a href="/todos" class="btn btn-dark">Create New Todo</a>
</div>

            <div class="card-body">
                <div class="table responsive">
                <table class="table table-striped DataTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Due Date</th>
                            <th>Priority</th>
                            <th>File</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    
                        
                </table>
                </div>
            </div>
            </div>
        </div>

        </main>
        <footer>
            <!-- place footer here -->
        </footer>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <!-- Bootstrap JavaScript Libraries -->
        <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        ></script>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        ></script>
          
<script src="https://cdn.datatables.net/2.3.6/js/dataTables.js"></script>

    <script>
        $(document).ready(function () {
           const table = $('.DataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('todos.datatable') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'description', name: 'description' },
                    { data: 'due_date', name: 'due_date' },
                    { data: 'priority', name: 'priority' },
                    { data: 'file', name: 'file' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });

                $('table').on('click', '.delete-user', function(e){
                   
                    const id = $(this).data('id');
                    if(confirm('Are you sure you want to delete this todo?')){
                        $.ajax({
                            url: "/todos/" + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response){
                                alert('Todo deleted successfully');
                                table.ajax.reload();
                            },
                            error: function(xhr){
                                alert('An error occurred while deleting the todo');
                            }
                        });
                    }
                });

                const editableCols=[1,2,3,4];
                let currentEditableRow = null;


                $('table').on('click', '.edit-user', function(e){
                   
                    const id = $(this).data('id');
                    const currentRow = $(this).closest('tr'); 


                    if(currentEditableRow && currentEditableRow !== currentRow){
                        resetEditableRow(currentEditableRow);
                    }


                   makeEditableRow(currentRow);

                   currentEditableRow  = currentRow;

                currentRow.find('td:last').html(`<div class="d-flex gap-2">
            <button class="btn btn-sm btn-primary btn-update" data-id="${id}">Save</button>
            <button class="btn btn-sm btn-danger delete-user" data-id="${id}">Delete</button>
        </div>`);
                });

                function makeEditableRow(currentRow){
                    currentRow.find('td').each(function(index){

                        const currentCell = $(this);
                        const currentText = currentCell.text().trim();

                        if(editableCols.includes(index)){
                            currentCell.html(`<input type="text" class="form-control editable-input" value="${currentText}"/>`);
                        }
                    });
                    
                }

                function resetEditableRow(currentEditableRow){
                  currentEditableRow.find('td').each(function(index){
                        const currentCell = $(this);
                        if(editableCols.includes(index)){
                            const currentValue = currentCell.find('input').val();
                            currentCell.html(`${currentValue}`);
                        }
                    });

                    const id = currentEditableRow.find('.btn-update').data('id');

                    currentEditableRow.find('td:last').html(`
                    <div class="d-flex gap-2">
            <button class="btn btn-sm btn-success edit-user" data-id="${id}">Edit</button>
            <button class="btn btn-sm btn-danger delete-user" data-id="${id}">Delete</button>
        </div>`); 

        currentEditableRow = null;
                    
                }

                $('table').on('click', '.btn-update', function(e){
                    const id = $(this).data('id');
                    const currentRow = $(this).closest('tr');

                    const updatedData = {};

                    currentRow.find('td').each(function(index){
                        if(editableCols.includes(index)){
                            const currentCell = $(this);
                            const newValue = currentCell.find('input').val();
                            if(index === 1) updatedData.name = newValue;
                            if(index === 2) updatedData.description = newValue;
                            if(index === 3) updatedData.due_date = newValue;
                            if(index === 4) updatedData.priority = newValue;
                        }
                    });

                            $.ajax({
                                url: "/todos/" + id,
                                type: 'POST',
                                data: {
                                    id: id,
                                    _method: 'PUT',
                                    name: updatedData.name,
                                    description: updatedData.description,
                                    due_date: updatedData.due_date,
                                    priority: updatedData.priority,
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(response){
                                    alert('Todo updated successfully');
                                    table.ajax.reload();
                                },
                                error: function(xhr){
                                    alert('An error occurred while updating the todo');
                                }
                                
                            });
                    });
                });
          
    </script>
    </body>
</html>
