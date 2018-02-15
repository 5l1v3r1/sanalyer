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
                            <th>Durum</th>
                            <th>Yayın Durumu</th>
                            <th>İşlem</th>
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
        $(document).ready(function () {
            var status = null;
            var text = null;
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth()+1; //January is 0!
            var yyyy = today.getFullYear();
            var hours = today.getHours();
            var minutes = today.getMinutes();
            var seconds = today.getSeconds();

            if(dd<10) {
                dd = '0'+dd
            }

            if(mm<10) {
                mm = '0'+mm
            }
            var totime = yyyy + "-" + mm + "-" + dd + " " + hours + ":" + minutes + ":" + seconds;
            $('#threadsTable').DataTable({
                order: [[0, "desc"]],
                processing: true,
                serverSide: true,
                ajax: '{{ route('ajax::threads') }}',
                "columns": [
                    {data: 'id', name: 'id', sClass: 'font-weight-bold'},
                    {data: 'title', name: 'title'},
                    {
                        data: null, render: function (data) {
                            if (data.status == 0){
                                status = 'Onay Bekliyor';
                            }else {
                                status = 'Onaylı';
                            }
                            return status;
                        }
                    },
                    {
                        data: null, render: function (data) {

                            if (data.updated_at < totime){
                                text = 'Yayında';
                            }else {
                                text = 'Zamanlanmış';
                            }
                            return text;
                        }
                    },
                    {
                        data: null, render: function (data) {
                            return '<a href="{{ route('edit_post',null) }}/' + data.id + '" class="editor_edit">Düzenle</a> ' +
                                '/' +
                                ' <a href="{{ route('delete_post',null) }}/' + data.id + '" class="editor_remove" onclick="return confirm(\'Silmek istediğinizden emin misiniz?\')">Sil</a>';
                        }
                    }
                ],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Turkish.json"
                },
                keys: true
            });
        });
    </script>
    <script type="text/javascript"
            src="{{ asset('assets/default/js-packages/jquery.datetimepicker.full.min.js') }}">
    </script>
@endsection