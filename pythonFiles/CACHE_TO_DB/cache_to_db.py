import os
import phpserialize
from io import BytesIO
from datetime import datetime
from dbconnect import Connection


c, conn = Connection()

c.execute("CREATE TABLE IF NOT EXISTS historical_prices (id INT PRIMARY KEY AUTO_INCREMENT, fincode INT, date INT, price FLOAT)")
conn.commit()

c.close()
conn.close()


no_of_errors = 0
time_at_start = datetime.now()
fincode_p_data = list()
for file_num, historical_price in enumerate(os.listdir('..\\..\\historical_prices\\')):
    if '_' not in historical_price: continue

    fincode = historical_price.split('_')[0]

    with open(f'..\\..\\historical_prices\\{historical_price}', 'r') as data:
        try:
            data = phpserialize.dict_to_list(
                    list(phpserialize.load(
                        BytesIO(
                            str(data.readlines()[0])
                            .encode()))
                        .values())[0])
        except:
            no_of_errors += 1
            print(f'Error File: {historical_price}')
            continue

        new_data = list()
        for datum in data:
            new_datum = dict()

            new_datum = {key.decode(): val.decode() if type(val) == bytes else
                    val for key, val in datum.items()}

            new_data.append(new_datum)

        fincode_p_data.append({fincode: new_data})


time_taken_for_first_list = datetime.now()
print(f"\n\n\n\n\n\nTime taken for first list: {time_taken_for_first_list - time_at_start}")

time_taken_for_filter = datetime.now()
fincode_p_data = filter(lambda x: list(x.values())[0], fincode_p_data)
print(f"\n\n\n\n\n\nTime taken for filter: {time_taken_for_filter - time_taken_for_first_list}")


time_taken_for_sql_insertion = datetime.now()
c, conn = Connection()
for fincode_p_datum in fincode_p_data:
    fincode = list(fincode_p_datum.keys())[0]
    price_data = list(fincode_p_datum.values())[0]

    for price_datum in price_data:
        c.execute('INSERT INTO historical_prices (fincode, date, price) VALUES (%s, %s, %s);', [fincode, price_datum['date'], price_datum['price']])
conn.commit()
c.close()
conn.close()
print(f"\n\n\n\n\n\nTime taken for SQL Insertion: {time_taken_for_sql_insertion - time_taken_for_filter}")

to_print = list(fincode_p_data)
print(len(to_print))
print(f'\n\n\n\n\n\nNo of errors: {no_of_errors}')
# print(to_print[-1])
