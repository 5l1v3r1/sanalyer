<div class="content-comments__item__avatar">
    @if($comment->user_id == null)
        <img src="{{ asset('rk_content/images/guest.png') }}">
    @else
        <img src="{{ $comment->user->photo }}">
    @endif
</div>
<div class="content-comments__item__content">
    <div itemtype="http://schema.org/Comment" itemscope="itemscope" itemprop="comment">
        <div class="content-comments__author">
                            <span class="content-comments__item__author__name"
                                  itemprop="author">{{ $comment->user_id ? $comment->user->firstname : "Misafir" }}</span>
            <span class="content-comments__item__author__date" itemprop="dateCreated"
                  content="{{ $comment->created_at->format(DateTime::ATOM) }}"
                  datetime="{{ $comment->created_at->format(DateTime::ATOM) }}">{{ $comment->created_at->diffForHumans() }}</span>
        </div>
        <div class="content-comments__item__message" itemprop="text">
            {{ $comment->content }}
        </div>
        <div class="content-comments__item__actions">
            <div class="content-comments__item__button content-comments__item__button--reply"
                 data-id="{{ $comment->id }}">
                <span class="ripple">Yanıtla</span>
            </div>
        </div>
    </div>
    @if($userCheck)
        <div class="content-comments__form clearfix content-comments__form--sub" id="form-thread-{{ $comment->id }}"
             data-login="yes">
            <div class="content-comments__item__avatar"><img src="{{ $pp }}"></div>

            <form class="content-comments__form__element" method="post">
                <div class="content-comments__form__row clearfix">
                    <textarea class="content-comments__form__comment" name="content"
                              placeholder="Yorumunuzu yazın..."></textarea>
                </div>
                <div class="content-comments__form__row clearfix">
                    <div class="content-comments__form__max-char">
                        Yorumunuz minimum <strong>10</strong> karakter olmalıdır.(<span
                                class="max-char">0</span>)
                    </div>
                    <div class="content-comments__form__notice">
                    </div>
                    <input type="hidden" name="thread_id" value="{{ $thread }}">
                    <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                    <button class="content-comments__form__send" type="submit">YORUMU GÖNDER</button>
                </div>
            </form>
        </div>
    @else
        <div class="content-comments__form clearfix content-comments__form--sub" id="form-thread-{{ $comment->id }}"
             data-login="no">
            <div class="content-comments__item__avatar content-comments__item__avatar--guest"></div>
            <form class="content-comments__form__element" method="post">
                <div class="content-comments__form__row clearfix">
                        <textarea class="content-comments__form__comment" name="content"
                                  placeholder="Yorumunuzu yazın..."></textarea>
                </div>
                <div class="content-comments__form__row clearfix">
                    <div class="content-comments__form__max-char">
                        Yorumunuz minimum <strong>10</strong> karakter olmalıdır.(<span
                                class="max-char">0</span>)
                    </div>
                    <div class="content-comments__form__notice">
                        <div class="content-comments__form__guest">
                            <b>Ziyaretçi</b> olarak yorum yapıyorsun, dilersen <span
                                    class="content-comments__form__login modal-button"
                                    data-modal="modal-signin">giriş yap</span>.
                        </div>
                    </div>
                    <input type="hidden" name="thread_id" value="{{ $thread }}">
                    <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                    <button class="content-comments__form__send" type="submit">YORUMU GÖNDER</button>
                </div>
            </form>
        </div>
@endif
