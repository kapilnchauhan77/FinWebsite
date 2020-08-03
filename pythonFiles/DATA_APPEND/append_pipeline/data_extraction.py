from datetime import datetime


def extract_data():
    file_name = f'aceFiles\\latestAdjusted_bse_AceFile.ace'

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

        for datum in dataFromAceFIle:
            try:
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
                    pass

            except:
                print(datum)
                break

        del(dataFromAceFIle)

        file_name = f'aceFiles\\latestAdjusted_nse_AceFile.ace'

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
                    print(datum)
                    print("ERROR in NSE")
                    print(str(e))
                    break

        del(dataFromAceFIle)

        companyDataBse = [{'bse': datum[0],
                           'open': datum[2],
                           'price': datum[3],
                           'high': datum[4],
                           'low': datum[5],
                           'volume': int(datum[6]),
                           'date': int(datum[1])}
                          for datum in companyDataBse]

        companyDataNse = [{'nse': datum[0],
                           'open': datum[2],
                           'price': datum[3],
                           'high': datum[4],
                           'low': datum[5],
                           'volume': int(datum[6]),
                           'date': int(datum[1])}
                          for datum in companyDataNse]


        return companyDataBse, companyDataNse
