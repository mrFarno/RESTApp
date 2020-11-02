function calendar_settings(day, month, year, display) {
    $.ajax({
        url : 'index.php?page=calendar',
        type : 'POST',
        data : 'day='+day+'&month='+month+'&year='+year+'&display='+display,
        dataType : 'html',
        success: function(data) {
            container = document.getElementsByClassName('calendar-container')[0]
            container.innerHTML = data
        }
    })
}