@extends('admin.layouts.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Ссылка</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Админ-панель</li>
                        <li class="breadcrumb-item active">Ссылки</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Список ссылок</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if (count($links))
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Короткая ссылка</th>
                            @if( Auth::user()->is_admin)
                            <th>Длинная ссылка</th>
                            @endif
                            <th>TikTok</th>
                            <th>Страна</th>
                            <th>ClickID</th>
                            <th>Пользователь</th>
                            <th style="width: 40px">Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($links as $link)
                            <tr>
                                <td>{{ $link->id }}</td>
                                <td><a href="{{ $link->domain->domain}}/{{ $link->link_short }}" target="_blank">{{ $link->domain->domain}}/{{ $link->link_short }}</a></td>
                                @if( Auth::user()->is_admin)
                                <td><a href="{{ $link->link_full }}" target="_blank">{{ $link->link_full }}</a></td>
                                @endif
                                <td><a href="https://www.tiktok.com/{{ $link->tiktok }}">tiktok.com/{{ $link->tiktok }}</a></td>
                                <td>{{ $link->country }}</td>
                                <td>{{ $link->click_id }}</td>
                                <td>{{ $link->user->name }}</td>
                                <td>
                                    <a href="{{ route('admin.links.edit', $link->id) }}" class="btn btn-info btn-sm float-left mr-1"><i class="fas fa-pencil-alt"></i></a>
                                    <form action="{{ route('admin.links.destroy', $link->id) }}" method="POST" class="float-left">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Подтвердите удаление')"><i class="fas fa-trash-alt"></i></button></form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <p>Ссылок пока нет.</p>
                @endif

            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                {{ $links->links('vendor.pagination.bootstrap-4') }}
            </div>
            <!-- /.card-footer-->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
@endsection
