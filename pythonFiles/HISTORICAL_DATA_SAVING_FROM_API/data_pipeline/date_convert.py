def date_conversion(dt):
    datetime_list = dt.split(' ')
    date_list = datetime_list[0].split('/')
    year = date_list[0] if len(date_list[0]) > 1 else '0' + date_list[0]
    month = date_list[1] if len(date_list[1]) > 1 else '0' + date_list[1]
    day = date_list[2] if len(date_list[2]) > 1 else '0' + date_list[2]
    date = year + '/' + month + '/' + day
    time = datetime_list[1]
    period = datetime_list[2]
    date_time = date + " " + time + " " + period

    return date_time
