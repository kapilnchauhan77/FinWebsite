from .dbconnect import Connection


def get_fincode(code, type_):
    c, conn = Connection()

    if type_ == 'bse':
        c.execute("SELECT fincode FROM stock_companymaster WHERE scripcode = %s", [int(code), ])
    else:
        c.execute("SELECT fincode FROM stock_companymaster WHERE symbol = %s", [code, ])

    fincodes_ = c.fetchall()

    c.close()
    conn.close()

    try:
        fincode = [int(fncd[0]) for fncd in fincodes_][0]
    except:
        # print(f"{str(code)}_{str(type_)}")
        pass

    return fincode

