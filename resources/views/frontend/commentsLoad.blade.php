@foreach($comments as $comment)
    <div class="content-comments__item clearfix" id="comment-thread-{{ $thread }}">
        @include('frontend.inc.widgets.comment')
        @if($comment->children->count() > 0)
            @include('frontend.inc.widgets.commentChildren')
        @endif
    </div>
    </div>
    <script>
        var $comments = $($('body').find('[data-thread="{{ $thread }}"]')),
            $more = $comments.find('.content-comments__load-more');
        if (!$more.is(':visible')) {
            $more.show();
        }
    </script>
@endforeach