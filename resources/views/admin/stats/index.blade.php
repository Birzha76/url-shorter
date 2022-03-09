@extends('admin.layouts.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Статистика</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Админ-панель</li>
                        <li class="breadcrumb-item active">Статистика</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        @if(!empty($ourStats))
            <!-- Default box -->
                <div class="card">
                    <div class="card-header">
                        <p>Общая статистика за период:</p>
                        <div class="options">
                            <div class="leads"><b>Количество аккаунтов</b>: {{ $ourStats['links_count'] }}</div>
                            <div class="leads"><b>Лиды</b>: {{ $ourStats['leads_count'] }}</div>
                            <div class="profit"><b>Профит</b>: ${{ $ourStats['revenue'] }}</div>
                            <div class="clicks"><b>Клики</b>: {{ $ourStats['visits_count'] }}</div>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
        @endif

    @if (count($linksWithVisits))
        @foreach($linksWithVisits as $date => $data)
        <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $date }}</h3>
                </div>
                <div class="card-footer">
                    <div class="options">
                        <div class="leads"><b>Лиды</b>: {{ $linksInfo[$date]['leads_count'] }}</div>
                        <div class="profit"><b>Профит</b>: ${{ $linksInfo[$date]['leads_sum'] }}</div>
                        <div class="clicks"><b>Клики</b>: {{ $linksInfo[$date]['count'] }}</div>
                        <div class="last-click"><b>Последний клик</b>: {{ $linksInfo[$date]['last_visit'] }}</div>
                    </div>
                </div>
                <!-- /.card-footer-->
                <div class="card-body">

                    <style>
                        .link__box {
                            margin-top: 15px;
                            display: flex;
                            flex-direction: column;
                            background-color: #fafaf4;
                            border-radius: 15px;
                            padding: 25px;
                        }

                        .link__box:first-child {
                            margin-top: 0;
                        }

                        small {
                            display: block;
                        }

                        .shortlink {
                            margin-top: 15px;
                            margin-bottom: 0;
                        }

                        .options {
                            margin-top: 15px;
                            display: flex;
                            justify-content: space-between;
                        }

                        .card-footer .options {
                            padding: 0 15px;
                        }
                    </style>

                    @foreach($data as $id => $link)
                    <div class="link__box">
                        @if( Auth::user()->is_admin)
                        <div class="link">
                            <small>URL:</small>
                            <a href="{{ $link['link_full'] }}">{{ $link['link_full'] }}</a>
                        </div>
                        @endif
                        <p class="shortlink">
                            <small>Short URL:</small>
                            <a href="{{ $link['link_short'] }}">{{ $link['link_short'] }}</a>
                        </p>
                        <p class="shortlink">
                            <small>Tiktok:</small>
                            <a href="https://www.tiktok.com/{{ $link['tiktok'] }}" target="_blank">tiktok.com/{{ $link['tiktok'] }}</a> {{ $link['country'] }}
                        </p>
                        <div class="options">
                            <div class="leads">Лиды: {{ $link['leads_count'] }}</div>
                            <div class="profit">Профит: ${{ $link['leads_sum'] }}</div>
                            <div class="clicks">Клики: {{ $link['count'] }}</div>
                            <div class="last-click">Последний клик: {{ $link['last_visit'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        @endforeach
    @else
        <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Статистика по дням</h3>
                </div>
                <div class="card-body">
                    <p>Статистики по переходам пока нет.</p>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        @endif

    </section>
    <!-- /.content -->
@endsection
