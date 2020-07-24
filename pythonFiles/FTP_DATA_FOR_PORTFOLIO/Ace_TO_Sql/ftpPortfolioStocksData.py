import ftplib
import datetime


def get_path_name_type(company_broker):
    date_string = (str(datetime.datetime.now()).split(' ')[0].split('-')[2] +
                   str(datetime.datetime.now()).split(' ')[0].split('-')[1] +
                   str(datetime.datetime.now()).split(' ')[0].split('-')[0]
                   if (int(
                           str(
                               datetime.datetime.now()
                               ).split(' ')[1]
                           .split(':')[0]) >= 10)
                   else
                   str(int(
                           str(
                               datetime.datetime.now()
                               ).split(' ')[0]
                           .split('-')[2]) - 1) +
                   str(datetime.datetime.now()).split(' ')[0].split('-')[1] +
                   str(datetime.datetime.now()).split(' ')[0].split('-')[0])

    print(f'\n\nDate String: {date_string} for {company_broker}\n\n')

    print('Hour: ' +
          str(datetime.datetime.now()).split(' ')[1].split(':')[0] +
          '\n\n')

    type_of_stock_price = ('intraday'
                           if (int(
                                   str(
                                       datetime.datetime.now()
                                       ).split(' ')[1]
                                   .split(':')[0]) >= 10 and
                               int(
                                   str(
                                       datetime.datetime.now()
                                       ).split(' ')[1]
                                   .split(':')[0]) < 17)
                           else
                           'Adjusted')

    print('Type of ace file to download: ' + type_of_stock_price)

    file_name = f'aceFiles\\latest{type_of_stock_price}_{company_broker}_AceFile.ace'

    if type_of_stock_price == 'intraday':
        path = f'stocks/{type_of_stock_price}/{company_broker}/{date_string}'
    elif type_of_stock_price == 'Adjusted':
        path = f'stocks/{type_of_stock_price}/{company_broker}'
    else:
        raise 'There is Some Error in type of stock price'

    print('Path:' + path + '\n\n')

    return path, file_name, type_of_stock_price


def donwload_ace_file(company_broker):

    path, file_name, type_of_stock_price = get_path_name_type(company_broker)

    ftp = ftplib.FTP("ftpservice.acesphere.com")
    ftp.login("etc", "Etc$23Jul11")
    ftp.cwd(path)
    data = []
    ftp.dir(data.append)
    data = [int((datum.split(' ')[-1]).split('.')[0])
            for datum in data
            if datum.split(' ')[-1].split('.')[-1] == 'ace']
    data.sort(reverse=True)
    file_to_download = (str(data[0]) + '.ace')
    ftp.encoding = 'utf-8'
    ftp.retrlines("RETR " + file_to_download,
                  open(file_name, 'w').write)
    print(f"File Downloaded -   {type_of_stock_price}: {file_to_download},\n\t\
            Name: {file_name}\n\n")
    ftp.quit()
    print("Connection Closed")

    return file_name, type_of_stock_price
