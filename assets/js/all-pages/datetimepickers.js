$(function () {
  console.log('datetime');
  $('.app-datepicker').datetimepicker({
    format: 'YYYY-MM-DD',
  });
  $('.app-timepicker').datetimepicker({
    format: 'HH:mm',
  });
})
