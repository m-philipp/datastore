import socket, threading
import sys
import time
import MySQLdb
import re


a = "{1,3.1415,4.1415}"
HOST = ''
PORT = 10016
#s = socket.socket(socket.AF_INET6, socket.SOCK_STREAM)
s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
s.bind((HOST, PORT))
s.listen(4)
clients = [] #list of clients connected
lock = threading.Lock()


db = MySQLdb.connect (host = "localhost",
                        user = "root",
                        passwd = "",
                        db = "datastore")
						


def getValues(line):
    r = re.compile('\{([0-9]+),(?:([0-9]+\.[0-9]+)|[0-9]+),(?:([0-9]+\.[0-9]+)|[0-9]+)\}')

    streamId = None
    val = None
    checkSum = None
    
    try:
        streamId = int(r.match(line).group(1))
        val =  float(r.match(line).group(2))
        checkSum =  float(r.match(line).group(3))
    except:
        return None
 
    approx_equal = lambda a, b, t: abs(a - b) < t
    
    if approx_equal(streamId + val, checkSum, 0.001):
        print "got some good value"
        return (streamId, val)

    print "got some BAD value"
    return None

def store(streamId, value):
    # prepare a cursor object using cursor() method
    cursor = db.cursor()
    print "entering store method"

    # Prepare SQL query to INSERT a record into the database.
    sql = "INSERT INTO store(streamId, \
           val, loggedTime) \
           VALUES ('%d', '%e', '%.6f' )" % \
           (streamId, value, time.time())
               
    try:
       # Execute the SQL command
       cursor.execute(sql)
       # Commit your changes in the database
       db.commit()
       print "stored some values in DB"
    except:
       # Rollback in case there is any error
       db.rollback()
       print "DB rollback cause of bad things happened"

  

class chatServer(threading.Thread):
    def __init__(self, (socket,address)):
        threading.Thread.__init__(self)
        self.socket = socket
        self.address= address

    def run(self):
        lock.acquire()
        clients.append(self)
        lock.release()
        #print '%s:%s connected.' % self.address
        print "connected"
        while True:
            data = self.socket.recv(1024)
            if not data:
                break

            v = getValues(data)
            if not None == v:
                store(v[0], v[1])
            sys.stdout.write(data)
        self.socket.close()
        #print '%s:%s disconnected.' % self.address
        print "disconnect"
        lock.acquire()
        clients.remove(self)
        lock.release()

while True: # wait for socket to connect
    # send socket to chatserver and start monitoring
    chatServer(s.accept()).start()


