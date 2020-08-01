from .date_convert import date_conversion
import requests
import datetime
import phpserialize


def download_and_save_data(fincode):
    URL = 'http://company.accordwebservices.com/Company/GetNSEBSESingleGraph'

    PARAMS = {
                'Type': 'H',
                'FINCODE': fincode,
                'STK': 'BSE',
                'DateOption': 'Y',
                'DateCount': 10,
                'StartDate': '',
                'EndDate': '',
                'token': 'So9q86WSaEBQERJJD3jRry2CxfpXdIVC'
             }

    r = requests.get(url = URL, params = PARAMS)

    data = r.json()

    for datum in data["Table"]:
        date_time = date_conversion(datum["Date"])
        datum["Date"] = str(datetime.datetime.strptime(date_time, "%m/%d/%Y %I:%M:%S %p"))
        datum.pop('pe')

    file_name = f"historical_prices/{str(fincode)}_historical_price.prmt"
    with open(file_name, "wb") as f:
        f.write(phpserialize.dumps(data))

    return True
