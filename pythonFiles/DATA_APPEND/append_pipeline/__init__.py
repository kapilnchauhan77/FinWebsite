import os
from .data_extraction import extract_data
from .fincode_finding import get_fincode
import phpserialize
from io import StringIO, BytesIO
from datetime import datetime


def start_append(wo_ohlv = True):
    companyDataBse, companyDataNse = extract_data()

    fincodes = []
    anamolies = []

    companyDataBse_new = list()
    for companyDatumBse in companyDataBse:
        try:
            bse_code = companyDatumBse.pop('bse')
            fincode_ = get_fincode(bse_code, 'bse')
        except:
            # print(companyDatumBse)
            anamolies.append(bse_code)
            continue
        if fincode_ == 0:
            anamolies.append(bse_code)
            continue

        companyDatumBse['fincode'] = fincode_
        fincodes.append(fincode_)
        companyDataBse_new.append(companyDatumBse)


    use_nse_data = False
    companyDataNse_new = list()
    for companyDatumNse in companyDataNse:
        fincode_ = get_fincode(companyDatumNse.pop('nse'), 'nse')
        if fincode_ in fincodes:
            companyDataNse.remove(companyDatumNse)
            continue
        else:
            companyDatumNse['fincode'] = fincode_
            fincodes.append(fincode_)
            companyDataNse_new.append(companyDatumNse)
            use_nse_data = True

    companyDataBse = companyDataBse_new
    companyDataNse = companyDataNse_new

    print(companyDataBse[0])
    print(companyDataNse[0] if use_nse_data else 'NSE not needed')

    companyData = companyDataBse + companyDataNse

    criticalAnamolies = 0
    appendedValues = 0
    changedValues = 0
    files_read = 0
    files_tried_to_read = 0
    # print(f"len: {len(list(os.listdir('historical_prices')))}")

    for companyDatum in companyData:
    # for file_ in list(os.listdir('historical_prices')):
    #     if '_historical_price.prmt' not in file_:
    #         criticalAnamolies += 1
    #         continue

        changed = False

        try:
            fincode = int(companyDatum.pop('fincode'))
        except:
            criticalAnamolies += 1
            continue

        if not fincode:
            criticalAnamolies += 1
            continue

        file_name = f"historical_prices/{str(fincode)}_historical_price.prmt"
        # file_name = f"historical_prices/{file_}"
        files_tried_to_read += 1
        new_data = list()

        with open(file_name, "r") as f:
            try:
                data = str(f.readlines()[0])
            except:
                print(file_name)
                continue

            files_read += 1

            data = BytesIO(data.encode())

            try:
                full_data = list(phpserialize.load(data).values())
            except:
                print(file_name)
                criticalAnamolies += 1

            adjustedVal = [{key.decode(): val.decode() for key, val in
                phpserialize.dict_to_list(full_data[1])[0].items()}]
            data = phpserialize.dict_to_list(full_data[0])

        for datum in data:
            new_datum = dict()

            try:
                new_datum = {key.decode(): val.decode() if type(val) == bytes else
                        val for key, val in datum.items()}
            except Exception as e:
                print(str(e))
                print(datum)
                continue

            try:
                if 'Date' in new_datum:
                    changed = True
                    new_datum['date'] = int(datetime.timestamp(datetime.strptime(new_datum.pop('Date'),
                        '%Y-%m-%d %H:%M:%S')))

                if wo_ohlv:
                    changed = True
                    if 'open' in new_datum:
                        new_datum.pop('open')
                        new_datum.pop('high')
                        new_datum.pop('low')
                        new_datum.pop('volume')

            except:
                print('datum')
                print(new_datum)
                continue

            if 'Open' in new_datum:
                changed = True
                new_datum['open'] = new_datum.pop('Open')

            new_data.append(new_datum)

        try:
            if (new_data[-1]['date'] < companyDatum['date']):

                if wo_ohlv:
                    if 'open' in companyDatum:
                        companyDatum.pop('high')
                        companyDatum.pop('open')
                        companyDatum.pop('low')
                        companyDatum.pop('volume')

                # companyDatum['price'] = str(companyDatum['price'])
                new_data.append(companyDatum)
                appendedValues += 1
                changed = True
                # print(companyDatum)

        except Exception as e:
            print('new_data')
            print(new_data)
            print(str(e))
            break

        try:
            if changed == True:
                full_data = dict()
                full_data["Table"] = new_data
                full_data["Table1"] = adjustedVal
                changedValues += 1

                with open(file_name, "wb") as f:
                    f.write(phpserialize.dumps(full_data))

        except Exception as e:
            print('data')
            print(data)
            print(str(e))
            break

    print(f"No. of Anamolies: {str(len(anamolies))}")
    print(f"No. of Critical Anamolies: {str(criticalAnamolies)}")
    print(f"No. of Appended Value: {str(appendedValues)}")
    print(f"No. of Changed Value: {str(changedValues)}")
    print(f"No. of Files Read: {str(files_read)}")
    print(f"No. of Files Tried to Read: {str(files_tried_to_read)}")
    print(anamolies, file=open("anamolies.txt", "w"))

    return True
