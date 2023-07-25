<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>پنل ادمین</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="{{ asset('/css/styles.css') }}" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand ps-3" href="/">صفحه اصلی</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0"></form>
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i>{{ auth()->user()->name }}</a>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">صفحات</div>
                            <a class="nav-link" href="{{ route('home') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                داشبورد
                            </a>
                            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout_form').submit();">
                                <form action="{{ route('logout') }}" method="POST" id="logout_form">
                                    {{ csrf_field() }}
                                </form>
                                <div class="sb-nav-link-icon"><i class="fas fa-sign-out"></i></div>
                                خروج
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Dashboard</h1><br>
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-plus"></i>
                                        @php
                                            echo isset($_GET['edit-genre']) && !empty($_GET['edit-genre']) ? "Update Genre: " . $edit_genre->name : "Add Genre";
                                        @endphp
                                    </div>
                                    <div class="card-body">
                                        @if(isset($_GET['edit-genre']) && !empty($_GET['edit-genre']))
                                            <form action="{{ route('update.genre', ['genre' => $edit_genre->id]) }}" method="POST">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="_method" value="put">
                                                <div class="input-group">
                                                    <input type="text" name="genre_name" placeholder="Genre name" value="{{ $edit_genre->name }}" class="form-control" required>
                                                    <button type="submit" class="btn btn-warning" style="color: white;"><i class="fas fa-edit"></i></button>
                                                </div><br>
                                            </form>
                                        @else
                                            <form action="{{ route('insert.genre') }}" method="POST">
                                                {{ csrf_field() }}
                                                <div class="input-group">
                                                    <input type="text" name="genre_name" placeholder="Genre name" class="form-control" required>
                                                    <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i></button>
                                                </div><br>
                                            </form>
                                        @endif
                                        <ul style="direction: rtl;">
                                            @foreach($all_genres as $genre)
                                                <li>{{ $genre->name }} | <a href="{{ route('home') }}?edit-genre={{ $genre->id }}" style="text-decoration: none;" class="text-warning">ویرایش</a> | <span onclick="if(confirm('آیا از حذف این ژانر مطمئن هستید؟')){document.getElementById('delete_genre_{{ $genre->id }}').submit();}" style="cursor: pointer;" class="text-danger">حذف</span></li>
                                                <form action="{{ route('delete.record', ['type' => 'genre', 'id' => $genre->id]) }}" method="POST" id="delete_genre_{{ $genre->id }}">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="_method" value="delete">
                                                </form>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-plus"></i>
                                        Add video
                                    </div>
                                    <div class="card-body">
                                        @if(isset($_GET['edit-video']) && !empty($_GET['edit-video']))
                                            <form action="{{ route('update.video', ['video' => $edit_video->id]) }}" method="POST">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="_method" value="put">
                                                <input type="text" name="name" placeholder="Name" value="@if(empty(old('name'))){{ $edit_video->name }}@else{{ old('name') }}@endif" class="form-control">
                                                @if($errors->has('name'))
                                                    <span class="text-danger">{{ $errors->first('name') }}</span><br>
                                                @endif
                                                <br>
                                                <input type="text" name="genre" id="genre" placeholder="Genres" value="@if(empty(old('genre'))){{ $edit_video->genres }}@else{{ old('genre') }}@endif" class="form-control">
                                                @if($errors->has('genre'))
                                                    <span class="text-danger">{{ $errors->first('genre') }}</span>
                                                @else
                                                    <span class="text-primary">لطفا این فیلد را پر نکنید و مقادیر آن را از انتخابگر زیر انتخاب کنید</span>
                                                @endif
                                                <br><br>
                                                <select class="form-control" onchange="if(this.value != '...'){if(document.getElementById('genre').value == ''){document.getElementById('genre').value = this.value;}else{document.getElementById('genre').value += ', ' + this.value;}}">
                                                    @foreach($genres_array as $genre_name)
                                                        <option>{{ $genre_name }}</option>
                                                    @endforeach
                                                </select><br>
                                                <button type="submit" class="btn btn-warning" style="color: white;"><i class="fas fa-edit"></i></button><br><br>
                                            </form>
                                        @else
                                            <form action="{{ route('insert.video') }}" method="POST">
                                                {{ csrf_field() }}
                                                <input type="text" name="name" placeholder="Name" value="{{ old('name') }}" class="form-control">
                                                @if($errors->has('name'))
                                                    <span class="text-danger">{{ $errors->first('name') }}</span><br>
                                                @endif
                                                <br>
                                                <input type="text" name="genre" id="genre" placeholder="Genres" value="{{ old('genre') }}" class="form-control">
                                                @if($errors->has('genre'))
                                                    <span class="text-danger">{{ $errors->first('genre') }}</span>
                                                @else
                                                    <span class="text-primary">لطفا این فیلد را پر نکنید و مقادیر آن را از انتخابگر زیر انتخاب کنید</span>
                                                @endif
                                                <br><br>
                                                <select class="form-control" onchange="if(this.value != '...'){if(document.getElementById('genre').value == ''){document.getElementById('genre').value = this.value;}else{document.getElementById('genre').value += ', ' + this.value;}}">
                                                    <option>...</option>
                                                    @foreach($genres_array as $genre_name)
                                                        <option>{{ $genre_name }}</option>
                                                    @endforeach
                                                </select><br>
                                                <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i></button><br><br>
                                            </form>
                                        @endif
                                        <ul style="direction: rtl;">
                                            @foreach($videos_array as $video)
                                                <li>{{ $video->name }} ==> {{ $video->genres }} | <a href="{{ route('home') }}?edit-video={{ $video->id }}" style="text-decoration: none;" class="text-warning">ویرایش</a> | <span onclick="if(confirm('آیا از حذف این ویدیو مطمئن هستید؟')){document.getElementById('delete_video_{{ $video->id }}').submit();}" style="cursor: pointer" class="text-danger">حذف</span></li>
                                                <form action="{{ route('delete.record', ['type' => 'video', 'id' => $video->id]) }}" method="POST" id="delete_video_{{ $video->id }}">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="_method" value="delete">
                                                </form>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="{{ asset('/js/scripts.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    </body>
</html>
