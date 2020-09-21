import MySQLdb


def Connection():
    conn = MySQLdb.connect(host="localhost",
                           user="root",
                           passwd="",
                           db="wowondernew")
    c = conn.cursor()
    return c, conn
