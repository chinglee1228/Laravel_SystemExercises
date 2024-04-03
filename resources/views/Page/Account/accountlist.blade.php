<!---------------------此頁面已無使用-------------------- -->
<!---------------------此頁面已無使用-------------------- -->
<!---------------------此頁面已無使用-------------------- -->
@extends('layouts.index')
@section('title','帳號列表')
@section('main')
<button class='m-1' type ="button" onclick="location.href='/'">返回</button>
@if(session('success'))
<div id="auto" class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('success') }}
  </div>
@endif
<div class='m-4'style="text-align: center"> 

    <div class="m-2">
    依姓名搜尋：<input type="text" id="search_name" placeholder="Search by Name...">
    部門：<select id="search_did">
        <option value="">請選擇部門</option>

    @foreach($departments as $department)
        <option value="{{$department->did}}">{{$department->dname}}</option>
    @endforeach

    </select>
    <!--<button  class="btn btn-outline-secondary" id="search_button">送出</button> -->
    </div>
    <div id="account_table">
    <table class="table table-sm">
        <thead>
            <tr>
                <th>姓名</th>
                <th>帳號名稱</th>
                <th>部門</th>
            </tr>
        </thead>
        <tbody >
            @foreach($accounts as $account)
            <tr>
                <td>{{$account->name}}</td>
                <td>{{$account->username}}</td>
                <td>{{$account->department->dname}}</td>
            </tr>
            @endforeach
          
        </tbody>
        
    </table>
    {{ $accounts->links() }}
    </div>
            

</div>
<script>
    $('#search_button').on('click', function() {
        updateAccountList();
    });
    $('#search_did').on('change', function() {
        updateAccountList();
    });
    $('#search_name').on('keyup', function() {
        updateAccountList();
    });
    function updateAccountList() {
        var did = $('#search_did').val();
        var name = $('#search_name').val();
    
        $.ajax({
            url: "{{ route('searchlist') }}", // 需要更新的路由
            type: 'GET',
            data: { did: did, name: name },
            success: function(data) {
                // 確保僅更新 table 的 tbody 部分
                $('#account_table').html(data);
            }
        });
    }
    </script>
    
@endsection



