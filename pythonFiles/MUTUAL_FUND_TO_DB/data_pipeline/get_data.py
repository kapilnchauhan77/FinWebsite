import requests;


def download_data():
    URL = 'https://www.amfiindia.com/spages/NAVAll.txt';

    try:
        with requests.get(url = URL) as r:
            try:
                r.raise_for_status();
            except Exception as e:
                with open('logs/mf_logs', 'a') as f:
                    print(f'Error: {str(e)}', file=f)
                return False;

            with open('lastest_mf_file', 'wb') as f:
                for chunk in r.iter_content(chunk_size=8192):
                    f.write(chunk);
    except Exception as e:
        with open('logs/mf_logs', 'a') as f:
            print(f'Error: {str(e)}', file=f)
        return False;

    return True;
