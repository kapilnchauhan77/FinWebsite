from .dbconnect import Connection
from .extractData import extract_data


def ace_2_sql():
    c, conn = Connection()

    comapanyDataBse, comapanyDataNse, bseData, file_type = extract_data()
    file_type = file_type.lower()

    for bseDatum in bseData:
        try:
            existing_rows_bse = c.execute('SELECT indice FROM bse_indices WHERE indice = %s',
                                          [bseDatum['index']])
        except Exception as e:
            print("Error: {}\n\n".format(str(e)))
            print('SELECT indice FROM bse_indices WHERE indice = %s',
                  [bseDatum['index']])

        if int(existing_rows_bse):
            try:
                c.execute('UPDATE bse_indices SET indice = (%s), open = (%s), close = (%s), high = (%s), low = (%s), volume = (%s), date = (%s) WHERE indice = (%s)',
                          [bseDatum['index'], bseDatum['open'], bseDatum['close'], bseDatum['high'], bseDatum['low'], bseDatum['volume'], bseDatum['date'], bseDatum['index']])
            except Exception as e:
                print("Error: {}\n\n".format(str(e)))
                print('UPDATE bse_indices SET indice = (%s), open = (%s), close = (%s), high = (%s), low = (%s), volume = (%s), date = (%s) WHERE indice = (%s)',
                      [bseDatum['index'], bseDatum['open'], bseDatum['close'], bseDatum['high'], bseDatum['low'], bseDatum['volume'], bseDatum['date'], bseDatum['index']])
                break
        else:
            try:
                c.execute('INSERT INTO bse_indices (indice, open, close, high, low, volume, date) VALUES (%s, %s, %s, %s, %s, %s, %s);',
                          [bseDatum['index'], bseDatum['open'], bseDatum['close'], bseDatum['high'], bseDatum['low'], bseDatum['volume'], bseDatum['date']])
            except Exception as e:
                print("Error: {}\n\n".format(str(e)))
                print('INSERT INTO bse_indices (indice, open, close, high, low, volume, date) VALUES (%s, %s, %s, %s, %s, %s, %s);',
                      [bseDatum['index'], bseDatum['open'], bseDatum['close'], bseDatum['high'], bseDatum['low'], bseDatum['volume'], bseDatum['date']])
                break

    for companyDatumBse in comapanyDataBse:

        try:
            existing_rows = c.execute(f'SELECT bse FROM stock_prices_{file_type}_ftp_bse WHERE bse = %s',
                                      [companyDatumBse['bse']])

        except Exception as e:
            print("Error: {}\n\n".format(str(e)))
            print('SELECT bse FROM stock_prices_ftp_bse WHERE bse = {%s}',
                  [companyDatumBse['bse']])

        if int(existing_rows):
            try:
                c.execute(f'UPDATE stock_prices_{file_type}_ftp_bse SET bse = (%s), open = (%s), close = (%s), high = (%s), low = (%s), volume = (%s), date = (%s) WHERE bse = (%s)',
                          [companyDatumBse['bse'], companyDatumBse['open'], companyDatumBse['close'], companyDatumBse['high'], companyDatumBse['low'], companyDatumBse['volume'], companyDatumBse['date'], companyDatumBse['bse']])
            except Exception as e:
                print("Error: {}\n\n".format(str(e)))
                print('UPDATE stock_prices_ftp_bse SET bse = (%s), open = (%s), close = (%s), high = (%s), low = (%s), volume = (%s), date = (%s) WHERE bse = (%s)',
                      [companyDatumBse['bse'], companyDatumBse['open'], companyDatumBse['close'], companyDatumBse['high'], companyDatumBse['low'], companyDatumBse['volume'], companyDatumBse['date'], companyDatumBse['bse']])
                break
        else:
            try:
                c.execute(f'INSERT INTO stock_prices_{file_type}_ftp_bse (bse, open, close, high, low, volume, date) VALUES (%s, %s, %s, %s, %s, %s, %s);',
                          [companyDatumBse['bse'], companyDatumBse['open'], companyDatumBse['close'], companyDatumBse['high'], companyDatumBse['low'], companyDatumBse['volume'], companyDatumBse['date']])
            except Exception as e:
                print("Error: {}\n\n".format(str(e)))
                print('INSERT INTO stock_prices_ftp_bse (bse, open, close, high, low, volume, date) VALUES (%s, %s, %s, %s, %s, %s, %s);',
                      [companyDatumBse['bse'], companyDatumBse['open'], companyDatumBse['close'], companyDatumBse['high'], companyDatumBse['low'], companyDatumBse['volume'], companyDatumBse['date']])
                break

    for companyDatumNse in comapanyDataNse:

        try:
            existing_rows = c.execute(f'SELECT nse FROM stock_prices_{file_type}_ftp_nse WHERE nse = %s',
                                      [companyDatumNse['nse']])

        except Exception as e:
            print("Error: {}\n\n".format(str(e)))
            print(companyDatumNse)
            print(f'SELECT nse FROM stock_prices_{file_type}_ftp_nse WHERE nse = %s',
                  [companyDatumNse['nse']])

        if int(existing_rows):
            try:
                c.execute(f'UPDATE stock_prices_{file_type}_ftp_nse SET nse = (%s), open = (%s), close = (%s), high = (%s), low = (%s), volume = (%s), date = (%s) WHERE nse = (%s)',
                          [companyDatumNse['nse'], companyDatumNse['open'], companyDatumNse['close'], companyDatumNse['high'], companyDatumNse['low'], companyDatumNse['volume'], companyDatumNse['date'], companyDatumNse['nse']])
            except Exception as e:
                print("Error: {}\n\n".format(str(e)))
                print('UPDATE stock_prices_ftp_nse SET nse = (%s), open = (%s), close = (%s), high = (%s), low = (%s), volume = (%s), date = (%s) WHERE nse = (%s)',
                      [companyDatumNse['nse'], companyDatumNse['open'], companyDatumNse['close'], companyDatumNse['high'], companyDatumNse['low'], companyDatumNse['volume'], companyDatumNse['date'], companyDatumNse['nse']])
                break
        else:
            try:
                c.execute(f'INSERT INTO stock_prices_{file_type}_ftp_nse (nse, open, close, high, low, volume, date) VALUES (%s, %s, %s, %s, %s, %s, %s);',
                          [companyDatumNse['nse'], companyDatumNse['open'], companyDatumNse['close'], companyDatumNse['high'], companyDatumNse['low'], companyDatumNse['volume'], companyDatumNse['date']])
            except Exception as e:
                print("Error: {}\n\n".format(str(e)))
                print('INSERT INTO stock_prices_ftp_nse (nse, open, close, high, low, volume, date) VALUES (%s, %s, %s, %s, %s, %s, %s);',
                      [companyDatumNse['nse'], companyDatumNse['open'], companyDatumNse['close'], companyDatumNse['high'], companyDatumNse['low'], companyDatumNse['volume'], companyDatumNse['date']])
                break

    conn.commit()
    c.close()
    conn.close()

    return True
