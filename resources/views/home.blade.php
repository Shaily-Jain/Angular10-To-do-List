<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Todo List</title>
        <!-- <link href="plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet"> -->

        <!-- Fonts -->
        <!-- <link rel="preconnect" href="https://fonts.bunny.net"> -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.20.0/css/mdb.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

        <!-- Styles -->
        <style>
            .header{
                text-align: center;
                font-family: sans-serif;
                margin-top: 2rem;
                color: #212529;
                padding: 10px;
                display: block;
            }
            h5{
                font-size: 2rem;
                font-family: system-ui;
                display: block;
            }
            p{
                margin-bottom: 2rem;
                font-family: sans-serif;
                font-weight: 500;
            }
            .outer_card{
                border: 1.5px solid #000;
                box-sizing: border-box;
                padding: 2.3rem;
            }
            label{
                color: #000;
                font-family: sans-serif;
                font-size: 14px;
            }
            .add_div{
                margin: auto;
                margin-bottom: 0px;
            }
            .add_btn{
                width: 100%;
                font-family: sans-serif;
                font-size: 14px;
            }
            .nodatafound{
                margin-top: 2.5rem;
                text-align: center;
                font-size: 15px;
                font-weight: 500;
                font-family: sans-serif;
            }
            .tablecard{
                margin-top: 3rem;
                border-radius: 0px;
                border: 1px solid #212529;
                display: block;
                font-family: sans-serif;
                font-weight: bold;
            }
            table.table thead th {
                border-top: none;
                font-weight: 600;
            }
            .card-footer{
                margin-bottom: 1rem;
                font-size: 15px;
                font-weight: bold;
                font-family: sans-serif;
            }
            .card-footer h5{
                margin-top: 5rem;
                border-top: 1px solid #000;
                padding-top: 10px;
                font-size: 1.2rem;
            }
            .delete_task{
                font-size: 13px;
                font-weight: 500;
                text-align: center;
                margin: auto;
            }
            .table_header{
                height: 3rem;
                display: block;
            }
            
        </style>
    </head>

    <body>
        <section>
            <div class="container">
                <div>
                    <div class="header">
                            <h5>To-do List Application</h5>
                            <p>Where to-do list items are added/deleted and belong to categories</p>
                    </div>

                    <div class="flash-message" id="success-alert">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                            <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                        @endif
                        @endforeach
                    </div>

                    <div class="preloader pl-size-lg loading">
                        <div class="spinner-layer pl-red-grey">
                            <div class="circle-clipper left">
                                <div class="circle"></div>
                            </div>
                            <div class="circle-clipper right">
                                <div class="circle"></div>
                            </div>
                        </div>
                    </div>

                    <!-- form section -->
                    <div class="container outer_card">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="card shadow-md">
                                    <div class="card-body">
                                        <form id="store_data" action="{{ route('store') }}" method="POST">
                                            {{ csrf_field() }}
                                            <div class="form-group"> 
                                                <div class="row clearfix">
                                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                                        <label class="form-label liLeft" for="category">Category</label><br>
                                                        <select class="form-control show-tick select2" name="category_id" placeholder="Select Category" id="category_id">
                                                            <option value="">Select Category</option>
                                                            @foreach($categories as $category)
                                                            <option value="{{$category->id}}">{{ $category->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                                        <label class="form-label liLeft" for="Todo item">To-do item</label><br>
                                                        <input type="text" class="form-control" name="name" id="name" placeholder="Type todo item name">
                                                    </div>

                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 add_div">
                                                        <button type="submit" class="btn btn-success add_btn" value="add">Add</button>
                                                        <input type="hidden" id="todo_item_id" name="todo_item_id" value="0">
                                                    </div><br/><br/>

                                                </div>  
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- table section -->
                        <section>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="card tablecard">
                                        <div class="card-header table_header">To-do List Datatable</div>
                                            <div class="body table-responsive">
                                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable myemptable">
                                                    <thead>
                                                        <tr class="bg-orange">
                                                            <th scope="col">id</th>
                                                            <th>Todo item name</th>
                                                            <th>Category</th>
                                                            <th>Timestamp</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if(count($todolists) > 0)
                                                            @foreach($todolists as $todolist)
                                                                <tr id="todolist{{$todolist->id}}">
                                                                    <td>{{ $todolist->id }}</td>
                                                                    <td>{{ $todolist->name }}</td>
                                                                    <td>{{ $todolist->category_id }}</td>
                                                                    <td>{{ $todolist->created_at->format('d') }}th</td>
                                                                    <td>
                                                                        <form action="{{ route('destroy', $todolist->id) }}" method="POST" id="delete_todo">
                                                                            {{ csrf_field() }}
                                                                            @method('DELETE')
                                                                            <button type="submit" class="btn btn-danger btn-xs btn-delete delete_task" value="{{$todolist->id}}">Delete</button>
                                                                        </form>
                                                                    </td>   
                                                                </tr>
                                                            @endforeach   
                                                            @endif    
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </section>

        <div class="card-footer">
            @if(count($todolists) > 0)
                <div class="card-footer">
                    <h5>You have {{ count($todolists) }} Tasks Pending :)</h5>
                </div>
            @endif
        </div>
    
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script src="{{ asset('js/todo.js') }}" defer></script>
    </body>
</html>
