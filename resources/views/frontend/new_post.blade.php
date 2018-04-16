@extends('layouts.master')

@section('css')
    <link href="{{ asset('/assets/default/css/other-pages.css?v=2.3.4') }}" rel="stylesheet" media="all"/>
    <link rel="stylesheet" href="{{ asset('assets/default/js-packages/jquery.datetimepicker.min.css') }}">


@endsection

@section('content')
    <div class="global-container container">
        <div class="content">
            <div class="content-title">
                @if($type==0)
                    <h1>İçerik Ekle</h1>
                @elseif($type == 1)
                    <h1>Video Ekle</h1>
                @endif
            </div>
            <div class="content-body">
                <div class="content-body__detail">
                    @if (count($errors) > 0)
                    @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach
                    @endif
                    <form name="sanalyer_client_bundle_contact_type" method="post" class="material" enctype="multipart/form-data">
                        <input type="hidden" value="{{ csrf_token() }}" name="_token">
                        <div>
                            <input type="text" name="title" required="required"
                                   placeholder="Başlık" value="{{ old('title') }}"/>
                        </div>
                        <div>
                            <input type="text" value="{{ old('date') }}" class="material" name="date" placeholder="Tarih" id="datetimepicker1"/>
                        </div>
                        <div>
                            <input type="file" name="image"/>
                        </div>
                        <div>
                            <textarea name="short_desc" required="required"
                                      placeholder="Kısa Açıklama">{{ old('short_desc') }}</textarea>
                        </div>
                        <div>
                            <select name="category" placeholder="Kategori">
                                @foreach($category as $item)
                                    @if($item->parent_id == 0)
                                        <option value="{{ $item->id }}" {{ old("category") == $item->id ? "selected":"" }}>{{ $item->title }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <textarea name="content_full" required="required" class="ckeditor " id="editor-ckeditor">{!! old('content_full') !!}</textarea>
                        </div>

                        <div>
                            <select name="location" placeholder="Nerede Gözüksün ?">
                                <option value="0" {{ old('location') == 0 ? 'selected': '' }}>Aşağıda Kalsın</option>
                                <option value="1" {{ old('location') == 1 ? 'selected': '' }}>Manşet</option>
                                <option value="2" {{ old('location') == 2 ? 'selected': '' }}>Manşet Yanı</option>
                                <option value="5" {{ old('location') == 5 ? 'selected': '' }}>Gizli</option>
                            </select>
                        </div>
                        <div>
                            <textarea name="tag" required="required"
                                      placeholder="Tag (virgül ile ayırınız)">{!! old('tag') !!}</textarea>
                        </div>
                        @if($type == 1)
                            <div>
                            <textarea name="video"
                                      placeholder="Youtube Video URL">{!! old('video') !!}</textarea>
                            </div>
                        @endif


                        <div>
                            <button type="submit" name="save"
                                    class="material-button ripple material-shadow--2dp" style="margin-top: 10px">Gönder
                            </button>
                        </div>

                    </form>
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
    <script type="text/javascript"
            src="{{ asset('assets/default/js-packages/jquery.datetimepicker.full.min.js') }}">
    </script>
    <script type="text/javascript">
        $('#datetimepicker1').datetimepicker({
            value:new Date(),
            datepicker:true,
            format:'d-m-Y H:i:s',
            step:5
        });
    </script>
@endsection