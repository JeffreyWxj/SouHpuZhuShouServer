@extends('layouts.admin')
@section('content')
  <div class="row">
    <div class="col-md-4">
      <div class="card card-success">
        <div class="card-header">
          <div class="card-title">
            <div class="title">本学期开始时间</div>
          </div>
        </div>
        <div class="card-body">
          <form action="{{route('admin.system.term-start')}}" method="post" id="form-start-date">
            <div class="form-group">
              <label for="start-date-selector">本学期开始日期(第一周周一)：</label>
              <input type="text" id="start-date-selector" name="start_date" class="form-control" value="{{$term_start_date}}">
              <a href="javascript:void(0)" class="btn btn-success btn-block" id="btn-set-start-date">修改</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('foot')
  <script>
    $().ready(function () {
      // 初始化ajax表单
      $('#form-start-date').ajaxForm();
    });
    // 实例化日期选择器
    layui.use('laydate', function () {
      var laydate = layui.laydate;
      laydate.render({
        elem: '#start-date-selector'
      });
    });
    // 修改学期开始日期的按钮点击事件
    $('#btn-set-start-date').on('click', function () {
      $('#form-start-date').ajaxSubmit(ajaxOptions);
    });
  </script>
@endsection