@extends('layout.main')

@section('content')
<div class="app-content-header">
        <div class="app-content">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-md-8">
                <div class="card mb-4">
                  <div class="card-header">
                    <h3 class="card-title">Task List</h3>
                  </div>
                  <p id="status"></p>
                  <!-- /.card-header -->
                  <div class="card-body">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <!-- <th style="width: 10px">#</th> -->
                          <th>Task</th>
                          <th>Priority</th>
                          <!-- <th style="width: 40px">Project</th> -->
                          <th style="width: 100px">Action</th>
                        </tr>
                      </thead>
                      <tbody id="sortable-tasks">
                        @if($tasks->count() > 0)
                        @foreach($tasks as $task)
                        <tr class="align-middle" draggable="true" data-id="{{$task->id}}">
                          <!-- <td class="drag-handle">{{$task->id??""}}</td> -->
                          <td class="drag-handle">{{$task->task_name??""}}</td>
                          <td class="priority">
                            {{$task->priority??""}}
                          </td>
                          <!-- <td>{{$task->project_id??""}}</td> -->
                          <td><a href="{{route('tasks.edit', $task->id)}}" class="btn btn-info btn-sm"><i class="bi bi-pencil"></i></a>
                              <button id="delete-task" data-delete-url="{{route('tasks.destroy', $task->id)}}" class="btn btn-danger btn-sm delete"><i class="bi bi-archive-fill"></i></button>
                          </td>

                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="4" align='center'>No Record Found</td>
                        </tr>
                        @endif
                        
                      </tbody>
                    </table>
                  </div>
                  <!-- /.card-body -->
                  @if($tasks->count() > 0)
                  <div class="card-footer clearfix pagination-div">
                    <!-- <ul class="pagination pagination-sm m-0 float-end">
                      <li class="page-item">
                        <a class="page-link" href="#">&laquo;</a>
                      </li>
                      <li class="page-item">
                        <a class="page-link" href="#">1</a>
                      </li>
                      <li class="page-item">
                        <a class="page-link" href="#">2</a>
                      </li>
                      <li class="page-item">
                        <a class="page-link" href="#">3</a>
                      </li>
                      <li class="page-item">
                        <a class="page-link" href="#">&raquo;</a>
                      </li>
                    </ul> -->
                    {{$tasks->links()}}
                  </div>
                  @endif
                </div>
              </div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        @include('tasks.delete-modal');
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.min.js"></script>
    <script type="text/javascript">
        
// code to delete the task on btn click
$(document).ready(function(){
    var modal = $('.modal');
    $(document).on('click','.delete',  function(){
        var url = $(this).data('delete-url');
        modal.attr('data-delete-url', url);
        modal.show();
    });

    $('.close-modal').on('click', function(){
        modal.hide();
    });
    $('.delete-task').on('click', function(){
        url = modal.data('delete-url');
        data = url.split('/');
        console.log(data);
        task_id = data[-1];
        $.ajax({
            url: url,
                method: 'DELETE',

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    task_id: task_id
                },
                success: function (response) {
                    // Refresh the displayed priority numbers.
                   console.log('task deleted successfully.')
                   window.location.reload();
                },

                error: function (xhr) {
                    console.log('some error occured.')
                }
        })
    })
});

$(function () {
    const $tbody = $('#sortable-tasks');
    const $status = $('#status');

    $tbody.sortable({
        handle: '.drag-handle',
        axis: 'y',
        helper: 'clone',
        placeholder: 'ui-sortable-placeholder',

        update: function () {
            const order = [];

            $tbody.find('tr').each(function () {
                order.push($(this).data('id'));
            });

            $status.text('Saving order...');

            $.ajax({
                url: "{{ route('tasks.reorder') }}",
                method: 'POST',

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                data: {
                    order: order
                },

                success: function (response) {
                    // Refresh the displayed priority numbers.
                    $tbody.find('tr').each(function (index) {
                        $(this).find('.priority').text(index + 1);
                    });
                    $status.addClass('alert alert-info')
                    $status.text(response.message);
                },

                error: function (xhr) {
                    $status.text(
                        'Could not save the new order. Restoring the previous order...'
                    );
                    //console.log(xhr);
                    // Reload the database-backed ordering.
                    window.location.reload();
                }
            });
        }
    });
});
    </script>
@endsection