@extends('layout.main')

@section('content')


    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0 fs-3">Task</h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Forms</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Layout</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="app-content">
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-12">
                    @if(session('success'))
                    <div class="callout callout-info">
                        <!-- Layout patterns built on Bootstrap's grid +
                        <a href="https://getbootstrap.com/docs/5.3/forms/layout/" target="_blank"
                            rel="noopener noreferrer" class="callout-link">form layout</a>
                        utilities. -->
                        
                        {{session('success')}}
                        @elseif(session('error'))
                        {{session('error')}}
                        
                    </div>
                    @endif
                </div>
                <!-- Inline Form -->
                <div class="col-md-6">
                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header">
                            <div class="card-title">Edit Task</div>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{route('tasks.update',$task->id)}}"
                                class=" row row-cols-lg-auto g-3 align-items-center">
                                @csrf
                                <div class="col-12">
                                    <label class="visually-hidden" for="inlineUser">Username</label>
                                    <div class="input-group">

                                        <input type="text" name="task_name" value="{{$task->task_name??''}}" class=" form-control" id="inlineUser"
                                            placeholder="Task" required />
                                    </div>
                                </div>
                                <!-- <div class="col-12">
                                    <label class="visually-hidden" for="inlineSelect">Project</label>
                                    <select name="project_id" class=" form-select" id="inlineSelect" required>
                                        <option selected>Project&hellip;</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                    </select>
                                </div> -->
                                <div class="col-12">
                                    <div class="form-check">
                                        <input name="priority" class="form-control" value="{{$task->priority??''}}" type="number" id="inlineCheck"
                                            placeholder="Priority" required />

                                    </div>
                                </div>
                                <div class=" col-12">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection