<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>پنل کاربران</title>
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
                            <a class="nav-link" href="{{ route('users.dashboard') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                داشبورد
                            </a>
                            <a class="nav-link" href="{{ route('edit.votes') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-edit"></i></div>
                                ویرایش رای ها
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
                        <h1 class="mt-4">Edit votes</h1><br>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                رای های شما
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>Counter</th>
                                            <th>Name</th>
                                            <th>Genres</th>
                                            <th>عملیات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $counter = 0; @endphp
                                        @foreach($votes as $vote)
                                            <tr>
                                                <td>@php echo ++$counter; @endphp</td>
                                                <td>{{ $vote->video->name }}</td>
                                                <td>{{ $vote->video->genres }}</td>
                                                <td>
                                                    <div class="d-flex">
                                                        <form action="{{ route('update.vote', ['vote' => $vote->id]) }}" method="POST">
                                                            {{ csrf_field() }}
                                                            <input type="hidden" name="_method" value="put">
                                                            <input type="number" name="new_vote" placeholder="رای شما" value="{{ $vote->score }}" min="1" max="10" required>
                                                        </form>
                                                        <b style="margin-right: 3px; margin-left: 3px;">|</b>
                                                        <button onclick="if(confirm('آیا از حذف این رای خود مطمئن هستید؟')){document.getElementById('delete_{{ $vote->id }}').submit();}" class="btn btn-sm btn-danger">حذف</button>
                                                        <form action="{{ route('delete.vote', ['vote' => $vote->id]) }}" method="POST" id="delete_{{ $vote->id }}">
                                                            {{ csrf_field() }}
                                                            <input type="hidden" name="_method" value="delete">
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
        <script src="{{ asset('/js/datatables-simple-demo.js') }}"></script>
    </body>
</html>
