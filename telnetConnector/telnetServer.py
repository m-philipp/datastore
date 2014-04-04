#!/usr/bin/env python
import socket, threading
import sys
import time
import MySQLdb
import re
import signal


db = MySQLdb.connect (host = "localhost",
                        user = "root",
                        passwd = "raspberry",
                        db = "datastore")
						


HOST = ''
PORT = 10020
TIMEOUT = 300
s = socket.socket(socket.AF_INET6, socket.SOCK_STREAM)
#s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
s.bind((HOST, PORT))
s.listen(4)
clients = [] #list of clients connected
lock = threading.Lock()
shutdown = 0


# def set_keepalive_linux(sock, after_idle_sec=1, interval_sec=3, max_fails=5):
def set_keepalive_linux(sock, after_idle_sec=1, interval_sec=5, max_fails=5):
    """Set TCP keepalive on an open socket.

    It activates after 1 second (after_idle_sec) of idleness,
    then sends a keepalive ping once every 3 seconds (interval_sec),
    and closes the connection after 5 failed ping (max_fails), or 15 seconds
    """
    sock.setsockopt(socket.SOL_SOCKET, socket.SO_KEEPALIVE, 1)
    sock.setsockopt(socket.IPPROTO_TCP, socket.TCP_KEEPIDLE, after_idle_sec)
    sock.setsockopt(socket.IPPROTO_TCP, socket.TCP_KEEPINTVL, interval_sec)
    sock.setsockopt(socket.IPPROTO_TCP, socket.TCP_KEEPCNT, max_fails)


def handleSigTERM(a, b):
    shutdown = 1

signal.signal(signal.SIGTERM, handleSigTERM)


def getValues(line):
    r = re.compile('\{([0-9]+),((?:[0-9]+\.[0-9]+)|[0-9]+),((?:[0-9]+\.[0-9]+)|[0-9]+)\}')
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
        # print "got some good value"
        return (streamId, val)

    # print "got some BAD value"
    return None

def store(streamId, value, timestamp):
    print "store: StreamId=" + str(streamId) + " Value=" + str(value)
    # prepare a cursor object using cursor() method
    cursor = db.cursor()
    #print "got db cursor"

    # Prepare SQL query to INSERT a record into the database.
    sql = "INSERT INTO store(streamId, \
           val, loggedTime) \
           VALUES ('%d', '%e', '%.6f' )" % \
           (streamId, value, timestamp)
               
    #print "set up sql query"
    try:
       # Execute the SQL command
       cursor.execute(sql)
       #print "executed cursor"
       # Commit your changes in the database
       db.commit()
       #print "comitted db"
       #print "stored some values in DB"
    except:
       # Rollback in case there is any error
       db.rollback()
       #print "did db rollback"
       # print "DB rollback cause of bad things happened"
    #print "leaving store function"

class archiver(threading.Thread):
    def __init__(self, valuesToStore):
        threading.Thread.__init__(self)
        self.valuesToStore = valuesToStore
  
    def run(self):
        while True:
            if len(self.valuesToStore) > 0:
                (rawLine, timestamp) = self.valuesToStore.pop()
                v = getValues(rawLine)
		if v:
                    store(v[0], v[1], timestamp)

class chatServer(threading.Thread):
    def __init__(self, (socket,address), valuesToStore):
        threading.Thread.__init__(self)
        self.valuesToStore = valuesToStore
        self.socket = socket
        self.address= address
        self.socket.settimeout(TIMEOUT)
	# set_keepalive_linux(self.socket)

    def run(self):
        lock.acquire()
        clients.append(self)
        lock.release()
        #print '%s:%s connected.' % self.address
        #print "connected"
        while True:
            #sys.stdout.write('.')
            data = None
            try:
                data = self.socket.recv(1024)
            except:
                if shutdown == 1:
                    break
                continue
        
            if not data:
                continue
            #print "#############################"
            #sys.stdout.write(data)
            #print "#############################"
            data = data.split( )
            for d in data:
                #print "--------------------------------"		
                #print d
                self.valuesToStore.append((d, time.time()))

        self.socket.close()
        #print '%s:%s disconnected.' % self.address
        #print "disconnect"
        lock.acquire()
        clients.remove(self)
        lock.release()

x = list()
archiver(x).start()

while True: # wait for socket to connect
    # send socket to chatserver and start monitoring
    if shutdown == 1:
        break
    chatServer(s.accept(), x).start()

s.close()

