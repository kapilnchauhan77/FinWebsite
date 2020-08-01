from .fincode_retrival import get_fincodes
from .data_dwnld_save import download_and_save_data


def run_pipeline():
    fincodes = get_fincodes()

    for fincode in fincodes:
        try:
            result = download_and_save_data(fincode)

            if result != True:
                print("Some unfound bug, please check!")
                return False

            else:
                continue

        except Exception as e:
            print(str(e))
            return False

    return True
