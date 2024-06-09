import ibapi
from ibapi.client import EClient
from ibapi.order import *
import threading
import time
from ibapi.wrapper import EWrapper
from ibapi.contract import Contract
from flask import Flask, jsonify
from flask_cors import CORS
import pymongo

myclient = pymongo.MongoClient("mongodb://localhost:27017/")

db = myclient["investment_info"]

closeInfo = db["close_info"]


class IBApi(EWrapper,EClient):
    def __init__(self):
        EClient.__init__(self, self)
        self.close_price = 10
        self.app = Flask(__name__)
        CORS(self.app)
        self.app.add_url_rule('/close_price', 'close_price', self.get_close_price)
    def realtimeBar(self, reqId, time, open_, high, low, close,volume, wap, count):
        super().realtimeBar(reqId, time, open_, high, low, close, volume, wap, count)
        try:
            bot.on_bar_update(reqId, time, open_, high, low, close, volume, wap, count)
        except Exception as e:
            print(e)
    def get_close_price(self):
        return jsonify({'close_price': self.close_price})
class Bot:
    ib = None
    def __init__(self):
        self.ib = IBApi()
        self.ib.connect("127.0.0.1", 7497,1)
        self.ib.close_price = None
        ib_thread = threading.Thread(target=self.run_loop, daemon=True)
        ib_thread.start()
        time.sleep(1)
        symbol = input("Enter the symbol you want to trade : ")
        contract = Contract()
        contract.symbol = symbol.upper()
        contract.secType = "STK"
        contract.exchange = "SMART"
        contract.currency = "USD"
        self.ib.reqRealTimeBars(0, contract, 5, "TRADES", 1, [])

        order = Order()
        order.orderType = "MKT"  # or LMT ETC....
        order.action = "BUY"  # or SELL ETC...
        quantity = 1
        order.totalQuantity = quantity
        contract = Contract()
        contract.symbol = symbol
        contract.secType = "STK"  # or FUT ETC....
        contract.exchange = "SMART"
        contract.primaryExchange = "ISLAND"
        contract.currency = "USD"
        self.ib.placeOrder(2, contract, order)
    def run_loop(self):
        self.ib.run()
    def on_bar_update(self, reqId, time, open_, high, low, close, volume, wap, count):
        self.ib.close_price = close
        print(close)
        mydict = {"requestID" : reqID, "time": time, "open_" = open_, "high": high, "low": low, "close": close, "volume": volume, "wap": wap, "count": count}

        update = closeInfo.insert_one(mydict)      
    #displays certain information that the user might be interested in without things like request id or the db id, which are irrelevant
    def return_info(self){
        for x in closeInfo.find({}, {"_id": 0, "high": 1, "low": 1, "close": 1, "volume":1})
    }
bot = Bot()
bot.ib.app.run(host='0.0.0.0', port=5002)