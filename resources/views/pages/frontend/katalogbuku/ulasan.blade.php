<div class="post-comments comments-area style-1 clearfix">
    <h4 class="comments-title">Ulasan Pembaca</h4>
    <div class="secUlasan">
        @if($ulasan->count() == 0)
            <span>Belum ada ulasan</span>
        @else
        @foreach($ulasan as $item)
            <div id="comment">
                <ol class="comment-list">
                    <li class="comment even thread-even depth-1 comment" id="comment-2">
                        <div class="comment-body">
                        <div class="comment-author vcard">
                                <img src="{{ $item->profilAnggota->user->foto ? asset('storage/' . $item->profilAnggota->user->foto->file_path) : Storage::url('user/default.png') }}" alt="" class="avatar"/>
                                <div class="row">
                                    <div class="col">
                                        <cite class="fn">{{$item->profilAnggota->nama_lengkap}}</cite>
                                    </div>
                                    <div class="col text-end">
                                        <small class="text-secondary">{{$item->waktuYangLalu()}}</small>
                                    </div>
                                </div>
                        </div>
                        <div class="comment-content dlab-page-text">
                            <p>{{$item->ulasan}}</p>
                        </div>
                    </div>
                    </li>

                </ol>
            </div>
        @endforeach
        @endif
    </div>
    @if($cek_pinjam > 0 && $status_ulasan === 0 && auth()->user() && auth()->user()->profilAnggota)
    <div class="default-form comment-respond style-1" id="respond">
        <h4 class="comment-reply-title" id="reply-title">Berikan Ulasan <small> <a rel="nofollow" id="cancel-comment-reply-link" href="javascript:void(0)" style="display:none;">Cancel reply</a> </small></h4>
        <div class="clearfix">
            <form method="post" id="comments_form" class="comment-form" novalidate>
            <p class="comment-form-comment"><textarea id="ulasan" placeholder="Tulis ulasan" class="form-control4" name="comment" cols="45" rows="3" required="required"></textarea></p>
            <p class="col-md-12 col-sm-12 col-xs-12 form-submit">
                <button  id="kirimUlasan" type="button" class="submit btn btn-primary filled">
                Kirim <i class="fa fa-angle-right m-l10"></i>
                </button>
            </p>
            </form>
        </div>
    </div>
  @endif
</div>
