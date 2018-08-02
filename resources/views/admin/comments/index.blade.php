@extends('layouts.admin')
@php
    if($status == 1){
        $title = "Onaylı Yorumlar";
    }else{
        $title = "Onay Bekleyen Yorumlar";
    }
@endphp
@section('title') {{ $title }} - @endsection
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">{{ $title }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin::index') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
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
                        <th scope="col">İçerik</th>
                        <th scope="col" width="500px">Yorum</th>
                        <th scope="col" width="200px">İşlemler</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($comments as $comment)
                        <tr>
                            <th scope="row">{{ $comment->id }}</th>
                            <td>
                                <a href="{{ route('show_post',$comment->posts->full_url) }}" target="_blank">
                                    {{ $comment->posts->title }}
                                </a>
                            </td>
                            <td>{{ $comment->content }}</td>
                            <td>
                                @if($status == 0)
                                    <a href="{{ route('admin::comment_approve', $comment->id) }}" class="btn btn-success">Onayla</a>
                                @endif
                                <a href="{{ route('admin::comment_delete', $comment->id) }}" class="btn btn-danger">Sil</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $comments->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </section>
@endsection