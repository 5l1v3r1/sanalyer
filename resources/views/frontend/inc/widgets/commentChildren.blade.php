@foreach($comment->children()->get() as $child)
    <div class="content-comments__item__reply">
        <div class="content-comments__item clearfix" id="comment-thread-301262">
            <div itemtype="http://schema.org/Comment" itemscope="itemscope" itemprop="comment">
                <div class="content-comments__item__avatar">
                    @if($child->user_id == null)
                        <img src="{{ asset('rk_content/images/guest.png') }}">
                    @else
                        <img src="{{ Image::url(asset($child->user->photo ? '/rk_content/images/user-profile/' . $child->user->photo : '/rk_content/images/noavatar.png'), 48, 48, array('crop')) }}">
                    @endif
                </div>
                <div class="content-comments__item__content">
                    <div class="content-comments__author">
                                        <span class="content-comments__item__author__name"
                                              itemprop="author">{{ $child->user_id ? $child->user->firstname  : "Misafir" }}</span>
                        <span class="content-comments__item__author__date" itemprop="dateCreated"
                              content="{{ $child->created_at->format(DateTime::ATOM) }}"
                              datetime="{{ $child->created_at->format(DateTime::ATOM) }}">{{ $child->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="content-comments__item__message" itemprop="text">
                        {{ $child->content }}
                    </div>
                    <div class="content-comments__item__actions">
                    </div>
                </div>
            </div>
        </div>
        <div class="content-comments__item__reply-list"></div>
    </div>
@endforeach