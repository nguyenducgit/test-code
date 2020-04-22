<?php
//Html
<a href="{{ url('/thanh-vien/load-more') }}" class="d-none d-md-block load_more" data-total="1000" data-container=".list-member-content">
    <span>Xem thêm thành viên</span>
</a>

//JS
$('.load_more').click(function(e){
    e.preventDefault();
    console.log('begin load');

    var _this = $(this),
        href = _this.attr('href'),
        // total = _this.data('total'),
        container = $(_this.data('container')),
        page = _this.data('page');
    console.log

    page = typeof page == 'undefined' ? 2 : page;
    console.log('Url: ' + href + '?page=' + page);
    $.ajax({
        url: href,
        data: {page: page},
        method: 'GET',
        dataType: 'json',
        beforeSend: function(){

        },
        success: function(res){
            page++;
            if(res.html == ''){
                console.log('Da load het data');
                _this.remove();
                return false;
            }
            _this.data('page', page);
            container.append(res.html);

            console.log('end load');
        },
        complete: function(){
        //
        },
        error: function(res) {

        }
    })
})

//PHP
Route::get('/load-more', 'MemberController@loadMore');
public function loadMore(Request $request)
    {
        $page = $request->get('page', 1);
        $limit = 20;
        $offset = ($page - 1) * $limit;
        $members = User::orderBy('total_sing_songs', 'desc')->take((int)$limit)
            ->skip($offset)
            ->get();
        $total = $members->count();

        if ($request->ajax()) {
            $html = view('member.member_items', compact('members'))->render();
            return response()->json(['html' => $html]);
        }

//        return view('song.tophit', ['tophit' => $data, 'total' => $total]);
    }