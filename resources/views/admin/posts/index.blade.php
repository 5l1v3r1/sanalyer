@extends('layouts.admin')
@section('title') İçerikler - @endsection
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">İçerikler</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin::index') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">İçerikler</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Başlık</th>
                        <th scope="col">Kategori</th>
                        <th scope="col">Onay</th>
                        <th scope="col">İşlemler</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($posts as $post)
                            @php
                                if($post->status == 1){
                                    $status = "Onaylı";
                                }else{
                                    $status = "Onay Bekliyor";
                                }
                            @endphp
                            <tr>
                                <th scope="row">{{ $post->id }}</th>
                                <td>{{ $post->title }}</td>
                                <td>{{ $post->category()->first()->title }}</td>
                                <td>{{ $status }}</td>
                                <td>
                                    <a href="#" class="btn btn-success">Düzenle</a>
                                    <a href="#" class="btn btn-danger">Sil</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $posts->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </section>
@endsection