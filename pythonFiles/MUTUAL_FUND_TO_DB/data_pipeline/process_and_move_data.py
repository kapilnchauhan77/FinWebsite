import time;
from tqdm import tqdm;
from datetime import datetime;
from get_data import download_data;
from dbconnect import Connection;


def process_data_and_move(status):
    if status:
        c, conn = Connection();

        c.execute("DELETE FROM `old_mutual_funds`;");
        c.execute("INSERT INTO `old_mutual_funds` (`Scheme Code`, `Net Asset Value`, `Date timestamp`) SELECT `Scheme Code`, `Net Asset Value`, `Date timestamp` FROM `mutual_funds`;");
        c.execute("DELETE FROM `mutual_funds`;");
        conn.commit();

        insert_count = 0;
        update_count = 0;
        inside_category = '';
        inside_name = '';

        with open('lastest_mf_file', 'rb') as f:
            data = f.readlines();
            types = data[0].decode('utf-8').strip().replace(';', '`, `');
            for datum in tqdm(data[1:]):
                datum = datum.decode('utf-8').strip();
                if datum == "":
                    continue;

                if 'Ended Schemes' in datum:
                    inside_category = str(datum);
                elif ';' in datum:
                    datum_list = datum.split(';');
                    date_time_stamp = int(time.mktime(datetime.strptime(datum_list[-1], "%d-%b-%Y").timetuple()));
                    try:
                        c.execute('INSERT INTO mutual_funds (`Category`, `MF House`, `'+ types +' timestamp`) VALUES (%s, %s, %s, %s, %s, %s, %s, %s);',
                                [inside_category, inside_name, *datum_list[:-1], date_time_stamp]);
                        insert_count += 1;

                    except Exception as e:
                        with open('logs/mf_logs', 'a') as f:
                            print(f'Error: {str(e)}', file=f);

                else:
                    inside_name = str(datum);

        conn.commit();
        c.close();
        conn.close();

        return insert_count;

    else: return False;

with open('logs/mf_logs', 'a') as f:
    insert_count = process_data_and_move(download_data());
    print(f'Successfully Inserted: {insert_count} rows at Timestamp: {time.time()}' + 2*'\n', file=f);
