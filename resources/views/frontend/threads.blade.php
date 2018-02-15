@extends('layouts.master')

@section('css')
    <link href="{{ asset('/assets/default/css/other-pages.css?v=2.3.4') }}" rel="stylesheet" media="all"/>
    <link rel="stylesheet" href="{{ asset('assets/default/js-packages/jquery.datetimepicker.min.css') }}">


@endsection

@section('content')
    <div class="global-container container">
        <div class="content">
            <div class="content-title">
                <h1>İçeriklerim</h1>
            </div>
            <div class="content-body">
                <div class="content-body__detail">
                    <table id="threadsTable" class="display" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Başlık</th>
                            <th>Tarih</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="info-sidebar hide-mobile">
            <ul>
                <li class="info-sidebar__item {{ url()->full() == route('new_post') ? "is-active" : "" }}">
                    <a href="{{ route('new_post') }}">Yazı Ekle</a>
                </li>
                <li class="info-sidebar__item {{ url()->full() == route('new_video') ? "is-active" : "" }}">
                    <a href="{{ route('new_video') }}">Video Ekle</a>
                </li>
                <li class="info-sidebar__item {{ url()->full() == route('threads') ? "is-active" : "" }}">
                    <a href="{{ route('threads') }}">İçeriklerim</a>
                </li>
            </ul>
        </div>
        <div class="sidebar hide-mobile">
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#threadsTable').DataTable( {
                order: [[0, "desc"]],
                processing: true,
                serverSide: true,
                ajax: '{{ route('ajax::threads') }}',
                "columns": [
                    {data: 'id', name: 'id', sClass: 'font-weight-bold'},
                    {data: 'title', name: 'title'},
                    {data: 'updated_at', name: 'updated_at'}
                ],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Turkish.json"
                },
                keys: true
            } );
        } );
    </script>
    <script type="text/javascript"
            src="{{ asset('assets/default/js-packages/jquery.datetimepicker.full.min.js') }}">
    </script>
@endsection