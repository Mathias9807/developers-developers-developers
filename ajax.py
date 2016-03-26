#!/bin/env python
import json
import time
import cgi
import cgitb
cgitb.enable()

def createProduct(name, price, text1, text2):
    now = int(time.time())
    product = {
        'PRODUCT': {
            'NAME': name, 
            'PRICE': price, 
            'TEXTVAL1': text1, 
            'TEXTVAL2': text2
        }, 'META': {
            'LASTUPDATED': now, 
            'CREATED': now
        }
    }
    return product

products = [
    createProduct("Bosch Diskmaskin", 50.90, "En riktigt fin diskmaskin", "Bosch, designing good life"), 
    createProduct("Samsung Galaxy SII", 2222, "Samsungs flagskepp", "Samsung")
]

try:
    form = cgi.FieldStorage()
    
    # Print response header, mind the newline
    print("Content-type: text/json\n")
    
    query = form['query'].value

    if query == 'listProducts':
        print(json.dumps(products))
except Exception as e:
    print(str(e))
