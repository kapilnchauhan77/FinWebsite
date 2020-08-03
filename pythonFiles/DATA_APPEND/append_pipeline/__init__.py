from .data_extraction import extract_data
from .fincode_finding import get_fincode
import phpserialize
from io import StringIO, BytesIO
from datetime import datetime


def start_append():
    companyDataBse, companyDataNse = extract_data()

    fincodes = []
    anamolies = 0

    companyDataBse_new = list()
    for companyDatumBse in companyDataBse:
        try:
            fincode_ = get_fincode(companyDatumBse.pop('bse'), 'bse')
        except:
            # print(companyDatumBse)
            anamolies += 1
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
    for companyDatum in companyData:
        try:
            fincode = int(companyDatum.pop('fincode'))
        except:
            criticalAnamolies += 1
            continue

        if not fincode:
            criticalAnamolies += 1
            continue

        file_name = f"historical_prices/{str(fincode)}_historical_price.prmt"
        new_data = list()

        with open(file_name, "r") as f:
            data = str(f.readlines()[0])
            data = BytesIO(data.encode())

            full_data = list(phpserialize.load(data).values())

            adjustedVal = [{key.decode(): val.decode() for key, val in phpserialize.dict_to_list(full_data[1])[0].items()}]
            data = phpserialize.dict_to_list(full_data[0])

        for datum in data:
            datum = {key.decode(): val.decode() for key, val in datum.items()}
            # datum['fincode'] = fincode
            # datum.pop('fincode')
            datum['open'] = datum.pop('Open')
            datum['date'] = int(datetime.timestamp(datetime.strptime(datum.pop('Date'), '%Y-%m-%d %H:%M:%S')))

            new_data.append(datum)

        if new_data[-1]['date'] < companyDatum['date']:
            new_data.append(companyDatum)
            appendedValues += 1

        # print(companyDatum)

        full_data = dict()
        full_data["Table"] = new_data
        full_data["Table1"] = adjustedVal

        # print(full_data)
        # break

        with open(file_name, "wb") as f:
            f.write(phpserialize.dumps(full_data))

    print(f"No. of Anamolies: {str(anamolies)}")
    print(f"No. of Critical Anamolies: {str(criticalAnamolies)}")
    print(f"No. of Appended Value: {str(appendedValues)}")

    return True
