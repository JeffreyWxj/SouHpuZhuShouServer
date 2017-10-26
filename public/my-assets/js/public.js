var ajaxOptions = {
  // 兼容jQueryAjax
  beforeSend: function () {
    layer.load();
  },
  // 兼容jQueryForm
  beforeSubmit: function () {
    layer.load();
  },
  complete: function () {
    layer.closeAll('loading');
  },
  success: function (resp) {
    if (resp.status === 'success') {
      layer.alert(resp.msg, {icon: 6});
    } else {
      layer.alert(resp.msg, {icon: 5});
    }
  },
  error: function () {
    layer.alert('网络服务器错误,请稍后重试', {icon: 2});
  }
};