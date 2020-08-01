from .dbconnect import Connection


def get_fincodes():
    c, conn = Connection()

    c.execute("SELECT fincode FROM stock_companymaster")
    fincodes = c.fetchall()

    c.close()
    conn.close()

    fincodes = [int(fncd[0]) for fncd in fincodes]
    return fincodes
