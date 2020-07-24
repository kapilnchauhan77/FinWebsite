from .ftpPortfolioStocksData import donwload_ace_file
from datetime import datetime


def extract_data():
    file_name, file_type = donwload_ace_file('bse')

    print('\n\nBse File Name: ' + file_name + '\n\n')

    with open(file_name, 'r') as f:
        dataFromAceFIle = list(
                            map(
                                lambda sentence:
                                    sentence
                                    .replace('<</row>>', '')
                                    .replace('\n', '')
                                    .replace('<<eof>>', '')
                                    .split('|'),
                                    f.readlines()[0].split('<<row>>')))[1:]

        companyDataBse = []
        bseData = []

        for datum in dataFromAceFIle:
            try:
                if file_type == "intraday":
                    if int(datum[0]) > 80:
                        companyDataBse.append([int(datum[0])] +
                                               list(map(
                                                   lambda x:
                                                       float(x) if x != '' else 0,
                                                   datum[1:-1])) +
                                               [datetime.timestamp(
                                                   datetime.strptime(
                                                            datum[-1],
                                                            '%Y-%m-%d %H:%M:%S.%f')
                                                            )])
                    else:
                        bseData.append([int(datum[0])] +
                                       list(map(
                                               lambda x:
                                               float(x) if x != '' else 0,
                                               datum[1:-1])) +
                                       [datetime.timestamp(datetime.strptime(
                                                        datum[-1],
                                                        '%Y-%m-%d %H:%M:%S.%f')
                                                        )])
                else:
                    if int(datum[0]) > 80:
                        companyDataBse.append([int(datum[0])] +
                                               [datetime.timestamp(
                                                   datetime.strptime(
                                                            datum[1],
                                                            '%Y-%m-%d %H:%M:%S.%f')
                                                            )] +
                                               list(map(
                                                   lambda x:
                                                       float(x) if x != '' else 0,
                                                   datum[2:-1])))
                    else:
                        bseData.append([int(datum[0])] +
                                       [datetime.timestamp(datetime.strptime(
                                                        datum[1],
                                                        '%Y-%m-%d %H:%M:%S.%f')
                                                        )] +
                                       list(map(
                                               lambda x:
                                               float(x) if x != '' else 0,
                                               datum[2:-1])))

            except:
                print(datum)
                break

        del(dataFromAceFIle)

        file_name, file_type = donwload_ace_file('nse')
        print(file_type)

        with open(file_name, 'r') as f:
            dataFromAceFIle = list(
                                map(
                                    lambda sentence:
                                        sentence
                                        .replace('<</row>>', '')
                                        .replace('\n', '')
                                        .replace('<<eof>>', '')
                                        .split('|'),
                                        f.readlines()[0].split('<<row>>')))[1:]

            companyDataNse = []

            for datum in dataFromAceFIle:
                try:
                    if file_type == "intraday":
                        if int(datum[0]) > 0:
                            companyDataNse.append([int(datum[0])]  +
                                                   [str(datum[1])] +
                                                   list(map(
                                                       lambda x:
                                                           float(x) if x != '' else 0,
                                                       datum[3:-1])) +
                                                   [datetime.timestamp(
                                                       datetime.strptime(
                                                                datum[-1],
                                                                '%Y-%m-%d %H:%M:%S.%f')
                                                                )])
                        else:
                            pass
                    else:
                        companyDataNse.append([str(datum[0])]  +
                                               [datetime.timestamp(
                                                   datetime.strptime(
                                                            datum[1],
                                                            '%Y-%m-%d %H:%M:%S.%f')
                                                            )] +
                                               list(map(
                                                   lambda x:
                                                       float(x) if x != '' else 0,
                                                   datum[2:-1])))
                except Exception as e:
                    print(file_type)
                    print(datum)
                    print("ERROR in NSE")
                    print(str(e))
                    break

        del(dataFromAceFIle)

        if file_type == "intraday":
            companyDataBse = [{'bse': datum[0],
                               'open': datum[1],
                               'close': datum[2],
                               'high': datum[3],
                               'low': datum[4],
                               'volume': int(datum[-3]),
                               'date': int(datum[-1])}
                              for datum in companyDataBse]

            companyDataNse = [{'nse': datum[1],
                               'open': datum[10],
                               'close': datum[11],
                               'high': datum[12],
                               'low': datum[13],
                               'volume': int(datum[7]),
                               'date': int(datum[-1])}
                              for datum in companyDataNse]

            bseData = [{'index': datum[0],
                        'open': datum[1],
                        'close': datum[2],
                        'high': datum[3],
                        'low': datum[4],
                        'volume': int(datum[-3]),
                        'date': int(datum[-1])}
                       for datum in bseData]

        else:
            companyDataBse = [{'bse': datum[0],
                               'open': datum[2],
                               'close': datum[3],
                               'high': datum[4],
                               'low': datum[5],
                               'volume': int(datum[6]),
                               'date': int(datum[1])}
                              for datum in companyDataBse]

            companyDataNse = [{'nse': datum[0],
                               'open': datum[2],
                               'close': datum[3],
                               'high': datum[4],
                               'low': datum[5],
                               'volume': int(datum[6]),
                               'date': int(datum[1])}
                              for datum in companyDataNse]

            bseData = [{'index': datum[0],
                        'open': datum[2],
                        'close': datum[3],
                        'high': datum[4],
                        'low': datum[5],
                        'volume': int(datum[-3]),
                        'date': int(datum[1])}
                       for datum in bseData]

        print(len(companyDataBse))
        print(companyDataBse[0]) if len(companyDataBse) else print('No scripcode')
        print(len(companyDataNse))
        print(companyDataNse[0]) if len(companyDataNse) else print('No symbol')
        print(len(bseData))
        print(bseData[-1])

        return companyDataBse, companyDataNse, bseData, file_type
