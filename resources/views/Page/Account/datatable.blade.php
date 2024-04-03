@extends('layouts.index')
@section('title','帳號列表')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css" type="text/css"/>

<style>
  body {
    font-family: 'Nunito';
  }
  .table {
    vertical-align: middle;
    text-align: center;
  }
  th {
    text-align: center !important;
  }
  .select {
  margin: 15px;
  width: 200px;
  height: 2em;
  padding: 7px;
  position: relative;
  border-radius: 5px;
  }
  .div{
  left: 100%;
  }
  .Rbutton {
  background-color: #04AA6D;
  transition-duration: 0.4s;
}

.Rbutton:hover {
  background-color: #29eea6;
  color: white;
}
 .Dbutton {
  background-color: #cf0303; /* Green */
  color: white;
}
.Dbutton:hover {
  background-color: #f96161;
  transition-duration: 0.4s;
  color: white;
}

</style>
@endsection
<!--main-->
@section('main')
<button type ="button" onclick="location.href='{{route('root')}}'">主頁</button>
<!--通知訊息-->
@if(session('success'))
<div id="auto" class="alert alert-danger alert-dismissible fade show m-2" role="alert">
  {{ session('success') }}
</div>
@endif

<body class="antialiased" >
  <div>
    部門搜尋：<select class = "select" id="selectdid">
    <option value="">選擇部門...</option>
    @foreach($departments as $department)
      <option value="{{ $department->dname }}">{{ $department->dname }}</option>
    @endforeach
  </select>

  日期搜尋：
  <input class = "select date" type = "date" id="minDate" ></input>至
  <input class = "select date" type = "date" id="maxDate" ></input>


<table class="table table-sm display" id="account_list">
    <thead class="table-light" style = "text-align: center">
    <tr>
      <th>id</th>
      <th>姓名</th>
      <th>帳號</th>
      <th>部門</th>
      <th>日期</th>
      <th>操作</th>
    </tr>
    </thead>
  </table>
  </body>

@endsection

@section('script')
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
  var editUrl = "{{ route('Account.edit', ['id' => 'id']) }}";
  var delUrl = "{{ route('Account.destroy', ['id' => 'id']) }}";
 </script>
<script type="text/javascript">
  $(document).ready(function() {
      // 定時5秒關閉
      setTimeout(function() {
          $('#auto').alert('close');
      }, 3000); // 时间设置为5秒
  });
</script>


<script>
  $(document).ready(function (){

    $('.datepicker').datepicker({
          autoclose: true,
          todayHighlight: true,
          format: 'yyyy-mm-dd'
      });

    //初始化datatable
    let datatable = $('#account_list').DataTable({
      //設定
      searching: true, //搜尋功能
      paging: true, //資料筆數設定 分頁功能
      autoWidth: false,
      processing: true, //處理中提示

        ajax:{
        url: "{{route('getData')}}",
        type: "GET"
      },
      columnDefs: [
        {//第一列索引為0開始
          "targets": 0, // id 列的索引是 0（第一列）
          "visible": false, // 將此列設定為隱藏
        },
       {// 最後一列設定
        "targets": -1,
          "data": null, // 此處不綁定特定數據
          "defaultContent": "<button class='edit-btn Rbutton'>修改</button>"+
          "<button class='del-btn Dbutton m-2'>刪除</button>" // 按鈕的 HTML
        },
        /*
        {
          targets: -1, // 假設最後一列是操作列

          render: function(data, type, row, meta){
            //var username = $('#account_list').DataTable().row($(this).parents('tr')).data()['username'];
            var id = '1'; // 假設第一列包含 userID
            console.log(row[0]);
            url = editUrl.replace('id',id);
            return '<a href="' + url + '">編輯</a>'; // 或者創建一個按鈕而不是連結
          }
        },
        */


      ],
      language:{//自訂表格功能
        "sZeroRecords": "查無資料",
        "sEmptyTable": "無資料",
        "sSearch": "搜尋:",
        "sInfo": "從 _START_ 到 _END_ 筆紀錄,總共搜尋到 _TOTAL_ 筆",
        "sInfoFiltered":"(從全部 _MAX_ 紀錄中篩選)",
        "sProcessing" : "處理中...",
        "lengthMenu": "每頁顯示 _MENU_ 筆",
        "sLoadingRecords": "載入中...",
        "oPaginate": {
              "sFirst": "首頁",
              "sPrevious": "上頁",
              "sNext": "下頁",
              "sLast": "末頁"
          },

      },

      columns:[
        { data: "id" ,     name :"id"},
        { data: "name" ,   name: "name"}, // 返回 name 屬性
        { data: "username",name: "username" },
        { data: "dname",   name: "dname" },
        { data: "time",    name: "time"},
        { }
      ],

      //下拉選單
       initComplete: function () {
        this.api().columns().every(function () {
          var column = this;

        });


      }
    });
    $.fn.dataTable.ext.search.push(
      function(settings, data, dataIndex) {
        //var min = $('#minDate').datepicker("getDate");
        //var max = $('#maxDate').datepicker("getDate");
        var min = $('#minDate').val() ? new Date($('#minDate').val()) : null;;
        var max = $('#maxDate').val() ? new Date($('#maxDate').val()) : null;;
       //將結束日期設定為23點59分59秒 避免少計算半天
        if (max) {
        max.setHours(23);
        max.setMinutes(59);
        max.setSeconds(59);
      }
        var startDate = new Date(data[4]); // 假設日期位於第五列

        if (min == null && max == null) return true;
        if (min == null && startDate <= max) return true;
        if (max == null && startDate >= min) return true;
        if (startDate <= max && startDate >= min) return true;
        return false;
      }
    );


    // 監聽下拉選單的變化來過濾資料
    $('#selectdid').on('change', function(){
      datatable.column(3).search(this.value).draw(); // 第三列為部門
    });


    $('#minDate,#maxDate').on('change', function(){
      datatable.draw();
    });

    //編輯按鈕
    $('#account_list tbody').on('click', '.edit-btn', function () {
        var data = $('#account_list').DataTable().row($(this).parents('tr')).data()['id'];
        editUrl = editUrl.replace('id', data);
        window.location.href = editUrl;
    });
   //刪除按鈕
    $('#account_list tbody').on('click', '.del-btn', function () {
      var table = $('#account_list').DataTable();
      var row = $(this).parents('tr');
      var id = table.row(row).data()['id'];
      var username = table.row(row).data()['username'];
      delUrl = delUrl.replace('id', id);
      let answer = confirm('確定要刪除帳號：'+ username +' 嗎?');
      if (answer){
        /*alert(delUrl);*/
        //window.location.href = delUrl;//跳轉頁面
        $.ajax({
            url: delUrl,
            type: 'DELETE',
            headers: {
                //CSRF token
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(id) {
        if (id && id.success) {
          table.row(row).remove().draw(false);
          alert(username + ' 已被刪除。');
        } else {
          alert('伺服器已處理請求，但返回意外結果。');
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        if (xhr.status === 405 && xhr.responseText) {
          try {
            var resp = JSON.parse(xhr.responseText);
            if (resp && resp.success) {
              // 即使收到 405 錯誤，如果資料已被刪除則移除該行
              table.row(row).remove().draw(false);
              alert(username + ' 已被刪除。');
            } else {
              alert('刪除錯誤，無法從伺服器獲得正確的成功訊息。');
            }
          } catch (e) {
            // 如果響應不能被解析為 JSON，或者存在其他問題
            alert('刪除錯誤，狀態：' + xhr.status + '，錯誤訊息：' + thrownError);
          }
        } else {
          // 處理除 405 之外的其他 AJAX 錯誤
          alert('刪除錯誤，狀態：' + xhr.status + '，錯誤訊息：' + thrownError);
        }
      }
        });
      }
    });

  });



    </script>
@endsection
