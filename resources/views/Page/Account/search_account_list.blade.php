<!---------------------此頁面已無使用-------------------- -->
<!---------------------此頁面已無使用-------------------- -->
<!---------------------此頁面已無使用-------------------- -->
<div id = "account_table" style="text-align: center">
    <table  class="table table-sm">
        <thead>
            <tr >
                <th>姓名</th>
                <th>帳號名稱</th>
                <th>部門</th>
            </tr>
        </thead>
        <tbody>
            @foreach($searchs as $search)
            <tr>
                <td>{{$search->name}}</td>
                <td>{{$search->username}}</td>
                <td>{{$search->department->dname}}</td>
            </tr>
            @endforeach
          
        </tbody>
        
    </table>
    {{ $searchs->links() }}
    </div>
    <td>{{$message}}</td>