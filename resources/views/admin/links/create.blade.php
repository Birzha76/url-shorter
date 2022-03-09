@extends('admin.layouts.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Ссылки</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Админ-панель</li>
                        <li class="breadcrumb-item active">Добавление ссылки</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Добавление ссылки</h3>

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
                            <form action="{{ route('admin.links.store') }}" method="POST">
                                @csrf
                                <div class="card-body">
                                    @if( Auth::user()->is_admin)
                                    <div class="form-group">
                                        <label for="link_full">Сокращаемая ссылка</label>
                                        <input type="text" class="form-control @error('link_full') is-invalid @enderror" id="link_full" name="link_full" placeholder="Введите сокращаемую ссылку">
                                    </div>
                                    @endif
                                    <div class="form-group">
                                        <label for="tiktok">Никнейм TikTok</label>
                                        <input type="text" class="form-control @error('tiktok') is-invalid @enderror" id="tiktok" name="tiktok" placeholder="Введите никнейм">
                                    </div>
                                    <div class="form-group">
                                        <label for="domain_id">Домен</label>
                                        <select class="form-control @error('domain_id') is-invalid @enderror" name="domain_id" id="domain_id">
                                            <option>Выберите домен</option>
                                            @foreach ($domains as $domain)
                                                <option value="{{ $domain->id }}">{{ $domain->domain }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="country">Страна</label>
                                        <select class="form-control @error('country') is-invalid @enderror" name="country" id="country">
                                            <option>Выберите страну</option>
                                            <option value="Germany">Germany</option>
                                            <option value="Austria">Austria</option>
                                            <option value="Switzerland">Switzerland</option>
                                            <option value="Belgium">Belgium</option>
                                            <option value="New Zealand">New Zealand</option>
                                            <option value="Australia">Australia</option>
                                            <option value="France">France</option>
                                            <option value="Netherlands">Netherlands</option>
                                            <option value="Spain">Spain</option>
                                            <option value="United State">United State</option>
                                            <option value="Canada">Canada</option>
                                            <option value="Portugal">Portugal</option>
                                            <option value="Sweden">Sweden</option>
                                            <option value="Italy">Italy</option>
                                            <option value="Czech Republic">Czech Republic</option>
                                            <option value="Poland">Poland</option>
                                            <option value="Greece">Greece</option>
                                            <option value="Croatia">Croatia</option>
                                            <option value="Latvia">Latvia</option>
                                            <option value="United Kingdom">United Kingdom</option>
                                            <option value="Denmark">Denmark</option>
                                            <option value="Norway">Norway</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Добавить</button>
                                </div>
                            </form>
                            @if (session()->has('link'))
                                <div class="mt-3">
                                    <div class="form-group">
                                        <label for="tiktok">Ваша ссылка:</label>
                                        <input type="text" class="form-control" id="generated_link" name="generated_link" value="{{ session('link') }}">
                                    </div>
                                </div>
                            @endif
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">

                        </div>
                        <!-- /.card-footer-->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>

    </section>
    <!-- /.content -->
@endsection
